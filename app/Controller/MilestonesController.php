<?php
/**
 *
 * MilestonesController Controller for the DevTrack system
 * Provides the hard-graft control of the Milestones for projects
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

class MilestonesController extends AppProjectController {

	public $helpers = array('Task');

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
	}

/**
 * index method
 *
 * @return void
 */
	public function index($project = null) {
		$this->redirect(array('project' => $project, 'action' => 'open'));
	}

/**
 * index method
 *
 * @return void
 */
	public function open($project = null) {
		$project = $this->_projectCheck($project);

		$milestones = array();
		// Iterate over all milestones
		foreach ($this->Milestone->getOpenMilestones() as $x) {
			$oTasks = $this->Milestone->openTasksForMilestone($x);
			$iTasks = $this->Milestone->inProgressTasksForMilestone($x);
			$rTasks = $this->Milestone->resolvedTasksForMilestone($x);
			$cTasks = $this->Milestone->closedTasksForMilestone($x);
			$dPoints = 0;
			foreach (array_merge($rTasks, $cTasks) as $task) {
				if ($task['Task']['story_points'] != null) {
					$dPoints += $task['Task']['story_points'];
				}
			}
			$tPoints = $dPoints;
			foreach (array_merge($oTasks, $iTasks) as $task) {
				if ($task['Task']['story_points'] != null) {
					$tPoints += $task['Task']['story_points'];
				}
			}

			$this->Milestone->id = $x;
			$milestone = $this->Milestone->read();

			$milestone['Milestone']['cTasks'] = count($cTasks);
			$milestone['Milestone']['iTasks'] = count($iTasks);
			$milestone['Milestone']['rTasks'] = count($rTasks);
			$milestone['Milestone']['oTasks'] = count($oTasks);
			$milestone['Milestone']['tPoints'] = $tPoints;
			$milestone['Milestone']['dPoints'] = $dPoints;

			$milestones[$x] = $milestone;
		}
		$this->set('milestones', $milestones);
		$this->render('open_closed');
	}

/**
 * index method
 *
 * @return void
 */
	public function closed($project = null) {
		$project = $this->_projectCheck($project);

		$milestones = array();
		// Iterate over all milestones
		foreach ($this->Milestone->getClosedMilestones() as $x) {
			$oTasks = $this->Milestone->openTasksForMilestone($x);
			$iTasks = $this->Milestone->inProgressTasksForMilestone($x);
			$rTasks = $this->Milestone->resolvedTasksForMilestone($x);
			$cTasks = $this->Milestone->closedTasksForMilestone($x);
			$dPoints = 0;
			foreach (array_merge($rTasks, $cTasks) as $task) {
				if ($task['Task']['story_points'] != null) {
					$dPoints += $task['Task']['story_points'];
				}
			}
			$tPoints = $dPoints;
			foreach (array_merge($oTasks, $iTasks) as $task) {
				if ($task['Task']['story_points'] != null) {
					$tPoints += $task['Task']['story_points'];
				}
			}

			$this->Milestone->id = $x;
			$milestone = $this->Milestone->read();

			$milestone['Milestone']['cTasks'] = count($cTasks);
			$milestone['Milestone']['iTasks'] = count($iTasks);
			$milestone['Milestone']['rTasks'] = count($rTasks);
			$milestone['Milestone']['oTasks'] = count($oTasks);
			$milestone['Milestone']['tPoints'] = $tPoints;
			$milestone['Milestone']['dPoints'] = $dPoints;

			$milestone['Milestone']['closed_tasks'] = count($cTasks);
			$milestone['Milestone']['open_tasks'] = count($oTasks);

			$milestones[$x] = $milestone;
		}
		$this->set('milestones', $milestones);
		$this->render('open_closed');
	}

/**
 * view method
 *
 * @return void
 */
	public function view($project = null, $id = null) {
		$project = $this->_projectCheck($project);
		$milestone = $this->Milestone->open($id);

		$backlog = $this->Milestone->openTasksForMilestone($id);
		$inProgress = $this->Milestone->inProgressTasksForMilestone($id);
		$completed = $this->Milestone->closedOrResolvedTasksForMilestone($id);

		$iceBox = $this->Milestone->Task->find('all', array(
			'conditions' => array(
				'Task.project_id' => $project['Project']['id'],
				'OR' => array(
					array('milestone_id' => null),
					array('milestone_id' => 0)
				),
			)
		));

		// Sort function for tasks
		$cmp = function($a, $b) {
			if (strtotime($a['Task']['task_priority_id']) == strtotime($b['Task']['task_priority_id'])) return 0;
			if (strtotime($a['Task']['task_priority_id']) > strtotime($b['Task']['task_priority_id'])) return 1;
			return -1;
		};

		usort($completed, $cmp);

		// Final value is min size of the board
		$max = max(count($backlog), count($inProgress), count($completed), 3);

		$this->set('milestone', $milestone);

		$this->set('backlog_empty', $max - count($backlog));
		$this->set('inProgress_empty', $max - count($inProgress));
		$this->set('completed_empty', $max - count($completed));
		$this->set(compact('backlog', 'inProgress', 'completed', 'iceBox'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($project = null) {
		$project = $this->_projectCheck($project, true);

		if ($this->request->is('post')) {
			$this->Milestone->create();

			$this->request->data['Milestone']['project_id'] = $project['Project']['id'];

			// Force new milestones into the 'open' state, this makes the most sense...
			$this->request->data['Milestone']['is_open'] = true;

			if ($this->Flash->c($this->Milestone->save($this->request->data))) {
				$this->redirect(array('project' => $project['Project']['name'], 'action' => 'view', $this->Milestone->id));
			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($project = null, $id = null) {
		$project = $this->_projectCheck($project, true);
		$milestone = $this->Milestone->open($id);

		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['Milestone']['project_id'] = $project['Project']['id'];

			if ($this->Flash->u($this->Milestone->save($this->request->data))) {
				$this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
			}
		} else {
			$this->request->data = $milestone;
		}
		$this->set('milestone', $milestone);
	}

/**
 * close method
 *
 * @param string $id
 * @return void
 */
	public function close($project = null, $id = null) {
		$project = $this->_projectCheck($project, true);
		$milestone = $this->Milestone->open($id);

		if ($this->request->is('post') || $this->request->is('put')) {
			/*$this->request->data['Milestone']['project_id'] = $project['Project']['id'];
			
			if ($this->Flash->u($this->Milestone->save($this->request->data))) {
				$this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
			}*/
		} else {
			$this->request->data = $milestone;
		}
		$this->set('milestone', $milestone);
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($project = null, $id = null) {
		$project = $this->_projectCheck($project, true, true);
		$milestone = $this->Milestone->open($id);

		$this->Flash->setUp();

		if ($this->request->is('post')) {
			if ($this->Flash->d($this->Milestone->delete())) {
				$this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
			}
		}
		$this->set('object', array(
			'name' => $milestone['Milestone']['subject'],
			'id'	=> $milestone['Milestone']['id']
		));
		$this->set('objects', $this->Milestone->preDelete());
		$this->render('/Elements/Project/delete');
	}

	/* ************************************************ *
	*													*
	*			API SECTION OF CONTROLLER				*
	*			 CAUTION: PUBLIC FACING					*
	*													*
	* ************************************************* */

/**
 * api_view function.
 *
 * @access public
 * @param mixed $id (default: null)
 * @return void
 */
	public function api_view($id = null) {
		$this->layout = 'ajax';

		$this->Milestone->recursive = -1;
		$this->Milestone->Task->recursive = -1;

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
			$this->Milestone->id = $id;

			if (!$this->Milestone->exists()) {
				$this->response->statusCode(404);
				$data['error'] = 404;
				$data['message'] = 'No milestone found of that ID.';
				$data['id'] = $id;
			} else {
				$milestone = $this->Milestone->read();

				$this->Milestone->Project->id = $milestone['Milestone']['project_id'];

				$partOfProject = $this->Milestone->Project->hasRead($this->Auth->user('id'));
				$publicProject	= $this->Milestone->Project->field('public');
				$isAdmin = ($this->_apiAuthLevel() == 1);

				if ($publicProject || $isAdmin || $partOfProject) {
					$milestone['Milestone']['tasks'] = array_values($this->Milestone->Task->find('list', array('conditions' => array('milestone_id' => $id))));

					$data = $milestone['Milestone'];
				} else {
					$data['error'] = 401;
					$data['message'] = 'Milestone found, but is not public.';
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

		$this->Milestone->recursive = -1;
		$data = array();

		switch ($this->_apiAuthLevel()) {
			case 1:
				foreach ($this->Milestone->find("all") as $milestone) {
					$milestone['Milestone']['tasks'] = array_values($this->Milestone->Task->find('list', array('conditions' => array('milestone_id' => $milestone['Milestone']['id']))));

					$data[] = $milestone['Milestone'];
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
}
