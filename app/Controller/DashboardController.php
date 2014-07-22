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

	public function index() {
		$this->set('projects', $this->__getRecentProjects());
		$this->set('tasks', $this->__getUserTasks());
		$this->set('history', $this->__getProjectsHistory());
	}

	private function __getRecentProjects() {
		$this->Project->Collaborator->recursive = 0;
		$current_user = $this->viewVars['current_user'];

		return $this->Project->Collaborator->find(
			'all', array(
			'conditions' => array('Collaborator.user_id' => $current_user['id']),
			'order' => array('Project.modified DESC'),
			'limit' => 3
			)
		);
	}

	private function __getUserTasks() {
		$current_user = $this->viewVars['current_user'];
		// TODO hard coded statuses
		return $this->Task->find('all', array(
			'conditions' => array(
				'Task.assignee_id' => $current_user['id'],
				'Task.task_status_id <>' => '4'
			),
			'recursive' => 3,
			'order' => array('task_priority_id DESC', 'task_status_id ASC'),
			'limit' => 7
		));
	}

	private function __getProjectsHistory() {
		$current_user = $this->viewVars['current_user'];
		return $this->ProjectHistory->fetchHistory(null, 15, 0, $current_user['id']);
	}

	public function admin_index() {
		$this->redirect(array('controller' => 'dashboard', 'action' => 'index', 'admin' => false)); // redirect to user dashboard until admin dashboard is created
	}

}
