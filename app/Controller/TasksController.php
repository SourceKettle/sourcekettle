<?php
/**
 *
 * TasksController Controller for the SourceKettle system
 * Provides the hard-graft control of the tasks for users
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link			http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.Controller
 * @since		 SourceKettle v 0.1
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

	public $uses = array(
		'Project',
		'Task',
		'TaskStatus',
		'TaskType', 
		'TaskPriority',
		'User',
		'Milestone',
		'Collaborator',
		'Team',
	);

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

		$this->Security->unlockedActions = array (
			'starttask',
			'stoptask',
			'resolve',
			'unresolve',
			'freeze',
			'api_update'
		);
	}
	// Which actions need which authorization levels (read-access, write-access, admin-access)
	protected function _getAuthorizationMapping() {

		return array(
			'index'  => 'read',
			'others'  => 'read',
			'nobody'  => 'read',
			'all'  => 'read',
			'view'   => 'read',
			'comment' => 'write',
			'deleteComment' => 'write',
			'updateComment' => 'write',
			'edit'   => 'write',
			'add'   => 'write',
			'assign'   => 'write',
			'opentask' => 'write',
			'closetask' => 'write',
			'starttask' => 'write',
			'stoptask' => 'write',
			'resolve' => 'write',
			'unresolve' => 'write',
			'freeze' => 'write',
			'api_marshalled' => 'read',
			'api_all' => 'read',
			'api_view' => 'read',
			'api_update' => 'write',
			'personal_kanban' => 'login',
		);
	}

	// Special case for team kanban, requires team membership instead of project collaboration
	public function isAuthorized($user) {

		if (!$this->sourcekettle_config['Features']['task_enabled']['value']) {
			if ($this->sourcekettle_config['Features']['task_enabled']['source'] == "Project-specific settings") {
				throw new ForbiddenException(__('This project does not have task tracking enabled. Please contact a project administrator to enable task tracking.'));
			} else {
				throw new ForbiddenException(__('This system does not allow task tracking. Please contact a system administrator to enable task tracking.'));
			}
		}

		if ($this->action != 'team_kanban') {
			return parent::isAuthorized($user);
		}

		if (!$user['is_active']) {
			return false;
		}

		$teamId = $this->Team->field('id', array('name' => $this->params->params['team']));
		if (empty($teamId) || $teamId < 1) {
			throw new NotFoundException(__("Invalid team"));
		}

		if ($user['is_admin']) {
			return true;
		}

		if (!$this->Team->isMember($teamId, $user['id'])) {
			$this->Auth->authError = __('You must be a team member to view the team kanban chart.');
			return false;
		} else {
			return true;
		}

	}
/**
 * index method
 *
 * @return void
 */
	public function index($project = null) {
		$project = $this->_getProject($project);

		// Convert from comma separated to arrays
		$statuses   = preg_split('/\s*,\s*/', trim(@$this->request->query['statuses']),   null, PREG_SPLIT_NO_EMPTY);
		$priorities = preg_split('/\s*,\s*/', trim(@$this->request->query['priorities']), null, PREG_SPLIT_NO_EMPTY);
		$types      = preg_split('/\s*,\s*/', trim(@$this->request->query['types']),      null, PREG_SPLIT_NO_EMPTY);
		$assignees  = preg_split('/\s*,\s*/', trim(@$this->request->query['assignees']),  null, PREG_SPLIT_NO_EMPTY);
		$creators   = preg_split('/\s*,\s*/', trim(@$this->request->query['creators']),   null, PREG_SPLIT_NO_EMPTY);
		$milestones = preg_split('/\s*,\s*/', trim(@$this->request->query['milestones']), null, PREG_SPLIT_NO_EMPTY);

		// Filter out invalid entries
		$selected_statuses   = $this->TaskStatus->filterValid($statuses);
		$selected_priorities = $this->TaskPriority->filterValid($priorities);
		$selected_types      = $this->TaskType->filterValid($types);
		$selected_assignees  = $this->User->filterValid($assignees);
		$selected_creators   = $this->User->filterValid($creators);
		$selected_milestones = $this->Milestone->filterValid($milestones);

		// For the filters: lists of available statuses, priorities etc.
		$milestones    = $this->Project->Milestone->listMilestoneOptions();
		$milestones = array($milestones[0]) + $milestones['Open'] + $milestones['Closed'];
		$statuses      = $this->TaskStatus->find('list', array());
		$priorities    = $this->TaskPriority->find('list', array());
		$types         = $this->TaskType->find('list', array());
		$collaborators = $this->Project->listCollaborators();

		// Combine the data for easily generating URLs for the task filter bar
		// Yes, this is horrible.
		$filter_clear_status = 'priorities='.urlencode(implode(',', array_keys($selected_priorities))).
							   '&types='.urlencode(implode(',', array_keys($selected_types))).
							   '&assignees='.urlencode(implode(',', array_keys($selected_assignees))).
							   '&creators='.urlencode(implode(',', array_keys($selected_creators))).
							   '&milestones='.urlencode(implode(',', array_keys($selected_milestones)));

		$filter_clear_priority = 'statuses='.urlencode(implode(',', array_keys($selected_statuses))).
							   '&types='.urlencode(implode(',', array_keys($selected_types))).
							   '&assignees='.urlencode(implode(',', array_keys($selected_assignees))).
							   '&creators='.urlencode(implode(',', array_keys($selected_creators))).
							   '&milestones='.urlencode(implode(',', array_keys($selected_milestones)));

		$filter_clear_type   = 'statuses='.urlencode(implode(',', array_keys($selected_statuses))).
							   '&priorities='.urlencode(implode(',', array_keys($selected_priorities))).
							   '&assignees='.urlencode(implode(',', array_keys($selected_assignees))).
							   '&creators='.urlencode(implode(',', array_keys($selected_creators))).
							   '&milestones='.urlencode(implode(',', array_keys($selected_milestones)));

		$filter_clear_assignee = 'statuses='.urlencode(implode(',', array_keys($selected_statuses))).
							     '&priorities='.urlencode(implode(',', array_keys($selected_priorities))).
							     '&types='.urlencode(implode(',', array_keys($selected_types))).
							     '&creators='.urlencode(implode(',', array_keys($selected_creators))).
							     '&milestones='.urlencode(implode(',', array_keys($selected_milestones)));

		$filter_clear_creator = 'statuses='.urlencode(implode(',', array_keys($selected_statuses))).
							    '&priorities='.urlencode(implode(',', array_keys($selected_priorities))).
							    '&types='.urlencode(implode(',', array_keys($selected_types))).
							    '&assignees='.urlencode(implode(',', array_keys($selected_assignees))).
							    '&milestones='.urlencode(implode(',', array_keys($selected_milestones)));

		$filter_clear_milestone = 'statuses='.urlencode(implode(',', array_keys($selected_statuses))).
							      '&priorities='.urlencode(implode(',', array_keys($selected_priorities))).
							      '&types='.urlencode(implode(',', array_keys($selected_types))).
							      '&assignees='.urlencode(implode(',', array_keys($selected_assignees))).
							      '&creators='.urlencode(implode(',', array_keys($selected_creators)));

		$filter_urls = array(
			'status'    => array(
				'clear' => $filter_clear_status,
				'all'   => $filter_clear_status.'&statuses=all',
			),
			'priority'  => array(
				'clear' => $filter_clear_priority,
				'all'   => $filter_clear_priority.'&priorities=all',
			),
			'type'      => array(
				'clear' => $filter_clear_type,
				'all'   => $filter_clear_type.'&types=all',
			),
			'assignee'  => array(
				'clear' => $filter_clear_assignee,
				'all'   => $filter_clear_assignee.'&assignees=all',
				'none'  => $filter_clear_assignee.'&assignees=0',
			),
			'creator'   => array(
				'clear' => $filter_clear_creator,
				'all'   => $filter_clear_creator.'&creators=all',
			),
			'milestone' => array(
				'clear' => $filter_clear_milestone,
				'all'   => $filter_clear_milestone.'&milestones=all',
				'none'  => $filter_clear_milestone.'&milestones=0',
			),
		);

		foreach ($statuses as $id => $status) {
			$filter_urls['status'][$id] = $filter_clear_status."&statuses=".urlencode($status);
		}

		foreach ($priorities as $id => $priority) {
			$filter_urls['priority'][$id] = $filter_clear_priority."&priorities=".urlencode($priority);
		}

		foreach ($types as $id => $type) {
			$filter_urls['type'][$id] = $filter_clear_type."&types=".urlencode($type);
		}

		foreach ($collaborators as $id => $assignee) {
			$filter_urls['assignee'][$id] = $filter_clear_assignee."&assignees=".urlencode($id);
		}

		foreach ($collaborators as $id => $creator) {
			$filter_urls['creator'][$id] = $filter_clear_creator."&creators=".urlencode($id);
		}

		foreach ($milestones as $id => $milestone) {
			$filter_urls['milestone'][$id] = $filter_clear_milestone."&milestones=".urlencode($id);
		}

		$this->set('filter_urls', $filter_urls);

		$conditions = array('Task.project_id' => $project['Project']['id']);

		if (!empty($selected_statuses)) {
			$conditions['Task.task_status_id'] = array_keys($selected_statuses);
		}
		if (!empty($selected_priorities)) {
			$conditions['Task.task_priority_id'] = array_keys($selected_priorities);
		}
		if (!empty($selected_types)) {
			$conditions['Task.task_type_id'] = array_keys($selected_types);
		}
		if (!empty($selected_assignees)) {
			$conditions['Task.assignee_id'] = array_keys($selected_assignees);
		}
		if (!empty($selected_creators)) {
			$conditions['Task.owner_id']   = array_keys($selected_creators);
		}
		if (!empty($selected_milestones)) {
			$conditions['Task.milestone_id'] = array_keys($selected_milestones);
		}

		// Load task list based on the filtering rules
		$tasks = $this->Task->find('all', array(
			'conditions' => $conditions,
		));
		$this->set('tasks', $tasks);


		$this->set('statuses',      $statuses);
		$this->set('priorities',    $priorities);
		$this->set('types',         $types);
		$this->set('collaborators', $collaborators);
		$this->set('milestones',    $milestones);

		$this->set('selected_statuses',   $selected_statuses);
		$this->set('selected_priorities', $selected_priorities);
		$this->set('selected_types',      $selected_types);
		$this->set('selected_assignees',  $selected_assignees);
		$this->set('selected_creators',   $selected_creators);
		$this->set('selected_milestones', $selected_milestones);


	}

	public function personal_kanban() {

		$backlog = $this->User->tasksOfStatusForUser($this->Auth->user('id'), 'open');
		$inProgress = $this->User->tasksOfStatusForUser($this->Auth->user('id'), 'in progress');
		$completed = $this->User->tasksOfStatusForUser($this->Auth->user('id'), array('resolved', 'closed'));

		$this->set(compact('backlog', 'inProgress', 'completed'));

	}

	public function team_kanban($team = null) {

		// NB we check it's valid in the isAuthorized method, so no need to check again
		$team = $this->Team->findByName($team);

		$backlog = $this->Team->tasksOfStatusForTeam($team['Team']['id'], 'open');
		$inProgress = $this->Team->tasksOfStatusForTeam($team['Team']['id'], 'in progress');
		$completed = $this->Team->tasksOfStatusForTeam($team['Team']['id'], array('resolved', 'closed'));

		$this->set(compact('team', 'backlog', 'inProgress', 'completed'));

	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($project = null, $public_id = null) {
		$project = $this->_getProject($project);
		$task = $this->Task->open($public_id);
		$current_user = $this->Auth->user();

		// Re-read to pick up changes
		$this->set('task', $this->Task->open($public_id));

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
					if (count($_temp) > 0) {
						$changeUsers[$_old] = array($_temp['Assignee']['name'], $_temp['Assignee']['email']);
					} else {
						$changeUsers[$_old] = array('(Unknown or deleted user)', '');
					}
				}
				if ($_new && !isset($changeUsers[$_new])) {
					$this->Task->Assignee->id = $_new;
					$_temp = $this->Task->Assignee->read();
					if (count($_temp) > 0) {
						$changeUsers[$_new] = array($_temp['Assignee']['name'], $_temp['Assignee']['email']);
					} else {
						$changeUsers[$_new] = array('(Unknown or deleted user)', '');
					}
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
		$this->set('tasks', $this->Task->fetchLoggableTasks($this->Auth->user('id')));
		$collabs = $this->Task->Project->Collaborator->collaboratorsForProject($project['Project']['id']);
		$collabs[0] = "None";
		ksort($collabs);
		$this->set('collaborators', $collabs);

	}

	public function assign($project = null, $public_id = null) {
		$project = $this->_getProject($project);
		$task = $this->Task->open($public_id);

		if (!$this->request->is('post') || !isset($this->request->data['Assignee']) || !isset($this->request->data['Assignee']['id'])) {
			return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $public_id));
		}
		
		$assigneeId = $this->request->data['Assignee']['id'];
		if ($assigneeId == 0 || $this->Task->Project->hasWrite($assigneeId)) {
			$this->Task->set('assignee_id', $assigneeId);
			$this->Flash->u($this->Task->save());
		} else {
			$this->Flash->error('The assignee could not be updated. The selected user is not a collaborator!');
		}
	
		unset($this->request->data['Assignee']);
	
		return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $public_id));
	}

/**
 * Post a comment for a task
 */
	public function comment($project = null, $public_id = null) {
		$project = $this->_getProject($project);
		$task = $this->Task->open($public_id);

		// No comment posted, redirect to the task
		if (!$this->request->is('post') || !isset($this->request->data['TaskComment'])) {
			return $this->redirect(array ('project' => $project['Project']['name'], 'action' => 'view', $public_id));
		}

		$this->Task->TaskComment->create();

		$this->request->data['TaskComment']['task_id'] = $this->Task->id;
		$this->request->data['TaskComment']['user_id'] = $this->Auth->user('id');

		// NB do not add a flash message, as they'll see the new task has been added
		if ($this->Task->TaskComment->save($this->request->data)) {
			unset($this->request->data['TaskComment']);
		} else {
			$this->Flash->error(__('The comment could not be saved. Please try again.'));
		}

		return $this->redirect(array ('controller' => 'tasks', 'project' => $project['Project']['name'], 'action' => 'view', $public_id));

	}

/**
 * Remove a comment from a task
 */
	public function deleteComment($project = null, $id = null) {

		$project = $this->_getProject($project);
		$comment = $this->Task->TaskComment->findById($id);

		if (!$comment) {
			throw new NotFoundException(__("Cannot find a comment with ID $id"));
		}

		$taskId = $comment['Task']['public_id'];

		// Check that we own the comment or we are a project or system admin...
		if (
			$comment['User']['id'] != $this->Auth->user('id')
			&& !$this->Project->hasWrite($this->Auth->user('id'))
			&& !$this->Auth->user('is_admin')
		) {
			$this->Flash->error (__('You don\'t have permission to delete that comment'));
			return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $taskId));
		}


		if (!$this->request->is('post') || !isset($this->request->data['TaskCommentDelete'])) {
			return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $taskId));
		}


		if ($this->Task->TaskComment->delete($id)) {
			$this->Flash->info (__('The comment has been deleted successfully.'));
		} else {
			$this->Flash->error (__('The comment could not be deleted. Please try again.'));
		}

		return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $taskId));
	}

/**
 * Change an existing comment's text
 */
	public function updateComment ($project = null, $id = null) {

		$project = $this->_getProject($project);
		$comment = $this->Task->TaskComment->findById($id);

		if (!$comment) {
			throw new NotFoundException(__("Cannot find a comment with ID $id"));
		}

		$taskId = $comment['Task']['public_id'];
		// Only the comment owner can edit the comment
		if ($comment['User']['id'] != $this->Auth->user('id')) {
			$this->Flash->error (__('You don\'t have permission to edit that comment'));
			return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $taskId));
		}

		// Check we have new POST data for the comment...
		if (!$this->request->is('post') || !isset($this->request->data['TaskCommentEdit'])) {
			return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $taskId));
		}
		$this->request->data['TaskComment'] = array(
			'comment' => $this->request->data['TaskCommentEdit']['comment'],
			'id' => $id,
		);
		unset($this->request->data['TaskCommentEdit']);

		if ($this->Task->TaskComment->save($this->request->data)) {
			$this->Flash->info(__('The comment has been updated successfully'));
			unset($this->request->data['TaskComment']);
		} else {
			$this->Flash->error(__('The comment could not be updated. Please try again.'));
		}

		return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $taskId));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($project = null) {


		$project = $this->_getProject($project);
		$current_user = $this->viewVars['current_user'];

		// Milestone pre-selected - parse and store
		if (!empty($this->request->query['milestone'])) {
			$selected_milestone_id = preg_replace('/[^\d]/', '', $this->request->query['milestone']);
		} else {
			$selected_milestone_id = 0;
		}

		if ($this->request->is('ajax') || $this->request->is('post')) {
			$this->Task->create();

			$this->request->data['Task']['project_id']		= $project['Project']['id'];
			$this->request->data['Task']['owner_id']		= $current_user['id'];
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

					// If they pre-selected a milestone, go back to that milestone
					if ($selected_milestone_id) {
						return $this->redirect(array('controller' => 'milestones', 'project' => $project['Project']['name'], 'action' => 'view', $selected_milestone_id));
					} else {
						// ...otherwise show the task.
						return $this->redirect(array('project' => $project['Project']['name'], 'action' => 'view', $this->Task->id));
					}
				}
			}
		} else {
			// GET request: set default priority, type and assignment
			// TODO define the defaults somewhere useful?
			$this->request->data['Task']['task_priority_id'] = 2;
			$this->request->data['Task']['task_type_id'] = 1;
			$this->request->data['Task']['assignee_id'] = 0;

			if ($selected_milestone_id) {
				$this->request->data['Task']['milestone_id'] = $selected_milestone_id;
			} else{
				$this->request->data['Task']['milestone_id'] = null;
			}
		}

		$milestones = $this->Milestone->listMilestoneOptions();

		$taskPriorities	= $this->Task->TaskPriority->find('list', array('fields' => array('id', 'label'), 'order' => 'level DESC'));

		$availableTasks = $this->Task->find('list', array(
			'conditions' => array('project_id =' => $project['Project']['id']),
			'fields'     => array('Task.id', 'Task.subject'),
		));

		$assignees = $this->Task->Project->Collaborator->collaboratorsForProject($project['Project']['id']);
		$assignees[0] = "None";
		ksort($assignees);

		$this->set(compact('taskPriorities', 'milestones', 'availableTasks', 'assignees'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($project = null, $public_id = null) {
		$project = $this->_getProject($project);
		$task = $this->Task->open($public_id);

		$milestones = $this->Milestone->listMilestoneOptions();

		$taskPriorities	= $this->Task->TaskPriority->find('list', array('fields' => array('id', 'label'), 'order' => 'level DESC'));

		$availableTasks = $this->Task->find('list', array(
			'conditions' => array('project_id =' => $project['Project']['id'], 'id !=' => $this->Task->id),
			'fields' => array('Task.id', 'Task.subject'),
		));

		$assignees = $this->Task->Project->Collaborator->collaboratorsForProject($project['Project']['id']);
		$assignees[0] = "None";
		ksort($assignees);

		$this->set(compact('taskPriorities', 'milestones', 'availableTasks', 'assignees'));

		if ($this->request->is('post') || $this->request->is('put')) {

			$this->request->data['Task']['project_id'] = $project['Project']['id'];
			unset($this->request->data['Task']['owner_id']);

			$this->request->data['Task']['id'] = $this->Task->id;

			$saved = $this->Task->save($this->request->data);
			$this->request->data['Task']['public_id'] = $public_id;

			if ($this->Flash->u($this->Task->save($this->request->data))) {
				return $this->redirect(array('project' => $project['Project']['name'], 'action' => 'view', $public_id));
			} else {
				$this->request->data = array_merge($task, $this->request->data);
			}
		} else {
			$this->request->data = $task;
		}
	}

/*
 * These functions are convenient helpers for changing task statuses
 */
	public function starttask($project = null, $public_id = null) {
		return $this->__updateTaskStatus($project, $public_id, 'in progress');
	}

	public function stoptask($project = null, $public_id = null) {
		return $this->__updateTaskStatus($project, $public_id, 'open');
	}

	public function opentask($project = null, $public_id = null) {
		return $this->__updateTaskStatus($project, $public_id, 'open');
	}

	public function closetask($project = null, $public_id = null) {
		return $this->__updateTaskStatus($project, $public_id, 'closed');
	}

	public function resolve($project = null, $public_id = null) {
		return $this->__updateTaskStatus($project, $public_id, 'resolved');
	}

	public function unresolve($project = null, $public_id = null) {
		return $this->__updateTaskStatus($project, $public_id, 'open');
	}

	public function freeze($project = null, $public_id = null) {
		return $this->__updateTaskStatus($project, $public_id, 'dropped');
	}

/**
 * __updateTaskStatus function. Does the bulk of the work for changing task statuses,
 * including error handling and adding comments if we have them.
 *
 * @access public
 * @param mixed $project (default: null)
 * @param mixed $id (default: null)
 * @return void
 */
	private function __updateTaskStatus($project = null, $public_id = null, $status_name = null) {

		$project = $this->_getProject($project);
		$task = $this->Task->open($public_id);
		$status = $this->TaskStatus->nameToId($status_name);
		$isAjax = $this->request->is("ajax");

		$this->Task->set('task_status_id', $status);
		$this->Task->project_id = $project['Project']['id'];
		$success = $this->Task->save();

		$messages = array();
		if ($success) {
			$messages[] = __("Task #%d updated - status set to %s", $public_id, $status_name);
		} else {
			$messages[] = __("Could not update status for task #%d", $public_id);
		}

		// If a user has made a comment, add it
		if ($success && isset($this->request->data['TaskComment']['comment']) && $this->request->data['TaskComment']['comment'] != '') {
			$this->Task->TaskComment->create();

			$this->request->data['TaskComment']['task_id'] = $this->Task->id;
			$this->request->data['TaskComment']['user_id'] = $this->Auth->user('id');

			if ($this->Task->TaskComment->save($this->request->data)) {
				$messages[] = __("Comment added to task %d", $public_id);
				unset($this->request->data['TaskComment']);
			} else {
				$success = 0;
				$messages[] = __("Could not add comment to task #%d", $public_id);
			}
		}

		// Set appropriate error/success messages
		if ($isAjax) {
			if ($success) {
				$this->set("error", "no_error");
				$this->set("errorDescription", join("; ", $messages));
			} else {
				$this->set("error", "failed_to_save");
				$this->set("errorDescription", join("; ", $messages));
			}

			$this->set ("_serialize", array ("error", "errorDescription"));
		} else {
			if ($success) {
				$this->Flash->info(join("; ", $messages));
			} else {
				$this->Flash->error(join("; ", $messages));
			}
		}
		
		// Redirect to the view task page
		return $this->redirect(array('controller' => 'tasks', 'project' => $project['Project']['name'], 'action' => 'view', $public_id));
	}



	/* ************************************************* *
	*													 *
	*			API SECTION OF CONTROLLER				 *
	*			 CAUTION: PUBLIC FACING					 *
	*													 *
	* ************************************************** */

	function api_update($project_name = null, $public_id = null) {
		$this->layout = 'ajax';
		$data = array();

		if ($project_name == null) {
			$this->response->statusCode(400);
			$this->set('data', array('error' => 400, 'message' => __('Bad request, no project specified.')));
			$this->render('/Elements/json');
			return;

		} elseif ($public_id == null) {
			$this->response->statusCode(400);
			$this->set('data', array('error' => 400, 'message' => __('Bad request, no task ID specified.')));
			$this->render('/Elements/json');
			return;

		} elseif (!is_numeric($public_id)) {
			$this->response->statusCode(400);
			$this->set('data', array('error' => 400, 'message' => __('Bad request, task ID should be numeric.')));
			$this->render('/Elements/json');
			return;

		}

		$task = $this->Task->find('first', array('conditions' => array(
			'Task.public_id' => $public_id,
			'Project.name' => $project_name,
		)));

		if (empty($task)) {
			$this->response->statusCode(404);
			$this->set('data', array('error' => 404, 'message' => __("Task with ID %s not found for project %s", $public_id, $project_name)));
			$this->render('/Elements/json');
			return;
		
		}

		// Make sure we're operating  on the correct task ID...
		$this->request->data['id'] = $task['Task']['id'];

		$task = $this->Task->save($this->request->data);

		if ($task) {
			$this->response->statusCode(200);
			$data = $task['Task'];
			unset($data['id']);
			$data['error'] = 'no_error';

		} else {
			$this->response->statusCode(500);
			$data = array('error' => 500, 'message' => __('Task update failed'));
		}

		$this->set('data',$data);
		$this->render('/Elements/json');
	}

}
