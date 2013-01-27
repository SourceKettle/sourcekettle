<?php
/**
 *
 * AppProjectController for the DevTrack system
 * The application wide controller for sub-components of a Project.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Modifications: DevTrack Development Team 2012
 * @link		http://github.com/SourceKettle/devtrack
 * @package		DevTrack.Controller
 * @since		DevTrack v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');

class AppProjectController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();

		// Redirect urls that use the id of a project to the name of the project
		if (isset($this->request->params['project']) && is_numeric($this->request->params['project'])) {
			$this->loadModel('Project');
			$project = $this->Project->findById($this->request->params['project']);
			if (isset($project) && !empty($project)) {
				$this->redirect(array(
					'controller'	=> $this->request->params['controller'],
					'action'		=> $this->request->params['action'],
					'project'		=> $project['Project']['name']
				));
			}
		}
	}

	/*
	 * _projectCheck
	 * Space saver to ensure user can view content
	 * Also sets commonly needed variables related to the project
	 *
	 * @access protected
	 * @param mixed $name
	 * @param bool $needWrite (default: false)
	 * @param bool $needAdmin (default: false)
	 * @return void
	 */

	protected function _projectCheck($name, $needWrite = false, $needAdmin = false) {
		if ( $this->modelClass == "Project" ) {
			$model = $this->Project;
		} else {
			$model = $this->{$this->modelClass}->Project;
		}

		$project = $model->getProject($name);

		if (empty($project)) {
			throw new NotFoundException(__('Invalid project'));
		}

		$model->id = $project['Project']['id'];

		$this->set('project', $project);
		$this->set('isAdmin', $model->isAdmin());

		$this->set('previousPage', $this->referer(array('action' => 'index', 'project' => $project['Project']['name']), true));

		// Site admins may have access to any project
		if ($this->Auth->user('is_admin') == 1) {
			return $project;
		}

		// Lock out those who aren't allowed to write
		if ($needWrite && !$model->hasWrite($model->_auth_user_id) ) {
			throw new ForbiddenException(__('You do not have permissions to write to this project'));
		}

		// Lock out those who aren't admins
		if ($needAdmin && !$model->isAdmin($model->_auth_user_id) ) {
			throw new ForbiddenException(__('You need to be an admin to access this page'));
		}

		return $project;
	}

}
