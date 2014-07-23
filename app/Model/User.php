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

	public function afterFind($results, $primary = false) {

		// Do we only have the model fields instead of User => array()?
		// NB from the docs it sounds like this should match up with !$primary, but it doesn't...
		$fields_only = (!isset($results[0]) || !is_array($results[0]));

		if ($fields_only) {
			if (isset($results['password']) && !empty($results['password'])) {
				$results['__is_internal'] = true;
			}
			// TODO this should be tidied
			if ($this->_is_api) {
				// A list of things that should not be available in the API
				unset($results['password']);
			}
			return $results;
		}

		foreach ($results as $x => $item) {
			// Check whether it's an internal account or one managed by e.g. LDAP
			if (isset($item['User']['password']) && !empty($item['User']['password'])) {
				$results[$x]['User']['__is_internal'] = true;
			} else {
				$results[$x]['User']['__is_internal'] = false;
			}

			// TODO this should be tidied
			if ($this->_is_api) {
				// A list of things that should not be available in the API
				unset($results[$x]['User']['password']);
			}
		}

		return $results;
	}

	public function beforeSave($options = array()) {
		// Can't update the email or password field if it's an externally-managed account
		if (!User::isSourcekettleManaged($this->data, @$this->id)) {
			$wl = $this->whitelist;
			if (empty($wl)) {
				$wl = array_keys($this->schema());
			}
			$wl = array_diff($wl, array('password'));

			// Only blacklist the email if we're updating an existing account
			if ($this->id) {
				$wl = array_diff($wl, array('email'));
			}
			$this->whitelist = $wl;

		} elseif (isset($this->data[$this->alias]['email'])) {

			// Lowercase the email address for storage (internal accounts only)
			$this->data[$this->alias]['email'] = strtolower($this->data[$this->alias]['email']);
		}

		if ( isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password'], null, true);
		}
		return true;
	}

	public function beforeDelete($cascade = true) {
		// Check that the user account is SourceKettle-managed, can't delete otherwise...
		if (!User::isSourcekettleManaged($this->data, @$this->id)) {
			return false;
		}

		// Check to ensure that this user is not the only admin on multi-collaborator projects
		$projects = $this->Collaborator->find('list', array('fields' => array('Collaborator.project_id'), 'conditions' => array('Collaborator.user_id' => $this->id)));
		foreach ($projects as $row => $projectId) {
			$admins = $this->Collaborator->find('count', array('conditions' => array('Collaborator.project_id' => $projectId, 'Collaborator.access_level' => '2', 'Collaborator.user_id <>' => $this->id)));
			if ( $admins == 0 ) {
				$users = $this->Collaborator->find('count', array('conditions' => array('Collaborator.project_id' => $projectId, 'Collaborator.access_level <>' => '2', 'Collaborator.user_id <>' => $this->id)));
				if ( $users > 0 ) {
					return false;
				}
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
 * isSourcekettleManaged function.
 * Is the user a SourceKettle-managed account, i.e. password is stored in the database?
 * If it's been auto-created from e.g. LDAP, the password will be blank.
 */
	public static function isSourcekettleManaged($data, $id = 0) {

		// Attempt to find an existing user by ID or email
		$user = ClassRegistry::init('User');
		if (isset($id) && $id > 0) {
			$found = $user->findById($id);
		} elseif (isset($data['User']['id'])) {
			$found = $user->findById($data['User']['id']);
		} elseif (isset($data['User']['email'])) {
			$found = $user->findByEmail($data['User']['email']);

		}
		// No existing user found - we must be saving a new one,
		// so simply check the password field exists
		if (!isset($found) || count($found) < 1) {
			return ( isset($data['User']['password']) && !empty($data['User']['password']) );
		}

		// Check the existing object's password field
		return ( isset($found['User']['password']) && !empty($found['User']['password']) );

	}

/**
 * findByEmail function.
 * Find a user by an email address
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

}
