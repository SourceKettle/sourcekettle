<?php
/**
 *
 * User model for the SourceKettle system
 * Represents a user in the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.Model
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppModel', 'Model', 'AuthComponent', 'Controller/Component');

class User extends AppModel {


/**
 * Display field
 */
	public $displayField = 'name';

/**
 * Validation rules
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your name',
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Please enter your email',
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'An account already exists for this email address',
			),
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a password',
			),
			'minlength' => array(
				'rule' => array('minlength', 8),
				'message' => 'Your password must be at least 8 characters',
			),
		),
		'is_admin' => array(
			'boolean' => array(
				'rule' => array('boolean'),
			),
		),
		'is_active' => array(
			'boolean' => array(
				'rule' => array('boolean'),
			),
		),
	);

/**
 * hasMany associations
 */
	public $hasMany = array(
		'Collaborator' => array(
			'className' => 'Collaborator',
			'foreignKey' => 'user_id',
			'dependent' => true,
		),
		'EmailConfirmationKey' => array(
			'className' => 'EmailConfirmationKey',
			'foreignKey' => 'user_id',
			'dependent' => true,
		),
		'SshKey' => array(
			'className' => 'SshKey',
			'foreignKey' => 'user_id',
			'dependent' => true,
		),
		'ApiKey' => array(
			'className' => 'ApiKey',
			'foreignKey' => 'user_id',
			'dependent' => true,
		),
		'LostPasswordKey' => array(
			'className' => 'LostPasswordKey',
			'foreignKey' => 'user_id',
			'dependent' => true,
		)
	);

	public $hasAndBelongsToMany = array(
		'Team' => array(
			'className' => 'Team',
			'joinTable' => 'teams_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'team_id',
			'unique' => 'keepExisting',
		)
	);

	public $actsAs = array(
		'FilterValid' => array(
			'nameField' => 'email',
		),
	);

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		// Get the DB table prefix from our database config, for if
		// we have multiple systems in the same DB or fixtures have a prefix
		$db =& ConnectionManager::getDataSource($this->useDbConfig);
		$table_prefix = $db->config['prefix'];

		$this->virtualFields = array(
			'is_internal' => "(`".$this->alias."`.password IS NOT NULL AND `".$this->alias."`.password != '')"
		);
	}

	public function afterFind($results, $primary = false) {
		// Do we only have the model fields instead of User => array()?
		// NB from the docs it sounds like this should match up with !$primary, but it doesn't...
		$fields_only = (!isset($results[0]) || !is_array($results[0]));

		if ($fields_only) {
			// TODO this should be tidied
			if ($this->_is_api) {
				// A list of things that should not be available in the API
				unset($results['password']);
			}
			return $results;
		}

		foreach ($results as $x => $item) {
			// TODO this should be tidied
			if ($this->_is_api) {
				// A list of things that should not be available in the API
				unset($results[$x]['User']['password']);
			}
		}

		return $results;
	}

	public function beforeSave($options = array()) {
		// This is not a brand-new account (we have an ID):
		// do some checks first to make sure we don't change things we shouldn't
		if (isset($this->id) && $this->id) {

			// Load the existing data and work out if it's internal or not
			$current_details = $this->findById($this->id);

			if (!$current_details[$this->alias]['is_internal']) {

				if (isset($this->data[$this->alias]['email'])) {
					unset($this->data[$this->alias]['email']);
				}
				if (isset($this->data[$this->alias]['password'])) {
					unset($this->data[$this->alias]['password']);
				}
			}

		} elseif (isset($this->data[$this->alias]['email'])) {

			// Lowercase the email address for storage (internal accounts only)
			$this->data[$this->alias]['email'] = strtolower($this->data[$this->alias]['email']);
		}

		// Hash the password before saving
		if ( isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password'], null, true);
		}
		return true;
	}

	public function beforeDelete($cascade = true) {

		// Load the existing data and work out if it's internal or not
		$current_details = $this->findById($this->id);

		if (!$current_details[$this->alias]['is_internal']) {
			return false;
		}

		// Check to ensure that this user is not the only admin on multi-collaborator projects
		$projects = $this->Collaborator->find('list', array(
			'fields' => array('Collaborator.project_id'),
			'conditions' => array('Collaborator.user_id' => $this->id)));

		foreach ($projects as $row => $projectId) {
			$admins = $this->Collaborator->find('count', array('conditions' => array(
				'Collaborator.project_id' => $projectId,
				'Collaborator.access_level' => '2',
				'Collaborator.user_id !=' => $this->id)));

			$users = $this->Collaborator->find('count', array('conditions' => array(
				'Collaborator.project_id' => $projectId,
				'Collaborator.access_level !=' => '2',
				'Collaborator.user_id !=' => $this->id)));
			// If this user is the only admin and there are other collaborators, do not delete
			if ( $admins == 0 && $users > 0) {
				return false;
			}
		}

		// Delete all the projects that the user is the only collaborator on
		foreach ($projects as $row => $projectId) {
			$users = $this->Collaborator->find('count', array('conditions' => array('Collaborator.project_id' => $projectId)));
			if ( $users == 1 ) {
				$this->Collaborator->Project->delete($projectId);
				$this->log("[UsersModel.beforeDelete] project[" . $projectId . "] deleted as user[" . $this->id . "] is being deleted", 'sourcekettle');
			}
		}
		return true;
	}

/**
 * findByEmail function.
 * Find a user by an email address, lowercasing it automatically
 *
 * @param mixed $email the email to search
 * @param mixed $fields (default: null)
 * @param mixed $order (default: null)
 */
	public function findByEmail($email, $fields = null, $order = null) {
		return $this->find('first', array(
			'conditions' => array(
				'LOWER(email)' => strtolower($email),
			),
			'fields' => $fields,
			'order' => $order
		));
	}

	// Gets a list of all pending account approvals i.e. confirmation keys
	// that have not yet been confirmed by the user
	public function getPendingApprovals() {
		$userList = $this->EmailConfirmationKey->find('all', array(
			'conditions' => array(
				'User.is_active' => false,
			),
			'fields' => array(
				'EmailConfirmationKey.key',
				'User.id',
				'User.name',
				'User.email',
				'User.is_active',
			),
			'recursive' => 1,
		));
		return $userList;
	}

	// Gets a single pending account given an email confirmation key
	public function getPendingAccount($key) {
		$user = $this->EmailConfirmationKey->find('first', array(
			'conditions' => array(
				'EmailConfirmationKey.key' => $key,
				'User.is_active' => false,
			),
			'fields' => array(
				'EmailConfirmationKey.key',
				'User.id',
				'User.name',
				'User.email',
				'User.is_active',
			),
			'recursive' => 1,
		));
		return $user;
	}

	public function approvePendingAccount($user) {

		if (empty($user) || !isset($user['User']) || !isset($user['User']['id']) || @$user['User']['is_active']) {
			return false;
		}
		
		$ok = $this->save(array('User' => array(
			'id' => $user['User']['id'],
			'is_active' => true,
		)));

		if (!$ok) {
			return false;
		}

		$this->EmailConfirmationKey->deleteAll(array('user_id' => $user['User']['id']));
		return true;
	}

}
