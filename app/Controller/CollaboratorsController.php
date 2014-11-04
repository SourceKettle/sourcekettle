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
			'add'   => 'admin',
			'makeadmin'   => 'admin',
			'makeuser'    => 'admin',
			'makeguest'   => 'admin',
			'delete' => 'admin',
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
 * add method
 *
 * @param string $name project name
 * @return void
 */
	public function add($project = null) {
		$project = $this->_getProject($project);
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}

		// Check for existant user
		$this->Collaborator->User->recursive = -1;

		// Redirect if they supplied no data
		if (empty($this->data) || !isset($this->request->data['Collaborator'])) {
			return $this->redirect(array('project' => $project['Project']['name'], 'action' => '*'));
		}

		// TODO don't attempt to regex for email addresses, we should pass in an ID or an exact email address instead
		$_email = $this->Useful->extractEmail($this->request->data['Collaborator']['name']);
		$user = $this->Collaborator->User->findByEmail($_email, array('User.id', 'User.name'));
		if (empty($user)) {
			$this->Flash->error('The user specified does not exist. Please try again.');
			return $this->redirect(array('project' => $project['Project']['name'], 'action' => '*'));
		}

		// Check for existing association with this project
		if ($this->Collaborator->findByUserIdAndProjectId($user['User']['id'], $project['Project']['id'], array('id'))) {
			$this->Flash->error('The user specified is already collaborating in this project.');
			return $this->redirect(array('project' => $project['Project']['name'], 'action' => '.'));
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
			$this->Flash->error($user['User']['name'] . ' could not be added to the project. Please try again.');
		}

		return $this->redirect(array('project' => $project['Project']['name'], 'action' => '.'));
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
		debug("Making $id admin on $project: logged in as ".$this->Auth->user('id'));
		$this->__changePermissionLevel($project, $id, 2);
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
				return $this->redirect(array('project' => $project['Project']['name'], 'action' => '*'));
			}
		}
		// Save the changes to the user
		if ($this->Collaborator->set('access_level', $newaccesslevel) && $this->Collaborator->save()) {
			$this->Flash->info("Permissions level successfully changed for '" . $collaborator['User']['name'] . "'");
		} else {
			$this->Flash->error("Permissions level for '" . $collaborator['User']['name'] . "' could not be updated. Please try again.");
		}

		return $this->redirect(array('project' => $project['Project']['name'], 'action' => '*'));
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
			$this->Flash->errorReason("There must be at least one admin in the project");
			return $this->redirect(array('project' => $project['Project']['name'], 'action' => '*'));
		}

		// If the user has confimed deletion
		if ($this->request->is('post') && $this->Flash->d($this->Collaborator->delete())) {
			return $this->redirect(array('project' => $project['Project']['name'], 'action' => '*'));
		}

		$this->set('object', array(
			'name' => $collaborator['User']['name'],
			'id'	=> $collaborator['Collaborator']['id']
		));
		$this->set('objects', $this->Collaborator->preDelete());
		$this->render('/Elements/Project/delete');
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
