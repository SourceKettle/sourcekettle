<?php
/**
 *
 * Dashboards Controller for the SourceKettle system
 * Provides methods for Dashboards to interact with their database object.
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
App::uses('AppController', 'Controller');

class DashboardController extends AppController {

	public $uses = array('Project', 'Task', 'ProjectHistory');

	public $helpers = array('Time', 'Task');

	// Dashboard - any user can see all actions
	public function isAuthorized($user) {
		if (isset($user['id'])) {
			return true;
		}
		return false;
	}

	public function index() {
		$current_user = $this->viewVars['current_user'];
  		$this->set('pageTitle', __("Dashboard"));
		$this->set('subTitle',  __("welcome %s", strtolower($current_user['name'])));
		$this->set('projects', $this->__getRecentProjects());
		$this->set('history', $this->__getProjectsHistory());
		$this->set('tasks', $this->__getUserTasks());
	}

	private function __getRecentProjects() {
		$current_user = $this->viewVars['current_user'];

		return $this->Project->Collaborator->find(
			'all', array(
			'conditions' => array('Collaborator.user_id' => $current_user['id']),
			'recursive' => 0,
			'order' => array('Project.modified DESC'),
			'limit' => 3
			)
		);
	}

	private function __getUserTasks() {
		$current_user = $this->viewVars['current_user'];
		$_tasks = $this->Task->find('all', array(
			'conditions' => array(
				'Task.assignee_id' => $current_user['id'],
				'TaskStatus.name <>' => array('closed', 'resolved', 'dropped')
			),
			'recursive' => 0,
			'order' => array('task_priority_id DESC', 'task_status_id ASC'),
			'limit' => 7
		));
		// Mark individual tasks that the current user can modify
		$tasks = array();
		foreach ($_tasks as $task) {
			$task['__hasWrite'] = $this->Task->hasWrite($this->Auth->user('id'), $task);
			$tasks[] = $task;
		}
		return $tasks;
	}

	private function __getProjectsHistory() {
		$current_user = $this->viewVars['current_user'];
		return $this->ProjectHistory->fetchHistory(null, 15, 0, $current_user['id']);
	}

	public function admin_index() {
		return $this->redirect(array('controller' => 'dashboard', 'action' => 'index', 'admin' => false)); // redirect to user dashboard until admin dashboard is created
	}

}
