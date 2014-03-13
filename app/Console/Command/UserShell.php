<?php


/**
 * UserShell - admin commands to add/remove user accounts on the command line,
 * and to set/unset admin priveleges etc.
 *
 * @author amn@ecs.soton.ac.uk
 */
class UserShell extends AppShell {

	public $uses = array('User', 'SshKey', 'Project', 'Collaborator', 'Security');

	public $minPasswordLength = 8;

	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->addSubCommand('add', array(
			'help' => __('Add a user account to the system, bypassing the registration process'),
			'parser' => array(
				'description' => array(
					__("Use this command to add a new user account to the system."),
					__("This bypasses the usual registration mechanism and allows"),
					__("you to directly add an account and set its password."),
				),
				'arguments' => array(
					'email' => array(
						'help' => __("The user's email address"),
						'required' => true,
					),
					'name' => array(
						'help'  => __("The full name of the user"),
						'required' => true,
					),
				),
				'options' => array(
					'is_admin' => array(
						'short' => 'a',
						'help' => __("The user account should be created as a system administrator"),
						'boolean'     => true,
						'default'     => false,
					),
					'password' => array(
						'short' => 'p',
						'help' => __("The initial password for the account"),
						'required' => false,
					)
				)
			)
		));

		$parser->addSubCommand('disable', array(
			'help' => __('Disable a user account, so they cannot log in'),
			'parser' => array(
				'description' => array(
					__("Use this command to disable a user account."),
					__("Once the account is disabled, they will be unable to log in."),
				),
				'arguments' => array(
					'email' => array(
						'email' => __("The user's email address"),
						'required' => true,
					),
				)
			)
		));

		$parser->addSubCommand('enable', array(
			'help' => __('Enable a user account, so they can log in again'),
			'parser' => array(
				'description' => array(
					__("Use this command to enable a user account."),
					__("Until the account is enabled, they will be unable to log in."),
				),
				'arguments' => array(
					'email' => array(
						'email' => __("The user's email address"),
						'required' => true,
					),
				)
			)
		));

		$parser->addSubCommand('promote', array(
			'help' => __('Promote a user account to administrator status'),
			'parser' => array(
				'description' => array(
					__("Use this command to make a user a system admin."),
				),
				'arguments' => array(
					'email' => array(
						'email' => __("The user's email address"),
						'required' => true,
					),
				)
			)
		));

		$parser->addSubCommand('demote', array(
			'help' => __('Demote a system administrator account to normal user status'),
			'parser' => array(
				'description' => array(
					__("Use this command to make a system admin a normal user."),
				),
				'arguments' => array(
					'email' => array(
						'email' => __("The user's email address"),
						'required' => true,
					),
				)
			)
		));

		return $parser;
	}

/**
 * Adds a user account
 */
	public function add() {
		// TODO this is not set by default as we do not go near the AppController :-/
		Security::setHash('sha256');

		$this->out("Adding a user account");

		$email = $this->args[0];
		$name  = $this->args[1];

		// Find existing user account
		$found = $this->User->find('first', array(
			'conditions' => array(
				'email' => $email
			)
		));

		// If it exists, error out
		if (!empty($found)) {
			$this->error(__("Found an existing user with that email address!"));
		}

		$password = isset($this->params['password'])? $this->params['password']: null;

		if (strlen($password) < $this->minPasswordLength) {
			$this->out(__("Password must be at least $this->minPasswordLength characters long!"));
			$password = null;
		}

		// If we have no password, ask for one and confirm it
		while (empty($password)) {
			$password = $this->in(__('Enter a password:>'));
			$confirmPassword = $this->in(__('Confirm password:>'));

			if ($password != $confirmPassword) {
				$this->out(__("Error: passwords don't match!"));
				$password = null;

			} elseif (strlen($password) < $this->minPasswordLength) {
				$this->out(__("Password must be at least $this->minPasswordLength characters long!"));
				$password = null;
			}
		}

		// No existing account, create one
		$this->out(__("Creating a new account..."));

		$this->User->create(array( 'User' => array(
			'name'      => $name,
			'email'     => $email,
			'is_admin'  => $this->params['is_admin'],
			'password'  => $password,
			'deleted'   => 0,
			'is_active' => 1,
		)));
		$ok = $this->User->save();

		if (!$ok) {
			$this->error(__("Failed to create user '$email'!"));
		} else {
			$this->out(__("User '$email' created."));
		}
	}

/**
 * Disables a user account, found by email address
 */
	public function disable() {
		$this->out("Disabling a user account");
		$email = $this->args[0];

		// Find existing user account
		$found = $this->User->find('first', array(
			'conditions' => array(
				'email' => $email
			)
		));

		// If it does not exist, error out
		if (empty($found)) {
			$this->error(__("Could not find an account with that email address!"));
		}

		// Save with updated status
		$this->User->id = $found['User']['id'];

		if (!$this->User->saveField('is_active', 0)) {
			$this->error(__("Failed to disable user '$email'!"));
		} else {
			$this->out(__("User '$email' disabled."));
		}
	}

/**
 * Enables a user account, found by email address
 */
	public function enable() {
		$this->out("Enabling a user account");
		$email = $this->args[0];

		// Find existing user account
		$found = $this->User->find('first', array(
			'conditions' => array(
				'email' => $email
			)
		));

		// If it does not exist, error out
		if (empty($found)) {
			$this->error(__("Could not find an account with that email address!"));
		}

		// Save with updated status
		$this->User->id = $found['User']['id'];

		if (!$this->User->saveField('is_active', 1)) {
			$this->error(__("Failed to enable user '$email'!"));
		} else {
			$this->out(__("User '$email' enabled."));
		}
	}

/**
 * Promotes a normal user to sysadmin status
 */
	public function promote() {
		$this->out("Promoting a normal user account to sysadmin");
		$email = $this->args[0];

		// Find existing user account
		$found = $this->User->find('first', array(
			'conditions' => array(
				'email' => $email
			)
		));

		// If it does not exist, error out
		if (empty($found)) {
			$this->error(__("Could not find an account with that email address!"));
		}

		// Save with updated status
		$this->User->id = $found['User']['id'];

		if (!$this->User->saveField('is_admin', 1)) {
			$this->error(__("Failed to promote user '$email'!"));
		} else {
			$this->out(__("User '$email' promoted to system administrator."));
		}
	}

/**
 * Demotes a sysadmin to normal user status
 */
	public function demote() {
		$this->out("Demoting a sysadmin to a normal user account");
		$email = $this->args[0];

		// Find existing user account
		$found = $this->User->find('first', array(
			'conditions' => array(
				'email' => $email
			)
		));

		// If it does not exist, error out
		if (empty($found)) {
			$this->error(__("Could not find an account with that email address!"));
		}

		// Save with updated status
		$this->User->id = $found['User']['id'];

		if (!$this->User->saveField('is_admin', 0)) {
			$this->error(__("Failed to demote user '$email'!"));
		} else {
			$this->out(__("User '$email' demoted to normal user."));
		}
	}

}

