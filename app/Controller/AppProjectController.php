<?php
/**
 *
 * AppProjectController for the SourceKettle system
 * The application wide controller for sub-components of a Project.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Modifications: SourceKettle Development Team 2012
 * @link		http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.Controller
 * @since		SourceKettle v 0.1
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
				return $this->redirect(array(
					'controller'	=> $this->request->params['controller'],
					'action'		=> $this->request->params['action'],
					'project'		=> $project['Project']['name']
				));
			}
		}

	}

	// Returns a hash of action name => access level required (read, write, admin).
	// Sets some defaults e.g. view/index/edit/delete, override this method to add any
	// non-standard actions.
	protected function _getAuthorizationMapping() {
		return array(
			'index'  => 'login',
			'view'   => 'read',
			'edit'   => 'write',
			'delete' => 'write',
		);
	}

	// Authorisation function - uses the CakePHP auth system, works out if the current
	// user has read/write/admin access to the project. Calls _getAuthorizationMapping()
	// to work out what level is required for the given action.
	public function isAuthorized($user) {

		// System admins can do whatever they want
		if (@$user['is_admin'] == 1) {
			return true;
		}

		// No access to admin functions for non-admin users
		if (preg_match('/^admin_/i', $this->action)) {
			$this->Auth->authError = __('You do not have site admin access to this '.$this->modelClass);
			return false;
		}

		// If we've not explicitly set the authorisation level, user is not authorised
		$mapping = $this->_getAuthorizationMapping();
		if (!array_key_exists($this->action, $mapping)) {
			$this->Auth->authError = __('Authentication level problem while accessing this '.$this->modelClass.', action '.$this->action);
			return false;
		}

		$requiredLevel = $mapping[$this->action];

		// Anyone can access, even when not logged in
		if ($requiredLevel == 'any') {
			return true;
		}

		// If we just need to be logged in, that's easy
		if ($requiredLevel == 'login') {
			return (isset($user) && !empty($user));
		}

		// At this point, we are definitely logged in, and we need to check
		// more granular access levels.



		// Slightly fudgy special-case for things that use a Project directly...
		if ( $this->modelClass == "Project" ) {
			$model = $this->Project;
		} else {
			$model = $this->{$this->modelClass}->Project;
		}

		// Get the actual project instance, if it exists...
		$project = $model->getProject($this->params['project']);

		if (empty($project)) {
			throw new NotFoundException(__('Invalid project'));
		}

		$model->id = $project['Project']['id'];
		$isProjectAdmin = $model->isAdmin($user['id']);
		$hasWrite = $model->hasWrite($user['id']);
		$hasRead = $model->hasRead($user['id']);

		switch ($requiredLevel) {
			case 'read':
				$this->Auth->authError = __('You do not have read access to this '.$this->modelClass);
				return $hasRead;
			case 'write':
				$this->Auth->authError = __('You do not have write access to this '.$this->modelClass);
				return $hasWrite;
			case 'admin':
				$this->Auth->authError = __('You do not have admin access to this '.$this->modelClass);
				return $isProjectAdmin;
		}
	
		return false;
	}

/**
 * _getProject
 * Space saver to return the project we're doing things to
 *
 * @access protected
 * @param mixed $name
 * @throws NotFoundException
 * @return void
 */
	protected function _getProject($name) {

		// Slightly fudgy special-case for things that use a Project directly...
		if ( $this->modelClass == "Project" ) {
			$model = $this->Project;
		} else {
			$model = $this->{$this->modelClass}->Project;
		}

		// Get the actual project instance, if it exists...
		$project = $model->getProject($name);

		if (empty($project)) {
			throw new NotFoundException(__('Invalid project'));
		}

		$model->id = $project['Project']['id'];

		$this->set('project', $project);
		$this->set('isAdmin', $model->isAdmin($this->Auth->user('id')));

		return $project;
	}

}
