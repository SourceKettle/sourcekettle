<?php
/**
 *
 * TimesController Controller for the SourceKettle system
 * Provides the hard-graft control of the time segments
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link			http://github.com/SourceKettle
 * @package		SourceKettle.Controller
 * @since		 SourceKettle v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppProjectController', 'Controller');
App::uses('TimeString', 'Time');

class TimesController extends AppProjectController {

/**
 * helpers for additional rendering support
 */
	public $helpers = array(
		'Time'
	);

	// Which actions need which authorization levels (read-access, write-access, admin-access)
	protected function _getAuthorizationMapping() {
		return array(
			'add'  => 'write',
			'delete'  => 'write',
			'edit'  => 'write',
			'history'  => 'read',
			'index'   => 'read',
			'users'   => 'read',
			'view' => 'read',
		);
	}

/**
 * add
 * allows users to log time
 *
 * @access public
 * @param mixed $project
 * @return void
 */
	public function add($project) {
		$project = $this->_getProject($project);

		if ($this->request->is('ajax')) {
			$this->autoRender = false;
			$this->Time->create();

			$this->request->data['Time']['user_id'] = $this->Auth->user('id');
			$this->request->data['Time']['project_id'] = $project['Project']['id'];

			if ($this->Time->save($this->request->data)) {
				echo '<div class="alert alert-success"><a class="close" data-dismiss="alert">x</a>Time successfully logged.</div>';
			} else {
				echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">x</a>Could not log time to the project. Please try again.</div>';
			}
		} else if ($this->request->is('post')) {
			$this->Time->create();
			$origTime = $this->request->data['Time']['mins'];

			$this->request->data['Time']['user_id'] = $this->Auth->user('id');
			$this->request->data['Time']['project_id'] = $project['Project']['id'];

			if ($this->Flash->c($this->Time->save($this->request->data))) {
				if (@$this->request->data['Time']['task_id']){
					return $this->redirect(array('controller' => 'tasks', 'project' => $project['Project']['name'], 'action' => 'view', $this->request->data['Time']['task_id']));
				} else {
					return $this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
				}
			} else {
				// Show the user what they put in, its just nice
				$this->request->data['Time']['mins'] = $origTime;
			}

		// GET request and they've pre-selected a date
		} else if (isset($this->request->query['date'])) {
			$this->request->data['Time']['date'] = $this->request->query['date'];
		}
		$this->set('tasks', $this->Time->Project->Task->fetchLoggableTasks($this->Auth->user('id')));
	}

/**
 * delete function.
 *
 * @access public
 * @param mixed $project
 * @param mixed $id (default: null)
 * @return void
 */
	public function delete($project, $id = null) {
		$project = $this->_getProject($project);
		$time = $this->Time->open($id, true);

		$this->Flash->setUp();
		$this->Flash->d($this->Time->delete(), $time['Time']['id']);
		return $this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
	}

/**
 * edit function.
 *
 * @access public
 * @param mixed $project
 * @param mixed $id (default: null)
 * @return void
 */
	public function edit($project, $id = null) {
		$project = $this->_getProject($project);
		$time = $this->Time->open($id, true);
		$this->set('time', $time);
		$current_user = $this->viewVars['current_user'];

		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['Time']['user_id'] = $current_user['id'];
			$this->request->data['Time']['project_id'] = $project['Project']['id'];

			if ($this->Flash->u($this->Time->save($this->request->data))) {
				return $this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
			}
		} else {
			$this->request->data = $time;
			$this->request->data['Time']['mins'] = $this->request->data['Time']['minutes']['s'];
			$this->set('tasks', $this->Time->Project->Task->fetchLoggableTasks($this->Auth->user('id')));
		}
	}

/**
 * history
 * list the amount of time logged
 *
 * @access public
 * @param mixed $project (default: null)
 * @param mixed $week (default: null)
 * @return void
 */
	public function history($project = null, $year = null, $week = null) {
		$project = $this->_getProject($project);

		// Validate the Year
		if (($_year = $this->Time->validateYear($year)) != $year) {
			return $this->redirect(array('project' => $project['Project']['name'], 'year' => $_year, 'week' => $week));
		}
		// Validate the week
		if (($_week = $this->Time->validateWeek($week, $year)) != $week) {
			return $this->redirect(array('project' => $project['Project']['name'], 'year' => $_year, 'week' => $_week));
		}

		// Optionally filter by user
		$user = null;
		$userName = null;
		if (isset($this->request->query['user'])) {
			$user = $this->request->query['user'];
			if (!preg_replace('/^\s*(\d+)\s*$/', '$1', $user)) {
				$user = null;
			} else {
				$userObject = $this->Time->User->find('first', array(
					'conditions' => array('id' => $user),
					'fields' => array('User.name')
				));
				if ($userObject) {
					$userName = $userObject['User']['name'];
				}
			}
			$this->set('user', $user);
			$this->set('userName', $userName);
		}

		// Start and end dates
		$startDate = new DateTime(null, new DateTimeZone('UTC'));
		$startDate->setISODate($year, $week, 1);
		$endDate = new DateTime(null, new DateTimeZone('UTC'));
		$endDate->setISODate($year, $week, 7);

		// Fetch summary details for the week
		$weekTimes = $this->Time->fetchWeeklySummary($project['Project']['id'], $year, $week, $user);

		$this->set('weekTimes', $weekTimes);
		$this->set('project', $project);

		$this->set('thisWeek',  $week);
		$this->set('thisYear',  $year);
		$this->set('startDate', $startDate);
		$this->set('endDate',   $endDate);

		if ($week == $this->Time->lastWeekOfYear($year)) {
			$this->set('nextWeek', 1);
			$this->set('nextYear', $year + 1);
		} else {
			$this->set('nextWeek', $week + 1);
			$this->set('nextYear', $year);
		}

		if ($week == 1) {
			$this->set('prevWeek', $this->Time->lastWeekOfYear($year - 1));
			$this->set('prevYear', $year - 1);
		} else {
			$this->set('prevWeek', $week - 1);
			$this->set('prevYear', $year);
		}

		// List of tasks we can log time against, for modal add dialog
		$this->set('tasks', $this->Time->Project->Task->fetchLoggableTasks($this->Auth->user('id')));

		// Downloadable timesheets
		if (isset($this->request->query['format'])) {
			switch (strtolower(trim($this->request->query['format']))) {
				case 'csv':
					$this->layout = 'ajax';
					$this->RequestHandler->respondAs('text/csv');
					$this->response->header(array(
						'Content-Disposition' =>  'attachment; filename="timesheet.csv"'
					));
					$this->render('/Elements/Time/tempo.csv');
					break;
				case 'json':	
					$this->autoRender = false;
					$this->set('data', $weekTimes);
					$this->render('/Elements/json');

					break;
				// Explicitly specified HTML, or unknown format - just render the page as normal
				case 'html':
				default:
					break;
			}
		}

	}

/**
 * index function.
 *
 * @access public
 * @param mixed $project
 * @return void
 */
	public function index($project) {
		return $this->redirect(array('project' => $project, 'controller' => 'times', 'action' => 'users'));
	}

/**
 * users
 * list the amount of time each user has logged
 *
 * @access public
 * @param mixed $project
 * @return void
 */
	public function users($project) {
		$project = $this->_getProject($project);

		$this->set('total_time', $this->Time->fetchTotalTimeForProject());
		$this->set('users', $this->Time->fetchUserTimesForProject());
	}

/**
 * userlog
 * Show a log for a specific user showing which tasks they have worked on and for how long
 */
	public function userlog($project, $user_id = null) {
		$project = $this->_getProject($project);
		$this->set('times', $this->Time->find('all', array(
			'conditions' => array('user_id' => $user_id, 'Project.id' => $project['Project']['id']),
			'fields' => array('Task.id', 'Task.subject', 'User.id', 'User.name', 'SUM(Time.mins) AS total_mins'),
			'group' => array('Time.task_id'),
		)));
	}

/**
 * view function.
 *
 * @access public
 * @param mixed $project
 * @param mixed $id (default: null)
 * @return void
 */
	public function view($project, $id = null) {
		$project = $this->_getProject($project);
		$time	= $this->Time->open($id);

		$this->set('time', $time);
		$this->set('task', $this->Time->Project->Task->findById($time['Time']['task_id']));
	}
}
