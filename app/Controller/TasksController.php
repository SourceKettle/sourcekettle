<?php
/**
 *
 * TasksController Controller for the DevTrack system
 * Provides the hard-graft control of the tasks for users
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 DevTrack Development Team 2012
 * @link			http://github.com/SourceKettle/devtrack
 * @package		DevTrack.Controller
 * @since		 DevTrack v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppProjectController', 'Controller');

class TasksController extends AppProjectController {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Time', 'Task');

/**
 * Components
 *
 * @var array
 */
	public $components = array("RequestHandler");

/**
 * beforeFilter function.
 *
 * @access public
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(
			'api_all',
			'api_view'
		);

        $this->Security->unlockedActions = array (
            "starttask",
            "stoptask",
            "resolve",
            "unresolve"
        );
	}

/**
 * index method
 *
 * @return void
 */
	public function index($project = null, $statuses = null) {
		$project = $this->_projectCheck($project);
		if (preg_match('/^\s*\d+(\s*,\s*\d+)*\s*$/', $statuses)) {
			$this->set('task_status_filter', preg_replace('/[^\d,]/', '', $statuses));
		} else {
			$this->set('task_status_filter', 0);
		}
		$this->set('events', $this->Task->fetchHistory($project['Project']['id'], 5));
		$this->set('open_milestones', $this->Task->Milestone->getOpenMilestones(true));
	}

/**
 * others function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @return void
 */
	public function others($project = null, $statuses = null) {
		$this->index($project, $statuses);
		$this->render('index');
	}

/**
 * View tasks assigned to nobody
 *
 * @access public
 * @param mixed $project (default: null)
 * @return void
 */
	public function nobody($project = null, $statuses = null) {
		$this->index($project, $statuses);
		$this->render('index');
	}

/**
 * View all tasks
 *
 * @access public
 * @param mixed $project (default: null)
 * @return void
 */
	public function all($project = null, $statuses = null) {
		$this->index($project, $statuses);
		$this->render('index');
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($project = null, $id = null) {
		$project = $this->_projectCheck($project);
		$task = $this->Task->open($id);

		// If a User has commented
		if ($this->request->is('post') && isset($this->request->data['TaskComment'])) {

			$this->Task->TaskComment->create();

			$this->request->data['TaskComment']['task_id'] = $id;
			$this->request->data['TaskComment']['user_id'] = User::get('id');

			if ($this->Task->TaskComment->save($this->request->data)) {
				$this->Flash->info('The comment has been added successfully');
				unset($this->request->data['TaskComment']);
			} else {
				$this->Flash->error('The comment could not be saved. Please, try again.');
			}

			$this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $id));
			return;
		}

		// If a user is deleting a comment
		if ($this->request->is('post') && isset($this->request->data['TaskCommentDelete'])) {
			try
			{
				$this->Task->TaskComment->open($this->request->data['TaskCommentDelete']['id'], $task['Task']['id'], true, true);
			}
			catch ( ForbiddenException $e )
			{
				$this->Flash->error (__('You don\'t have permission to delete that comment'));
				$this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $id));
				return;
			}

			if ($this->Task->TaskComment->delete ($this->request->data['TaskCommentDelete']['id'])) {
				$this->Flash->info ('The comment has been deleted successfully.');
			} else {
				$this->Flash->error ('The comment could not be deleted. Please, try again.');
			}

			$this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $id));
			return;
		}

		// If a User has updated a comment
		if ($this->request->is('post') && isset($this->request->data['TaskCommentEdit'])) {
			$this->request->data['TaskComment'] = array(
				'comment' => $this->request->data['TaskCommentEdit']['comment'],
				'id' => $this->request->data['TaskCommentEdit']['id']
			);
			unset($this->request->data['TaskCommentEdit']);

			try {
				$this->Task->TaskComment->open($this->request->data['TaskComment']['id'], $task['Task']['id'], true, true);
			} catch (ForbiddenException $e) {
				$this->Flash->error (__('You don\'t have permission to edit that comment'));
				$this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $id));
				return;
			}

			if ($this->Task->TaskComment->save($this->request->data)) {
				$this->Flash->info('The comment has been updated successfully');
				unset($this->request->data['TaskComment']);
			} else {
				$this->Flash->error('The comment could not be updated. Please, try again.');
			}

			$this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $id));
			return;
		}

		// If a User has assigned someone
		if ($this->request->is('post') && isset($this->request->data['TaskAssignee']) && isset($this->request->data['TaskAssignee']['assignee'])) {

			$_email = $this->Useful->extractEmail($this->request->data['TaskAssignee']['assignee']);
			unset($this->request->data['TaskAssignee']);

			if ($user = $this->Task->Assignee->findByEmail($_email)) {
				if ($this->Task->Project->hasWrite($user['Assignee']['id'])) {
					$this->Task->set('assignee_id', $user['Assignee']['id']);
					$this->Flash->u($this->Task->save());
				} else {
					$this->Flash->error('The assignee could not be updated. The selected user is not a collaborator!');
				}
			} else {
				$this->Flash->error('The assignee could not be updated. Please, try again.');
			}

			$this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $id));
			return;
		}

		// Re-read to pick up changes
		$this->set('task', $this->Task->open($id));

		// Fetch the changes that will have happened
		$changes	= $this->Task->Project->ProjectHistory->find(
			'all',
			array(
				'conditions' => array(
					'ProjectHistory.row_id' => $this->Task->id,
					'ProjectHistory.row_field !=' => '+',
					'ProjectHistory.model' => 'task'
				)
			)
		);
		$comments = $this->Task->TaskComment->find('all', array('conditions' => array('Task.id' => $this->Task->id)));

		// They are in the wrong format for the sort function - so move the modified field
		foreach ($changes as $x => $change) {
			$changes[$x]['created'] = $change['ProjectHistory']['created'];
		}
		foreach ($comments as $x => $comment) {
			$comments[$x]['created'] = $comment['TaskComment']['created'];
		}

		// Fetch any additional users that may be needed
		$changeUsers = array();
		$this->Task->Assignee->recursive = -1;
		foreach ($changes as $change) {
			if ($change['ProjectHistory']['row_field'] == 'assignee_id') {
				$_old = $change['ProjectHistory']['row_field_old'];
				$_new = $change['ProjectHistory']['row_field_new'];

				if ($_old && !isset($changeUsers[$_old])) {
					$this->Task->Assignee->id = $_old;
					$_temp = $this->Task->Assignee->read();
					$changeUsers[$_old] = array($_temp['Assignee']['name'], $_temp['Assignee']['email']);
				}
				if ($_new && !isset($changeUsers[$_new])) {
					$this->Task->Assignee->id = $_new;
					$_temp = $this->Task->Assignee->read();
					$changeUsers[$_new] = array($_temp['Assignee']['name'], $_temp['Assignee']['email']);
				}
			}
		}

		// Merge the changes
		$changes = array_merge($changes, $comments);

		// Sort function for events
		// assumes $array{ $array{ 'modified' => 'date' }, ... }
		$cmp = function($a, $b) {
			if (strtotime($a['created']) == strtotime($b['created'])) return 0;
			if (strtotime($a['created']) > strtotime($b['created'])) return 1;
			return -1;
		};

		usort($changes, $cmp);
		$this->set('change_users', $changeUsers);
		$this->set('changes', $changes);

		$times = $this->Task->Time->find(
			'all',
			array(
				'conditions' => array(
					'Time.task_id' => $this->Task->id
				)
			)
		);
		$this->set('times', $times);
		$this->set('tasks', $this->Task->fetchLoggableTasks());
		$this->set('collaborators', $this->Task->Project->Collaborator->collaboratorsForProject($project['Project']['id']));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($project = null) {
		$project = $this->_projectCheck($project, true);

		if ($this->request->is('ajax') || $this->request->is('post')) {
			$this->Task->create();

			$this->request->data['Task']['project_id']		= $project['Project']['id'];
			$this->request->data['Task']['owner_id']		= User::get('id');
			$this->request->data['Task']['assignee_id']		= null;
			$this->request->data['Task']['task_status_id']	= 1;

			if (isset($this->request->data['Task']['milestone_id']) && $this->request->data['Task']['milestone_id'] == 0) {
				$this->request->data['Task']['milestone_id'] = null;
			}

			if (isset($this->request->data['Task']['task_type_id']) && $this->request->data['Task']['task_type_id'] == 0) {
				$this->request->data['Task']['task_type_id'] = 3;
			}

			if (isset($this->request->data['Task']['task_priority_id']) && $this->request->data['Task']['task_priority_id'] == 0) {
				$this->request->data['Task']['task_priority_id'] = 2;
			}

			if ($this->request->is('ajax')) {
				$this->autoRender = false;

				if ($this->Task->saveAll($this->request->data)) {
					echo '<div class="alert alert-success"><a class="close" data-dismiss="alert">x</a>Task successfully created.</div>';
				} else {
					echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">x</a>Could not add task to the project. Please, try again.</div>';
				}
			} else if ($this->request->is('post')) {
				if ($this->Flash->c($this->Task->saveAll($this->request->data))) {
					$this->redirect(array('project' => $project['Project']['name'], 'action' => 'view', $this->Task->id));
				}
			}
		}

		// Fetch all the variables for the view
		$taskPriorities	= $this->Task->TaskPriority->find('list', array('order' => 'id DESC'));
		$milestonesOpen	= $this->Task->Milestone->getOpenMilestones(true);
		$milestonesClosed = $this->Task->Milestone->getClosedMilestones(true);
		$milestones = array('No Assigned Milestone');
		if (!empty($milestonesOpen)) {
			$milestones['Open'] = $milestonesOpen;
		}
		if (!empty($milestonesClosed)) {
			$milestones['Closed'] = $milestonesClosed;
		}
		foreach ($taskPriorities as $id => $p) {
			$taskPriorities[$id] = ucfirst(strtolower($p));
		}

		$availableTasks = $this->Task->find('list', array(
			'conditions' => array('project_id =' => $project['Project']['id']),
			'fields' => array('Task.id', 'Task.subject'),
		));
		$this->set(compact('taskPriorities', 'milestones', 'availableTasks'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($project = null, $id = null) {
		$project = $this->_projectCheck($project, true);
		$task = $this->Task->open($id);

		if ($this->request->is('post') || $this->request->is('put')) {

			unset($this->request->data['Task']['project_id']);
			unset($this->request->data['Task']['owner_id']);

			if ($this->Flash->u($this->Task->save($this->request->data))) {
				$this->redirect(array('project' => $project['Project']['name'], 'action' => 'view', $this->Task->id));
			}
		} else {
			$this->request->data = $task;

			// Fetch all the variables for the view
			$taskPriorities = $this->Task->TaskPriority->find('list', array('order' => 'id DESC'));
			$milestonesOpen = $this->Task->Milestone->getOpenMilestones(true);
			$milestonesClosed = $this->Task->Milestone->getClosedMilestones(true);
			$milestones = array('No Assigned Milestone');
			if (!empty($milestonesOpen)) {
				$milestones['Open'] = $milestonesOpen;
			}
			if (!empty($milestonesClosed)) {
				$milestones['Closed'] = $milestonesClosed;
			}
			foreach ($taskPriorities as $id => $p) {
				$taskPriorities[$id] = ucfirst(strtolower($p));
			}
			$availableTasks = $this->Task->find('list', array(
				'conditions' => array('project_id =' => $project['Project']['id'], 'id !=' => $this->Task->id),
				'fields' => array('Task.id', 'Task.subject'),
			));

			$this->Task->bindModel(array('hasOne' => array('TaskDependency')));

			$this->set(compact('taskPriorities', 'milestones', 'availableTasks'));
		}
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	// Temporarily commented out - tasks should not be deleted
	// public function delete($project = null, $id = null) {
	//	 $project = $this->_projectCheck($project, true);
	//	 $task = $this->Task->open($id);
	//
	//	 if (!$this->request->is('post')) throw new MethodNotAllowedException();
	//
	//	 $this->Flash->setUp();
	//	 $this->Flash->d($this->Task->delete());
	//	 $this->redirect(array('action' => 'index'));
	// }

/**
 * starttask function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @param mixed $id (default: null)
 * @return void
 */
	public function starttask($project = null, $id = null) {
		$task = $this->Task->open($id);

		$isAjax = $this->request->is("ajax");

		// check assigned
		if (!$this->Task->isAssignee()) {
			if ($isAjax) {
				$this->set("error", "not_assignee");
				$this->set("_serialize", array("error"));
			} else {
				$this->Flash->error('You can not start work on a task not assigned to you.');
				$this->redirect(array('project' => $project, 'action' => 'view', $id));
			}

			return;
		}

		//check open
		if (!$this->Task->isOpen()) {
			if ($isAjax) {
				$this->set("error", "not_open");
				$this->set("_serialize", array("error"));
			} else {
				$this->Flash->error('You can not start work on a task that is not open.');
				$this->redirect(array('project' => $project, 'action' => 'view', $id));
			}

			return;
		}

		$updated = $this->__updateTaskStatus($project, $id, 2, $isAjax);

		if ($isAjax) {
			if ($updated) {
				$this->set("error", "no_error");
			} else {
				$this->set("error", "failed_to_save");
			}
			$this->set("_serialize", array("error"));
		} else {
			$this->redirect(array('project' => $project, 'action' => 'view', $id));
		}
	}

/**
 * stoptask function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @param mixed $id (default: null)
 * @return void
 */
	public function stoptask($project = null, $id = null) {
		$isAjax = $this->request->is("ajax");

		$task = $this->Task->open($id);

		// check assigned
		if (!$this->Task->isAssignee()) {
			if ($isAjax) {
				$this->set("error", "not_assignee");
				$this->set("_serialize", array("error"));
			} else {
				$this->Flash->error('You can not stop work on a task not assigned to you.');
				$this->redirect(array('project' => $project, 'action' => 'view', $id));
			}

			return;
		}

		//check inProgress
		if (!$this->Task->isInProgress()) {
			if ($isAjax) {
				$this->set("error", "not_in_progress");
				$this->set("_serialize", array("error"));
			} else {
				$this->Flash->error('You can not stop work on a task that is not in progress.');
				$this->redirect(array('project' => $project, 'action' => 'view', $id));
			}

			return;
		}

		$updated = $this->__updateTaskStatus($project, $id, 1, $isAjax);
		if ($isAjax) {
			if ($updated) {
				$this->set("error", "no_error");
			} else {
				$this->set("error", "failed_to_save");
			}
			$this->set("_serialize", array("error"));
		} else {
			$this->redirect(array('project' => $project, 'action' => 'view', $id));
		}
	}

/**
 * opentask function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @param mixed $id (default: null)
 * @return void
 */
	public function opentask($project = null, $id = null) {
		$isAjax = $this->request->is("ajax");

		$success = $this->__updateTaskStatus($project, $id, 1, $isAjax);
		$this->redirect(array('project' => $project, 'action' => 'view', $id));
	}

/**
 * closetask function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @param mixed $id (default: null)
 * @return void
 */
	public function closetask($project = null, $id = null) {
		$isAjax = $this->request->is("ajax");

		$success = $this->__updateTaskStatus($project, $id, 4, $isAjax);

		// If a User has commented
		if (isset($this->request->data['TaskComment']['comment']) && $this->request->data['TaskComment']['comment'] != '') {
			$this->Task->TaskComment->create();

			$this->request->data['TaskComment']['task_id'] = $id;
			$this->request->data['TaskComment']['user_id'] = $this->Auth->user('id');

			if ($this->Task->TaskComment->save($this->request->data)) {
				$this->Flash->info('The comment has been added successfully');
				unset($this->request->data['TaskComment']);
			} else {
				$this->Flash->error('The comment could not be saved. Please, try again.');
			}
		}
		$this->redirect(array('project' => $project, 'action' => 'view', $id));
	}

	public function resolve($project = null, $id = null){
		$isAjax = $this->request->is("ajax");

		$success = $this->__updateTaskStatus($project, $id, 3, $isAjax);
		if ($isAjax) {
			if ($success) {
				$this->set ("error", "no_error");
			} else {
				$this->set ("error", "failed_to_save");
			}

			$this->set ("_serialize", array ("error"));
		} else {
			// If a User has commented
			if (isset($this->request->data['TaskComment']['comment']) && $this->request->data['TaskComment']['comment'] != '') {
				$this->Task->TaskComment->create();

				$this->request->data['TaskComment']['task_id'] = $id;
				$this->request->data['TaskComment']['user_id'] = $this->Auth->user('id');

				if ($this->Task->TaskComment->save($this->request->data)) {
					$this->Flash->info('The comment has been added successfully');
					unset($this->request->data['TaskComment']);
				} else {
					$this->Flash->error('The comment could not be saved. Please, try again.');
				}
			}
			$this->redirect(array('project' => $project, 'action' => 'view', $id));
		}
	}

	public function unresolve($project = null, $id = null){
		$isAjax = $this->request->is("ajax");

		$success = $this->__updateTaskStatus($project, $id, 1, $isAjax);

		if ($isAjax) {
			if ($success) {
				$this->set ("error", "no_error");
			} else {
				$this->set ("error", "failed_to_save");
			}

			$this->set ("_serialize", array ("error"));
		} else {
			// If a User has commented
			if (isset($this->request->data['TaskComment']['comment']) && $this->request->data['TaskComment']['comment'] != '') {
				$this->Task->TaskComment->create();

				$this->request->data['TaskComment']['task_id'] = $id;
				$this->request->data['TaskComment']['user_id'] = $this->Auth->user('id');

				if ($this->Task->TaskComment->save($this->request->data)) {
					$this->Flash->info('The comment has been added successfully');
					unset($this->request->data['TaskComment']);
				} else {
					$this->Flash->error('The comment could not be saved. Please, try again.');
				}
			}
			$this->redirect(array('project' => $project, 'action' => 'view', $id));
		}
	}

/**
 * freeze function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @param mixed $taskId (default: null)
 * @throws MethodNotAllowedException
 */
	public function freeze ($project = null, $taskId = null) {
		if (!$this->request->is("ajax")) {
			throw new MethodNotAllowedException();
		}

		$project = $this->_projectCheck($project, true);
		$this->Task->open ($taskId);
		$this->Task->set ("milestone_id", 0);

		if (!$this->Task->save()) {
			$this->set ("error", "failed_to_save");
		} else {
			$this->set ("error", "no_error");
		}

		$this->set ("_serialize", array ("error"));
	}

	public function thaw ($project = null, $taskId = null ) {
		if (!$this->request->is("ajax")) {
			throw new MethodNotAllowedException();
		}

		$milestone = $this->request->data['milestone'];

		$project = $this->_projectCheck($project, true);
		$this->Task->open ($taskId);
		$this->Task->set ("milestone_id", $milestone);

		if (!$this->Task->save()) {
			$this->set ("error", "failed_to_save");
		} else {
			$this->set ("error", "no_error");
		}

		$this->set ("_serialize", array ("error"));
	}

/**
 * __updateTaskStatus function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @param mixed $id (default: null)
 * @return void
 */
	private function __updateTaskStatus($project = null, $id = null, $status = null, $isAjax = false) {
		$project = $this->_projectCheck($project, true);
		$task = $this->Task->open($id);

		$this->Task->set('task_status_id', $status);
        $result = $this->Task->save();
        if ($isAjax) {
            return $result;
        } else {
            return $this->Flash->U($result);
        }
	}

	/* ************************************************* *
	*													 *
	*			API SECTION OF CONTROLLER				 *
	*			 CAUTION: PUBLIC FACING					 *
	*													 *
	* ************************************************** */

/**
 * api_view function.
 *
 * @access public
 * @param mixed $id (default: null)
 * @return void
 */
	public function api_view($id = null) {
		$this->layout = 'ajax';

		$this->Task->recursive = 0;

		$data = array();

		if ($id == null) {
			$this->response->statusCode(400);
			$data['error'] = 400;
			$data['message'] = 'Bad request, no project id specified.';
		}

		if ($id == 'all') {
			$this->api_all();
			return;
		}

		if (is_numeric($id)) {
			$this->Task->id = $id;

			if (!$this->Task->exists()) {
				$this->response->statusCode(404);
				$data['error'] = 404;
				$data['message'] = 'No task found of that ID.';
				$data['id'] = $id;
			} else {
				$task = $this->Task->read();

				$this->Task->Project->id = $task['Task']['project_id'];

				$partOfProject = $this->Task->Project->hasRead($this->Auth->user('id'));
				$publicProject	= $this->Task->Project->field('public');
				$isAdmin = ($this->_apiAuthLevel() == 1);

				if ($publicProject || $isAdmin || $partOfProject) {
					//task_type_id
					unset($task['Task']['task_type_id']);
					$task['Task']['type'] = $task['TaskType']['name'];
					//task_status_id
					unset($task['Task']['task_status_id']);
					$task['Task']['status'] = $task['TaskStatus']['name'];
					//task_priority_id
					unset($task['Task']['task_priority_id']);
					$task['Task']['priority'] = $task['TaskPriority']['name'];

					$data = $task['Task'];
				} else {
					$data['error'] = 401;
					$data['message'] = 'Task found, but is not public.';
					$data['id'] = $id;
				}
			}
		}

		$this->set('data',$data);
		$this->render('/Elements/json');
	}

/**
 * api_all function.
 * ADMINS only
 *
 * @access public
 * @return void
 */
	public function api_all() {
		$this->layout = 'ajax';

		$this->Task->recursive = 0;
		$data = array();

		switch ($this->_apiAuthLevel()) {
			case 1:
				foreach ($this->Task->find("all", array('conditions' => array('order' => 'task_priority_id DESC'))) as $task) {
					//task_type_id
					unset($task['Task']['task_type_id']);
					$task['Task']['type'] = $task['TaskType']['name'];
					//task_status_id
					unset($task['Task']['task_status_id']);
					$task['Task']['status'] = $task['TaskStatus']['name'];
					//task_priority_id
					unset($task['Task']['task_priority_id']);
					$task['Task']['priority'] = $task['TaskPriority']['name'];

					$data[] = $task['Task'];
				}
				break;
			default:
				$this->response->statusCode(403);
				$data['error'] = 403;
				$data['message'] = 'You are not authorised to access this.';
		}

		$this->set('data',$data);
		$this->render('/Elements/json');
	}

/**
 * api_marshalled function.
 * THIS RETURNS HTML
 *
 * @access public
 * @return void
 */
	public function api_marshalled() {
		$this->layout = 'ajax';

		$data = array();
		$request = array();

		// Fetch the request from the user
		if (!is_null($this->request->query)) {
			$request = $this->request->query;
		}

		$user = $this->Auth->user('id');

		if (empty($request)) {
			$this->response->statusCode(400);
			$data['error'] = 400;
			$data['message'] = 'Bad request, empty query specified.';
		} else if (!array_key_exists('project', $request)) {
			$this->response->statusCode(400);
			$data['error'] = 400;
			$data['message'] = 'Bad request, no project specified.';
		} else if (is_null($user) || $user < 1 || !($project = $this->_projectCheck($request['project']))) {
			$this->response->statusCode(403);
			$data['error'] = 403;
			$data['message'] = 'You are not authorised to access this.';
		} else {
			$conditions = array('Task.project_id' => $project['Project']['id']);

			if (array_key_exists('milestone', $request) && is_numeric($request['milestone']) && $request['milestone'] > 0) {
				$this->Task->Milestone->id = $request['milestone'];
				if ($this->Task->Milestone->exists()) {
					$conditions['milestone_id'] = $request['milestone'];
				}
			}

			// Ive assumed the user is logged in
			if (array_key_exists('requester', $request)) {
				switch ($request['requester']) {
					case 'index':
						$conditions['assignee_id'] = $user;
						break;
					case 'nobody':
						$conditions['assignee_id'] = null;
						break;
					case 'all':
						break;
					default:
						$conditions['assignee_id !='] = $user;
				}
			} else {
				$conditions['assignee_id'] = $user;
			}

			// Ive assumed the user is logged in

			// If they want one or more specific task statuses, add some conditions
			if (array_key_exists('statuses', $request)) {
				$or = array();

				foreach (preg_split('/\s*,\s*/', trim($request['statuses'])) as $statusId) {

					$status = $this->Task->TaskStatus->findById($statusId);

					if ($status != null) {
						$or[] = array('Task.task_status_id' => $status['TaskStatus']['id']);
					}
				}

				if (!empty($or)) {
					$conditions['OR'] = $or;
				}
			}

			if (array_key_exists('types', $request) && $request['types'] != '' && $types = explode(',', $request['types'])) {
				$conditions['task_type_id'] = array();
				foreach ($types as $i) {
					if (is_numeric($i) && $i > 0 && $i < 7) {
						$conditions['task_type_id'][] = $i;
					}
				}
			}

			foreach ($this->Task->find("all", array('conditions' => $conditions, 'order' => array('task_status_id ASC', 'task_priority_id DESC'))) as $task) {
				$data[] = $task;
			}
		}
		$this->set('data',$data);
	}
}
