<?php

/**
 *
 * CollaboratorsController Controller for the SourceKettle system
 * Provides the hard-graft control of the colaborators contained within the system
 * Including CRUD, admin CRUD and API control
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

class CollaboratorsController extends AppProjectController {

/**
 * Project helpers
 * @var type
 */
	public $helpers = array('Time');

	// Which actions need which authorization levels (read-access, write-access, admin-access)
	protected function _getAuthorizationMapping() {
		return array(
			'index'  => 'read',
			'all'   => 'read',
			'add'   => 'write',
			'makeadmin'   => 'admin',
			'makeuser'   => 'admin',
			'makeguest'   => 'admin',
			'delete' => 'write',
			'api_autocomplete' => 'read',
		);
	}
/**
 * index method
 *
 * @param string $project project name
 * @return void
 */
	public function index ($project = null) {
		$project = $this->_getProject($project);

		$collaborators = array();
		foreach (array(0, 1, 2) as $x) {
			$collaborators[$x] = $this->Collaborator->find(
				'all',
				array(
					'conditions' => array(
						'access_level' => $x,
						'project_id' => $project['Project']['id']
					)
				)
			);
		}

		$this->set('collaborators', $collaborators);
	}

/**
 * List the collaborators on the project
 */
	public function all ($project = null) {
		$project = $this->_getProject($project);

		$this->Collaborator->recursive = 0;

		$collaborators = $this->Collaborator->find('all', array(
			'conditions' => array(
				'project_id' => $project['Project']['id']
			),
			'order' => 'access_level DESC'
		));

		$this->set('collaborators', $collaborators);
	}

/**
 * add method
 *
 * @param string $name project name
 * @return void
 */
	public function add($project = null) {
		$project = $this->_getProject($project);
		$this->__add($project, $this->request->data, array('project' => $project['Project']['name'], 'action' => '.'));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		$project = $this->_getProject($this->request->data['Project']['id']);
		$this->__add($project, $this->request->data, array('controller' => 'projects', 'action' => 'admin_view', $project['Project']['id']));
	}

/**
 * _add function.
 * Alias for admin_add and add
 *
 * @access private
 * @param mixed $project
 * @param mixed $data
 * @param mixed $redirect
 * @throws MethodNotAllowedException
 * @return void
 */
	private function __add($project, $data, $redirect) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}

		// Check for existant user
		$this->Collaborator->User->recursive = -1;
		// TODO don't attempt to regex for email addresses, we should pass in an ID or an exact email address instead
		$_email = $this->Useful->extractEmail($data['Collaborator']['name']);
		$user = $this->Collaborator->User->findByEmail($_email, array('User.id', 'User.name'));
		if (empty($user)) {
			$this->Flash->error('The user specified does not exist. Please, try again.');
			return $this->redirect($redirect);
		}

		// Check for existing association with this project
		if ($this->Collaborator->findByUserIdAndProjectId($user['User']['id'], $project['Project']['id'], array('id'))) {
			$this->Flash->error('The user specified is already collaborating in this project.');
			return $this->redirect($redirect);
		}

		// Create details to attach user to this project
		$this->Collaborator->create();
		$collaborator = array(
			'project_id'	=> $project['Project']['id'],
			'user_id'		=> $user['User']['id'],
			'access_level' => 1
		);

		// Save the new collaborator
		if ($this->Collaborator->save($collaborator)) {
			$this->Flash->info($user['User']['name'] . ' has been added to the project');
		} else {
			$this->Flash->error($user['User']['name'] . ' could not be added to the project. Please, try again.');
		}

		return $this->redirect($redirect);
	}

/**
 * makeadmin
 * Allows users to premote a user to an admin
 *
 * @param string $project project name
 * @param string $id user to change
 * @return void
 */
	public function makeadmin($project = null, $id = null) {
		$this->__changePermissionLevel($project, $id, 2);
	}

/**
 * admin_makeadmin
 * Allows admin to premote a user to an admin of
 * a project
 *
 * @param string $id collaborator to change
 * @return void
 */
	public function admin_makeadmin($id = null) {
		$this->__adminChangePermissionLevel($id, 2);
	}

/**
 * makeuser
 * Allows users to premote a user to a regular user
 *
 * @param string $project project name
 * @param string $id user to change
 * @return void
 */
	public function makeuser($project = null, $id = null) {
		$this->__changePermissionLevel($project, $id, 1);
	}

/**
 * admin_makeuser
 * Allows admin to premote a user to a regular user
 *
 * @param string $id user to change
 * @return void
 */
	public function admin_makeuser($id = null) {
		$this->__adminChangePermissionLevel($id, 1);
	}

/**
 * makeguest
 * Allows users to premote a user to an observer
 *
 * @param string $project project name
 * @param string $id user to change
 * @return void
 */
	public function makeguest($project = null, $id = null) {
		$this->__changePermissionLevel($project, $id, 0);
	}

/**
 * admin_makeguest
 * Allows admin to premote a user to an observer
 *
 * @param string $id user to change
 * @return void
 */
	public function admin_makeguest($id = null) {
		$this->__adminChangePermissionLevel($id, 0);
	}

/**
 * changePermissionLevel
 *
 * @param string $project project name
 * @param string $id user to change
 * @param string $newaccesslevel new access level to assign
 * @return void
 */
	private function __changePermissionLevel($project = null, $id = null, $newaccesslevel = 0) {
		$project = $this->_getProject($project);
		$collaborator = $this->Collaborator->open($id);

		if ($newaccesslevel <= 1 && $collaborator['Collaborator']['access_level'] > 1) {
			// Count the number of admins
			$numAdmins = $this->Collaborator->find ('count', array (
				'fields' => 'Collaborator.id',
				'conditions' => array (
					'project_id' => $project['Project']['id'],
					'access_level >' => '1'
				)
			));

			if ($numAdmins <= 1) {
				$this->Flash->errorReason('There must be at least one admin in the project');
				return $this->redirect(array('project' => $project['Project']['name'], 'action' => '.'));
			}
		}

		// Save the changes to the user
		if ($this->Collaborator->set('access_level', $newaccesslevel) && $this->Collaborator->save()) {
			$this->Flash->info("Permissions level successfully changed for '" . $collaborator['User']['name'] . "'");
		} else {
			$this->Flash->error("Permissions level for '" . $collaborator['User']['name'] . "' not be updated. Please, try again.");
		}

		return $this->redirect(array('project' => $project['Project']['name'], 'action' => '.'));
	}

/**
 * adminChangePermissionLevel
 *
 * @param string $id collaborator to change
 * @param string $newaccesslevel new access level to assign
 * @return void
 */
	private function __adminChangePermissionLevel($id = null, $newaccesslevel = 0) {
		$collaborator = $this->Collaborator->open($id);

		// Save the changes to the user
		if ($this->Collaborator->set('access_level', $newaccesslevel) && $this->Collaborator->save()) {
			$this->Flash->info("Permissions level successfully changed for '" . $collaborator['User']['name'] . "'");
		} else {
			$this->Flash->error("Permissions level for '" . $collaborator['User']['name'] . "' not be updated. Please, try again.");
		}

		return $this->redirect(array('controller' => 'projects', 'action' => 'admin_view', $collaborator['Collaborator']['project_id']));
	}

/**
 * delete method
 *
 * @param string $name project name
 * @param string $id
 * @return void
 */
	public function delete($project = null, $id = null) {
		$project = $this->_getProject($project);
		$collaborator = $this->Collaborator->open($id);

		$this->Flash->setUp();

		// Count the number of admins
		$numAdmins = $this->Collaborator->find('count', array (
			'fields' => 'DISTINCT Collaborator.id',
			'conditions' => array (
				'Collaborator.project_id' => $project['Project']['id'],
				'Collaborator.access_level >' => '1',
				'Collaborator.id !=' => $id
			)
		));

		// If the user cannot delete due to no other admins
		if (!$numAdmins) {
			$this->Flash->errorReason('There must be at least one admin in the project');
			return $this->redirect(array('project' => $project['Project']['name'], 'action' => '.'));
		}

		// If the user has confimed deletion
		if ($this->request->is('post') && $this->Flash->d($this->Collaborator->delete())) {
			return $this->redirect(array('project' => $project['Project']['name'], 'action' => '.'));
		}

		$this->set('object', array(
			'name' => $collaborator['User']['name'],
			'id'	=> $collaborator['Collaborator']['id']
		));
		$this->set('objects', $this->Collaborator->preDelete());
		$this->render('/Elements/Project/delete');
	}

/**
 * admin_delete method
 *
 * @param string $name project name
 * @param string $id
 * @throws MethodNotAllowedException
 * @return void
 */
	public function admin_delete($id = null) {
		$collaborator = $this->Collaborator->open($id);

		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}

		$this->Flash->setUp();
		$this->Flash->d($this->Collaborator->delete());
		return $this->redirect(array('controller' => 'projects', 'action' => 'admin_view', $collaborator['Project']['id']));
	}

	/* ************************************************ *
	 *													*
	 *			API SECTION OF CONTROLLER			 	*
	 *			 CAUTION: PUBLIC FACING					*
	 *													*
	 * ************************************************ */

/**
 * api_autocomplete function.
 *
 * @access public
 * @return void
 */
	public function api_autocomplete() {
		$this->layout = 'ajax';

		$data = array('users' => array());

		if (isset($this->request->query['query'])
			&& $this->request->query['query'] != null
			&& strlen($this->request->query['query']) > 2
			&& isset($this->request['named']['project'])) {

			$query = $this->request->query['query'];
			$users = $this->Collaborator->find(
				"all",
				array(
					'conditions' => array(
						'OR' => array(
							'User.name	LIKE' => $query . '%',
							'User.email LIKE' => $query . '%'
						),
						'Project.id' => $this->request['named']['project']
					),
					'fields' => array(
						'User.name',
						'User.email'
					)
				)
			);
			foreach ($users as $user) {
				$data['users'][] = $user['User']['name'] . " [" . $user['User']['email'] . "]";
			}

		}
		$this->set('data',$data);
		$this->render('/Elements/json');
	}
}
