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

		$this->loadModel('Project');

		// Redirect urls that use the id of a project to the name of the project
		if (isset($this->request->params['project']) && is_numeric($this->request->params['project'])) {
			$project = $this->Project->findById($this->request->params['project']);
			if (isset($project) && !empty($project)) {
				return $this->redirect(array(
					'controller'	=> $this->request->params['controller'],
					'action'		=> $this->request->params['action'],
					'project'		=> $project['Project']['name']
				));
			}
		}

		// If the user has write access, they can drag and drop tasks, etc.
		// Otherwise we'll disable controls and remove links to things they won't be able to do
		if (isset($this->request->params['project'])) {
			$this->set('sidebar', 'project');
		
			$project = $this->Project->findByName($this->request->params['project']);
			if (isset($project) && $project) {
				$this->set('hasWrite', $this->Project->hasWrite($this->Auth->user('id'), $project['Project']['id']));
			}
		}

		// Admin actions - give the admin sidebar
		if (isset($this->request->params['admin'])) {
			$this->set('sidebar', 'admin');
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

		// Deactivated users do not get access
		// (NB they should not be able to log in anyway, of course!)
		if (@$user['is_active'] != 1) {
			return false;
		}

		// System admins can do whatever they want
		if (@$user['is_admin'] == 1) {
			return true;
		}

		// We're now definitely not a sysadmin, so deny admin actions
		if (preg_match('/^admin_/i', $this->action)) {
			$this->Auth->authError = __('You do not have site admin access to this '.$this->modelClass);
			return false;
		}

		// If we've not explicitly set the authorisation level, user is not authorised.
		// This probably indicates a bug though.
		$mapping = $this->_getAuthorizationMapping();
		if (!array_key_exists($this->action, $mapping)) {
			$this->Auth->authError = __('Authentication level problem while accessing this '.$this->modelClass.', action '.$this->action);
			return false;
		}

		$requiredLevel = $mapping[$this->action];

		// Anyone can access, even when not logged in - e.g. registration page
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

		$project_id = $project['Project']['id'];

		switch ($requiredLevel) {
			case 'read':
				$this->Auth->authError = __('You do not have read access to this '.$this->modelClass);
				return $model->hasRead($user['id'], $project_id);
			case 'write':
				$this->Auth->authError = __('You do not have write access to this '.$this->modelClass);
				return $model->hasWrite($user['id'], $project_id);
			case 'admin':
				$this->Auth->authError = __('You do not have admin access to this '.$this->modelClass);
				return $model->isAdmin($user['id'], $project_id);
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
