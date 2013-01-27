<?php
/**
 *
 * Dashboards Controller for the DevTrack system
 * Provides methods for Dashboards to interact with their database object.
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

		return $this->Project->Collaborator->find(
			'all', array(
			'conditions' => array('Collaborator.user_id' => $this->Project->_auth_user_id),
			'order' => array('Project.modified DESC'),
			'limit' => 5
			)
		);
	}

	private function __getUserTasks() {
		return $this->Task->find('all', array(
			'conditions' => array(
				'Task.assignee_id' => $this->Task->_auth_user_id,
				'Task.task_status_id <>' => '4'
			),
			'recursive' => 3,
			'order' => array('task_priority_id DESC', 'task_status_id ASC'),
			'limit' => 10
		));
	}

	private function __getProjectsHistory() {
		return $this->ProjectHistory->fetchHistory(null, 30, 0, $this->Project->_auth_user_id);
	}

	public function admin_index() {
		$this->redirect(array('controller' => 'dashboard', 'action' => 'index', 'admin' => false)); // redirect to user dashboard until admin dashboard is created
	}

}
