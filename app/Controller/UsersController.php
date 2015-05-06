<?php

/**
 *
 * Users Controller for the SourceKettle system
 * Provides methods for users to interact with their database object. Contains methods
 * for them to register, update their details and delete their account.
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

class UsersController extends AppController {

	public $helpers = array('Time');

	public $components = array('Email', 'Paginator');

	public $uses = array('User', 'Setting', 'Project');

	public function isAuthorized($user) {

		// Must be logged in and active
		if (!isset($user) || empty($user) || !$user['is_active']) {
			return false;
		}

		// Sysadmins only for admin actions
		if (preg_match('/^admin_/', $this->action) && !$user['is_admin']) {
			return false;
		}

		// Anything else you just need to be logged in for
		return true;
	}

	public function beforeFilter() {
		parent::beforeFilter();
		$this->set('sidebar', 'users');

		// Registration and other "setting your password" actions cannot
		// require a login
		$this->Auth->allow(
			'add',
			'register',
			'activate',
			'lost_password',
			'reset_password'
		);
	}

/**
 * Function to allow users to register with the application
 */
	public function register() {
		$this->set('pageTitle', __('Register with %s', $this->sourcekettle_config['UserInterface']['alias']['value']));
		$this->set('subTitle', __('Hello! Bonjour! Willkommen!..'));
		$ok = true;

		// Registration is disabled, render a message instead
		if (!$this->sourcekettle_config['Users']['register_enabled']['value']) {
			$this->render('registration_disabled');
			return;
		}

		// Render form
		if (!$this->request->is('post')) {
			$this->render('register');
			return;
		}

		$data = $this->_cleanPost(array("User.name", "User.email", "User.password", "User.password_confirm", "User.ssh_key"));
		$data['User']['is_admin'] = false;

		// At this point, we've got a form submission, so process it...
		// Check passwords match
		if ($data['User']['password'] != $data['User']['password_confirm']) {
			$this->Session->setFlash(__("<h4 class='alert-heading'>Error</h4>The passwords do not match. Please try again."), 'default', array(), 'error');
			$this->render('register');
			return;
		}

		// Check for a particularly silly password...
		if (strtolower($data['User']['password']) == 'password') {
			$this->Session->setFlash(__("<h4 class='alert-heading'>Oh Dear...</h4>I see what you did there. 'password' is not a good password. Be more original!"), 'default', array(), 'error');
			$this->render('register');
			return;
		}

		// Attempt to save the new user account
		$this->User->create();
		if (!$this->User->save($data)) {
			$this->Session->setFlash(__("<h4 class='alert-heading'>Error</h4>One or more fields were not filled in correctly. Please try again."), 'default', array(), 'error');
			$this->render('register');
			return;
		}

		$id = $this->User->getLastInsertID();
		$this->log("[UsersController.register] user[${id}] created", 'sourcekettle');

		// Check to see if an SSH key was added and save it
		if (!empty($data['User']['ssh_key'])) {
			$this->User->SshKey->create();
			$sshdata = array('SshKey');
			$sshdata['SshKey']['user_id'] = $id;
			$sshdata['SshKey']['key'] = $data['User']['ssh_key'];
			$sshdata['SshKey']['comment'] = 'Default key';
			$this->User->SshKey->save($sshdata);

			// Update the sync required flag
			$this->Setting->syncRequired();

			$this->log("[UsersController.register] sshkey[" . $this->User->SshKey->getLastInsertID() . "] added to user[${id}]", 'sourcekettle');
		}

		// Now to create the key and send the email
		$this->User->EmailConfirmationKey->save(
			array('EmailConfirmationKey' => array(
				'user_id' => $id,
				'key' => $this->__generateKey(20),
			))
		);

		$this->__sendNewUserMail($id);
		$this->render('email_sent');
	}

/**
 * Used to activate user accounts
 * @param type $key The key used to activate an account
 */
	public function activate($key = null) {
		if ($key == null) {
			$this->Session->setFlash("The key given was not a valid activation key.", 'default', array(), 'error');
			return $this->redirect('/');
		}

		$user = $this->User->getPendingAccount($key);

		if (empty($user)) {
			$this->Session->setFlash(__("<h4 class='alert-heading'>Error</h4>The link given was not valid. Please contact your system administrator to manually activate your account."), 'default', array(), 'error');

		} elseif(!$this->User->approvePendingAccount($user)) {
			$this->Session->setFlash(__("<h4 class='alert-heading'>Error</h4>An error occured, please contact your system administrator."), 'default', array(), 'error');

		} else {
			$this->Session->setFlash(__("<h4 class='alert-heading'>Success</h4>Your account is now activated. You can now login."), 'default', array(), 'success');
			$this->log("[UsersController.activate] user[" . $user['User']['id'] . "] activated", 'sourcekettle');
			return $this->redirect('/login');
		}
		return $this->redirect('/');
	}

/**
 * Function to allow for users to reset their passwords. Will generate a key and an email.
 */
	public function lost_password() {

		$this->set('pageTitle', __('Lost password'));
		$this->set('subTitle', __('correct horse battery staple'));
		// GET request - render the lost password form
		if ($this->request->is('get')) {	
			$this->render('lost_password');
			return;
		}

		// Filter out non POST or GET requests
		if (!$this->request->is('post')) {
			return $this->redirect('/lost_password');
		}

		// POST request - create a key and email the user
		$user = $this->User->findByEmail($this->request->data['User']['email']);
		
		// Don't know them...
		if (empty($user)) {
			$this->Session->setFlash("A problem occurred when resetting your password - if the problem persists you should contact <a href='mailto:".$this->sourcekettle_config['Users']['sysadmin_email']['value']."'>the system administrator</a>.", 'default', array(), 'error');
			return $this->redirect('/login');
		}
		// Check the user account is internally managed by SourceKettle
		if (!@$user['User']['is_internal']) {
			$this->Session->setFlash("It looks like you're using an account that is not managed by " . $this->sourcekettle_config['UserInterface']['alias']['value'] . " - " .
				"unfortunately, we can't help you reset your password. Try talking to " .
				"<a href='mailto:" . $this->sourcekettle_config['Users']['sysadmin_email']['value'] . "'>the system administrator</a>.", 'default', array(), 'error');
			return $this->redirect('/login');
		}
	
		// Now to create the key and send the email
		$this->User->LostPasswordKey->save(
			array('LostPasswordKey' => array(
				'user_id' => $user['User']['id'],
				'key' => $this->__generateKey(25),
			))
		);
	
		if ($this->__sendForgottenPasswordMail($user['User']['id'], $this->User->LostPasswordKey->getLastInsertID())) {
			$this->log("[UsersController.lost_password] lost password email sent to user[" . $user['User']['id'] . "]", 'sourcekettle');
		} else {
			$this->log("[UsersController.lost_password] lost password email could NOT be sent to user[" . $user['User']['id'] . "]", 'sourcekettle');
			$this->Session->setFlash("There was a problem sending the lost password email", 'default', array(), 'error');
			$this->render('lost_password');
			return;
		}
	
		$this->Session->setFlash("An email was sent to the given email address. Please use the link to reset your password.", 'default', array(), 'success');
		return $this->redirect('/login');

	}

	public function reset_password($key = null) {

		$this->set('pageTitle', __('Reset password'));
		$this->set('subTitle', __('hunter2'));

		// No key yet - bounce to the lost_password form
		if ($key == null) {
			return $this->redirect('/lost_password');
		}

		// Invalid key
		$passwordkey = $this->User->LostPasswordKey->findByKey($key);
		if (empty($passwordkey)) {
			$this->Session->setFlash("The key given was invalid", 'default', array(), 'error');
			return $this->redirect('/lost_password');
		}

		// Bomb out if the key has expired
		// TODO hard-coded expiry time, should be in config
		$keyTime = new DateTime($passwordkey['LostPasswordKey']['created'], new DateTimeZone('UTC'));
		$expiryTime = new DateTime('now', new DateTimeZone('UTC'));
		$expiryTime->sub(new DateInterval('PT18000S'));

		if ($keyTime < $expiryTime) {
			$this->User->LostPasswordKey->delete($passwordkey['LostPasswordKey']);
			$this->Session->setFlash("The key given has expired", 'default', array(), 'error');
			return $this->redirect('/lost_password');
		}

		// If we have no new password yet, render the password form
		if ($this->request->is('get') || !isset($this->request->data['User']['password'])) {
			$this->render('reset_password');
			return;
		}

		$data = $this->_cleanPost(array("User.password", "User.password_confirm"));
		// Passwords don't match, re-render password form
		if (@$data['User']['password'] != @$data['User']['password_confirm']) {
			$this->Session->setFlash("Your passwords do not match. Please try again.", 'default', array(), 'error');
			$this->render('reset_password');
			return;
		}

		// Check for a particularly silly password...
		if (strtolower($data['User']['password']) == 'password') {
			$this->Session->setFlash(__("<h4 class='alert-heading'>Oh Dear...</h4>I see what you did there. 'password' is not a good password. Be more original!"), 'default', array(), 'error');
			$this->render('reset_password');
			return;
		}

		// At this point, we have validated everything - the user has supplied
		// a valid reset key and two matching passwords.

		// Save the user object with their newly-chosen password
		$this->User->id = $passwordkey['User']['id'];

		// Save failed...
		if (!$this->User->save($data)) {
			$this->Session->setFlash(__("There was a problem resetting your password. Please try again."), 'default', array(), 'error');
			$this->render("reset_password");
			return;
		}

		// Delete the reset key and redirect to login page
		$this->User->LostPasswordKey->delete($passwordkey['LostPasswordKey']);
		$this->Session->setFlash(__("Your password has been reset. You can now login."), 'default', array(), 'success');
		$this->log("[UsersController.reset_password] password reset for user[" . $passwordkey['User']['id'] . "]", 'sourcekettle');

		return $this->redirect('/login');
	}

/**
 * Allows admins to see all users
 */
	public function admin_index() {
		$this->set('pageTitle', __('Administration'));
		$this->set('subTitle', __('search for persons of interest'));
		// TODO nesting levels of doooom
		if ($this->request->is('post') && isset($this->request->data['User']['name']) && $user = $this->request->data['User']['name']) {
			if (preg_match('/[\[{\(](.+@.+)[\]}\)]/', $user, $matches)) {
				if ($user = $this->User->findByEmail($matches[1])) {
					return $this->redirect(array('action' => 'view', $user['User']['id']));
				} else {
					$this->Flash->error(__('The specified user does not exist. Please try again.'));
				}
				
			}
		}
		$this->User->contain();
		$this->set('users', $this->paginate());
	}

/**
 * Allows users to view their profile
 */
	public function index() {
		return $this->redirect(array ('action' => 'details'));
	}

/**
 * View a user in admin mode
 * @param type $id The id of the user to view
 * @throws NotFoundException
 */
	public function admin_view($id = null) {
		$this->set('pageTitle', __('Administration'));
		$this->set('subTitle', __('single out the stragglers'));
		$this->User->id = $id;

		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		//Find the users projects they are working on
		//$this->set('projects', $this->User->Collaborator->findAllByUser_id($id));
		$this->Paginator->settings = array(
			'conditions' => array('Collaborator.user_id' => $id),
			'joins' => array(
			 	array('table' => 'collaborators',
					'alias' => 'Collaborator',
					'type' => 'INNER',
					'conditions' => array(
						'Collaborator.project_id = Project.id',
					)
				),
			),
			'limit' => 15,
			'group' => array('Project.id'),
			'order' => array('Project.modified DESC'),
		);

		$projects = $this->paginate('Project');
		$this->set('projects', $projects);
		$this->set('model', 'Project'); // For pagination thingy
		$this->request->data = $this->User->read();
		$this->request->data['User']['password'] = null;
	}

	public function admin_approve($key = null) {
		$this->set('pageTitle', __('Administration'));
		$this->set('subTitle', __('thumbs up'));

		if ($this->request->is('get')) {
			$this->set('users', $this->User->getPendingApprovals());
			return $this->render('admin_approve');
		}

		if (!$this->request->is('post') || $key == null) {
			return $this->redirect('/admin/users/approve');
		}

		$user = $this->User->getPendingAccount($key);

		if (empty($user)) {
			$this->Session->setFlash(__("Failed to find a pending account for key '$key'"), 'default', array(), 'error');

		} elseif(!$this->User->approvePendingAccount($user)) {
			$this->Session->setFlash(__("A problem occurred when approving the account. Please try again."), 'default', array(), 'error');

		} else {
			$this->Session->setFlash(__("Account '".@$user['User']['email']."' approved."), 'default', array(), 'success');

		}

		return $this->redirect('/admin/users/approve');
	}

/**
 * Function for viewing a user's public page
 * @param type $id The id of the user to view
 * @throws NotFoundException
 */
	public function view($id = null) {
		$current_user = $this->viewVars['current_user'];
		$this->set('pageTitle', __('Profile: %s', $current_user['name']));
		$this->set('subTitle', __(''));
		$this->User->id = $id;

		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		// Find the users public projects or public projects they are working on
		$this->User->Collaborator->contain('Project');
		$this->set('projects', $this->User->Collaborator->find('all', array('conditions' => array('Collaborator.user_id' => $id, 'Project.public' => true))));
		$this->set('user', $this->User->read(null, $id));
		
		$you	= $current_user['id'];
		$them   = $this->User->id;
		$joinProjects = array();

		// Find projects you both collaborate on
		if ($you != $them) {
			$themProjects = array_values($this->User->Collaborator->find('list', array('conditions' => array('user_id' => $them), 'fields' => array('project_id'))));
			$joinProjects = $this->User->Collaborator->find('all', array('conditions' => array('user_id' => $you, 'project_id' => $themProjects)));
		}
		$this->set('shared_projects', $joinProjects);
		
	}

/**
 * Create a new user
 */
	public function add() {
		return $this->redirect('register');
	}

/**
 * Create a new user
 */
	public function admin_add() {
		$this->set('pageTitle', __('Administration'));
		$this->set('subTitle', __('add a new sheep to your flock'));
		if ($this->request->is('post')) { // if data was posted therefore a submitted form
			$this->User->create();

			$data = $this->_cleanPost(array("User.name", "User.email", "User.is_admin", "User.is_active"));
			// Fudge in a random password to stop it looking like an external account
			$data['User']['password'] = $this->__generateKey(25);
			if ($this->User->save($data)) {
				$id = $this->User->getLastInsertID();
				$this->log("[UsersController.admin_add] user[${id}] created by user[" . $this->Auth->user('id') . "]", 'sourcekettle');

				//Now to create the key and send the email
				$this->User->LostPasswordKey->save(
					array('LostPasswordKey' => array(
						'user_id' => $id,
						'key' => $this->__generateKey(25),
					))
				);
				$this->__sendAdminCreatedUserMail($id, $this->User->LostPasswordKey->getLastInsertID());
				$this->Session->setFlash(__('New User added successfully.'), 'default', array(), 'success');
				$this->log("[UsersController.admin_add] user[" . $id . "] added by user[" . $this->Auth->user('id') . "]", 'sourcekettle');
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash("<h4 class='alert-heading'>".__("Error")."</h4>".__("One or more fields were not filled in correctly. Please try again."), 'default', array(), 'error');
			}
		}
	}

/**
 * Edit the name and the email address of a user
 * @param type $id The id of the user to edit
 * @throws NotFoundException
 */
	public function admin_edit($id = null) {
		$this->User->id = $id;

		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		if ($this->request->is('post')) {
			// Admins may change admin/active status
			$data = $this->_cleanPost(array("User.name", "User.email", "User.is_admin", "User.is_active"));
			$data['User']['id'] = $id;
			if ($this->Flash->u($this->User->save($data))) {
				$this->log("[UsersController.admin_edit] user[" . $this->Auth->user('id') . "] edited details of user[" . $this->User->id . "]", 'sourcekettle');
				return $this->redirect(array('action' => 'index'));
			}
		}
		return $this->redirect(array('controller' => 'users', 'action' => 'admin_view', $this->User->id));
	}

/**
 * Edit the name and the email address of the current user
 */
	public function details() {
		$this->set('pageTitle', __('Edit profile: %s', $this->Auth->user('name')));
		$this->set('subTitle', __(''));
		$this->User->id = $this->Auth->user('id'); //get the current user

		if ($this->request->is('post')) {
			$data = $this->_cleanPost(array("User.name", "User.email"));
			if ($this->User->save($data)) {
				$this->Session->setFlash(__('Your changes have been saved.'), 'default', array(), 'success');
				$this->log("[UsersController.details] user[" . $this->User->id . "] edited details", 'sourcekettle');

				// NB we have to re-read this as we may not have updated the email address
				// (external accounts will throw it away)
				$userData = $this->User->read();
				$this->Session->write('Auth.User.name', $userData['User']['name']);
				$this->Session->write('Auth.User.email', $userData['User']['email']);
				$this->set('user_name', $userData['User']['name']);
			} else {
				$this->Session->setFlash(__('There was a problem saving your changes. Please try again.'), 'default', array(), 'error');
			}
		}

		//update the page
		$user = $this->Auth->user();
		$this->set('user', $user);
		$this->User->id = $user['id'];
		$this->request->data = $this->User->read();
		$this->request->data['User']['password'] = null;
	}

/**
 * Edit the current users password
 */
	public function security() {
		$this->set('pageTitle', __('Change password: %s', $this->Auth->user('name')));
		$this->set('subTitle', __(''));
		$this->User->id = $this->Auth->user('id'); //get the current user
		$user = $this->User->read(null, $this->User->id);
		$user = $user['User'];
		if ($this->request->is('post')) {
			$data = $this->_cleanPost(array("User.password_current", "User.password", "User.password_confirm"));
			if ($user['password'] == $this->Auth->password($this->request->data['User']['password_current'])) { //check their current password
				if ($data['User']['password'] == $data['User']['password_confirm']) { //check passwords match

					if ($this->User->save($data)) {
						$this->Session->setFlash(__('Your changes have been saved.'), 'default', array(), 'success');
						$this->log("[UsersController.security] user[" . $this->Auth->user('id') . "] changed password", 'sourcekettle');
					} else {
						foreach ($this->User->validationErrors as $field => $errors) {
							foreach ($errors as $errorMessage) {
								$this->Session->setFlash($errorMessage, 'default', array(), 'error');
							}
						}
					}
				} else {
					$this->Session->setFlash(__('Your passwords did not match. Please try again.'), 'default', array(), 'error');
				}
			} else {
				$this->Session->setFlash(__('Your current password was incorrect. Please try again.'), 'default', array(), 'error');
			}
		}

		//Update the page details
		$user = $this->Auth->user();
		$this->set('user', $user);
		$this->User->id = $user['id'];
		$this->request->data = $this->User->read();
		$this->request->data['User']['password'] = null;
	}

/**
 * Edit the current users theme
 */
	public function theme() {
		$this->set('pageTitle', __('Change theme: %s', $this->Auth->user('name')));
		$this->set('subTitle', __(''));
		$model = ClassRegistry::init("UserSetting");
		$currentTheme = $model->findByNameAndUserId("UserInterface.theme", $this->Auth->user('id'));
		if ($this->request->is('post')) {

			$data = $this->_cleanPost(array("Setting.UserInterface.theme"));
			$save = array('UserSetting' => array(
				'user_id' => $this->Auth->user('id'),
				'name' => 'UserInterface.theme',
				'value' => (string)$data['Setting']['UserInterface']['theme']
			));

			if (!empty($currentTheme)) {
				$save['UserSetting']['id'] = $currentTheme['UserSetting']['id'];
			}

			if ($model->save($save)) {
				$this->Session->setFlash(__('Your changes have been saved.'), 'default', array(), 'success');
				return $this->redirect(array('action' => 'theme'));
			} else {
				$this->Session->setFlash(__('There was a problem saving your changes. Please try again.'), 'default', array(), 'error');
			}
		}

		if (!empty($currentTheme)) {
			$currentTheme = $currentTheme['UserSetting']['value'];
		} else {
			$currentTheme = 'default';
		}

		$this->request->data['Setting'] = array('UserInterface' => array('theme' => $currentTheme));
		$this->set('username', $this->Auth->user('name'));
	}

/**
 * Function to delete a user
 * Use at your own peril
 *
 * Deletes the current user (the one that is authenticated with the system) and any projects for which there are no other
 * collaborators
 */
	public function delete() {
		$this->set('pageTitle', __('Delete your account'));
		$this->set('subTitle', __('careful now...'));
		// Check whether the user account is SourceKettle-managed (if not it's an LDAP
		// account or similar, so we can't really delete it properly)
		// TODO this is totally wrong and broken!
		$this->User->id = $this->Auth->user('id');
		$this->request->data = $this->User->read();
		$this->set('external_account', false);

		if ($this->request->is('post')) {
			$this->User->id = $this->Auth->user('id');

			//Now delete the user
			if ($this->User->delete($this->Auth->id)) {
				$this->Session->setFlash(__('Account deleted'), 'default', array(), 'success');
				$this->log("[UsersController.delete] user[" . $this->Auth->user('id') . "] deleted", 'sourcekettle');

				//Now log them out of the system
				$this->Auth->logout();
				return $this->redirect('/');
			}
			// TODO check what projects made this fail
			$this->Session->setFlash(__('Account was not deleted'), 'default', array(), 'error');
			return $this->redirect(array('action' => 'delete'));
		} else {
			$user = $this->Auth->user();
			$this->User->id = $user['id'];
			$this->request->data = $this->User->read();
			$this->request->data['User']['password'] = null;
		}
	}

/**
 * Function to delete a user
 * Use at your own peril
 *
 * Deletes the specified user and any projects for which there are no other
 * collaborators - but only if the current user is a system admin.
 */
	public function admin_delete($userId) {

		// Check we're logged in as an admin
		$this->User->id = $this->Auth->user('id');
		$currentUserData = $this->User->read();

		if (!$currentUserData['User']['is_admin']) {
			return $this->redirect('/');
		}

		// Check user ID is numeric...
		$userId = trim($userId);
		if (!is_numeric($userId)) {
			$this->Session->setFlash(__('Could not delete user - bad user ID was given'), 'error', array(), '');
			return $this->redirect(array('action' => 'admin_index'));
		}

		if ($this->request->is('post')) {
			$this->User->id = $userId;
			$targetUserData = $this->User->read();

			$this->set('external_account', false);
			if (!$targetUserData['User']['is_internal']) {
				$this->Session->setFlash(__('Account could not be deleted - it is not managed by SourceKettle'), 'default', array(), 'error');
				return $this->redirect(array('action' => 'admin_index'));
			}

			//Now delete the user
			if ($this->User->delete($this->Auth->id)) {
				$this->Session->setFlash(__('Account deleted'), 'default', array(), 'success');
				$this->log("[UsersController.delete] user[" . $this->Auth->user('id') . "] deleted", 'sourcekettle');
				return $this->redirect(array('action' => 'admin_index'));
			}

			// TODO check what projects made this fail
			$this->Session->setFlash(__('Account was not deleted'), 'default', array(), 'error');
			return $this->redirect(array('action' => 'admin_index'));

		} else {
			// We only respond to POSTs, otherwise bounce to index page
			return $this->redirect(array('action' => 'admin_index'));
		}
	}

	public function admin_promote($userId) {

		if ($this->request->is('post')) {
			$this->User->id = $userId;
			$targetUserData = $this->User->read();

			// Never promote an inactive user account, just in case!
			if (!$targetUserData['User']['is_active']) {
				$this->Session->setFlash(__('Account was not promoted as it is inactive'), 'default', array(), 'error');
				return $this->redirect(array('action' => 'admin_index'));
			}

			// Now promote the user
			$targetUserData['User']['is_admin'] = 1;

			if ($this->User->save($targetUserData, array('fieldList' => array('is_admin')))) {
				$this->Session->setFlash(__('Account promoted to system admin'), 'default', array(), 'success');
				$this->log("[UsersController.promote] user[" . $this->Auth->user('id') . "] promoted to sysadmin", 'sourcekettle');
				return $this->redirect(array('action' => 'admin_index'));
			}

			// TODO check what projects made this fail
			$this->Session->setFlash(__('Account was not promoted'), 'default', array(), 'error');
			return $this->redirect(array('action' => 'admin_index'));

		} else {
			// We only respond to POSTs, otherwise bounce to index page
			return $this->redirect(array('action' => 'admin_index'));
		}
	}

	public function admin_demote($userId) {

		// Safety net: do not allow a sysadmin to demote themself!
		if ($this->Auth->user('id') == $userId) {
			$this->Session->setFlash(__('Cannot demote yourself! Ask another admin to do it'), 'error', array(), '');
			return $this->redirect(array('action' => 'admin_index'));
		}

		// Check user ID is numeric...
		$userId = trim($userId);
		if (!is_numeric($userId)) {
			$this->Session->setFlash(__('Could not demote user - bad user ID was given'), 'error', array(), '');
			return $this->redirect(array('action' => 'admin_index'));
		}

		if ($this->request->is('post')) {
			$this->User->id = $userId;
			$targetUserData = $this->User->read();


			// Now demote the user
			$targetUserData['User']['is_admin'] = 0;

			if ($this->User->save($targetUserData, array('fieldList' => array('is_admin')))) {
				$this->Session->setFlash(__('Account demoted to normal user'), 'default', array(), 'success');
				$this->log("[UsersController.demote] user[" . $this->Auth->user('id') . "] demoted to sysadmin", 'sourcekettle');
				return $this->redirect(array('action' => 'admin_index'));
			}

			// TODO check what projects made this fail
			$this->Session->setFlash(__('Account was not demoted'), 'default', array(), 'error');
			return $this->redirect(array('action' => 'admin_index'));

		} else {
			// We only respond to POSTs, otherwise bounce to index page
			return $this->redirect(array('action' => 'admin_index'));
		}
	}

/**
 * Generates a random key of a given length
 * @param type $length The length of the key
 * @return string The random key
 */
	private function __generateKey($length) {
		$key = "";
		$i = 0;
		$possible = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

		while ($i < $length) {
			$char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);

			if (!strstr($key, $char)) {
				$key .= $char;
				$i++;
			}
		}
		return $key;
	}

/**
 * __sendNewUserMail
 * Send a user a registration email
 *
 * @param $id int the id of the user to email
 */
	private function __sendNewUserMail($id) {
		// No sending emails in debug mode
		if ( Configure::read('debug') > 1 ) {
			$this->Email->delivery = 'debug';
		}
		$User = $this->User->read(null,$id);
		$Addr = $this->sourcekettle_config['Users']['send_email_from']['value'];
		$Key	= $this->User->EmailConfirmationKey->field('key', array('user_id' => $id));

		$this->Email->to		= $User['User']['email'];
		$this->Email->bcc		= array('secret@example.com');
		$this->Email->subject	= __('Welcome to %s - Account activation', $this->sourcekettle_config['UserInterface']['alias']['value']);
		$this->Email->replyTo	= $Addr;
		$this->Email->from		= __('%s Admin <%s>', $this->sourcekettle_config['UserInterface']['alias']['value'], $Addr);
		$this->Email->template	= 'email_activation';

		$this->Email->sendAs = 'text'; // because we hate to send pretty mail

		//Set view variables as normal
		$this->set('User', $User);
		$this->set('Key', $Key);
		if ($this->Email->send()) {
			return true;
		}
		return false;
	}

/**
 * __sendForgottenPasswordMail
 * Send a user a forgotten password email
 *
 * @param $id int the id of the user to email
 * @param $key int the id of the key to send
 */
	private function __sendForgottenPasswordMail($userId, $keyId) {
		// No sending emails in debug mode
		if ( Configure::read('debug') > 1 ) {
			$this->Email->delivery = 'debug';
		}
		$User	= $this->User->read(null, $userId);
		$Key	= $this->User->LostPasswordKey->read(null, $keyId);
		$Addr = $this->sourcekettle_config['Users']['send_email_from']['value'];

		// Couldn't find the user or the key for some reason, FAILURE.
		if (!$User || !$Key) {
			$this->log("[UsersController.__sendForgottenPasswordMail] lost password email could NOT be sent - User is $User ($userId), Key is $Key ($keyId)", 'sourcekettle');
			return false;
		}

		$this->Email->to		= $User['User']['email'];
		$this->Email->subject	= __('%s - Forgotten Password', $this->sourcekettle_config['UserInterface']['alias']['value']);
		$this->Email->replyTo	= $Addr;
		$this->Email->from		= __('%s Admin <%s>', $this->sourcekettle_config['UserInterface']['alias']['value'], $Addr);
		$this->Email->template	= 'email_forgotten_password';

		$this->Email->sendAs = 'text'; // because we hate to send pretty mail

		//Set view variables as normal
		$this->set('User', $User);
		$this->set('Key', $Key);

		if ($this->Email->send()) {
			$this->log("[UsersController.__sendForgottenPasswordMail] Lost password key ID $keyId sent to user ID $userId", 'sourcekettle');
			return true;
		}
		$this->log("[UsersController.__sendForgottenPasswordMail] lost password email could NOT be sent to user[" . $User['User']['id'] . "]", 'sourcekettle');
		return false;
	}

/**
 * __sendAdminCreatedUserMail
 * Send a user a email saying an account has been created
 *
 * @param $id int the id of the user to email
 * @param $key int the id of the key to send
 */
	private function __sendAdminCreatedUserMail($userId, $keyId) {
		// No sending emails in debug mode
		if ( Configure::read('debug') > 1 ) {
			$this->Email->delivery = 'debug';
		}
		$User	= $this->User->read(null, $userId);
		$Key	= $this->User->LostPasswordKey->read(null, $keyId);
		$Addr = $this->sourcekettle_config['Users']['send_email_from']['value'];

		$this->Email->to		= $User['User']['email'];
		$this->Email->subject	= __('Welcome to %s - Suprise!', $this->sourcekettle_config['UserInterface']['alias']['value']);
		$this->Email->replyTo	= $Addr;
		$this->Email->from		= __('%s Admin <%s>', $this->sourcekettle_config['UserInterface']['alias']['value'], $Addr);
		$this->Email->template	= 'email_admin_create';

		$this->Email->sendAs = 'text'; // because we hate to send pretty mail

		// Set view variables as normal
		$this->set('User', $User);
		$this->set('Key', $Key);
		if ($this->Email->send()) {
			return true;
		}
		return false;
	}

	/* ************************************************* *
	 *													 *
	 *			API SECTION OF CONTROLLER				 *
	 *			 CAUTION: PUBLIC FACING					 *
	 *													 *
	 * ************************************************* */

/**
 * api_autocomplete function.
 *
 * @access public
 * @return void
 */
	public function api_autocomplete() {
		$this->layout = 'ajax';

		$this->User->contain();
		$data = array('users' => array());

		if (isset($this->request->query['query'])
			&& $this->request->query['query'] != null
			&& strlen($this->request->query['query']) > 0) {

			$query = strtolower($this->request->query['query']);

			// At 3 characters, start matching anywhere within the name
			if(strlen($query) > 2){
				$query = "%$query%";
			} else {
				$query = "$query%";
			}

			$users = $this->User->find(
				"all",
				array(
					'conditions' => array(
						'OR' => array(
							'LOWER(User.name) LIKE' => $query,
							'LOWER(User.email) LIKE' => $query
						)
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
		$this->set('data', $data);
		$this->render('/Elements/json');
	}
}
