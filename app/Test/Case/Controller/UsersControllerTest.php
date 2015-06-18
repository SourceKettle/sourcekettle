<?php
App::uses('UsersController', 'Controller');
require_once(__DIR__ . DS . 'AppControllerTest.php');

/**
 * UsersController Test Case
 *
 */
class UsersControllerTest extends AppControllerTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'core.cake_session',
		'app.setting',
		'app.user_setting',
		'app.project_setting',
		'app.project',
		'app.project_history',
		'app.repo_type',
		'app.collaborator',
		'app.user',
		'app.task',
		'app.task_type',
		'app.task_dependency',
		'app.task_comment',
		'app.task_status',
		'app.task_priority',
		'app.time',
		'app.attachment',
		'app.source',
		'app.milestone',
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
		'app.milestone_burndown_log',
		'app.project_burndown_log',
		'app.collaborating_team',
		'app.group_collaborating_team',
		'app.team',
		'app.teams_user',
		'app.project_group',
		'app.project_groups_project',
		'app.story',
	);

	public function setUp() {
		parent::setUp("Users");
		$this->Setting = ClassRegistry::init("Setting");
		$this->User = ClassRegistry::init("User");
		$this->SshKey = ClassRegistry::init("SshKey");
		// It seems loading Setting here breaks the sourcekettle_config loading somehow...
		$this->controller->sourcekettle_config = $this->Setting->loadConfigSettings();
	}


	public function testEditSettingsNotLoggedIn() {
		try{
			$this->testAction('/account/details', array('method' => 'get', 'return' => 'vars'));
		} catch(Exception $e) {}
		$this->assertNotAuthorized();
	}

	public function testEditSettingsFormInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/account/details', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testEditSettingsFormInternal() {
		$this->_fakeLogin(1);
		$this->testAction('/account/details', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/account/details').'"|', $this->view);
		$this->assertRegexp('|<input name=.*"data\[User\]\[name\]".*value="Mr Smith"|', $this->view);
		$this->assertRegexp('|<input name=.*"data\[User\]\[email\]"|', $this->view);
	}

	public function testEditSettingsFormExternal() {
		$this->_fakeLogin(23);
		$this->testAction('/account/details', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/account/details').'"|', $this->view);
		$this->assertRegexp('|<input name=.*"data\[User\]\[name\]".*value="An external account"|', $this->view);
		$this->assertNotRegexp('|<input name=.*"data\[User\]\[email\]".*value="ldap-user@example.com"|', $this->view);
	}

	public function testEditSettingsFail() {
		$this->_fakeLogin(1);
		$postData = array('User' => array(
			'name' => 'Mr Flibble',
			'email' => 'flibble@example.com',
		));

		$this->controller->User = $this->getMockForModel('User', array('save'));
		$this->controller->User
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__('There was a problem saving your changes. Please try again.'), 'default', array(), 'error');

		$this->testAction('/account/details', array('method' => 'post', 'return' => 'vars', 'data' => $postData));
		$this->assertAuthorized();

		$retrieved = $this->User->find('first', array(
			'conditions' => array('id' => 1),
			'fields' => array('name', 'email'),
			'recursive' => -1,
		));

		$this->assertNotEquals($postData, $retrieved);
	}

	public function testEditSettingsInternal() {
		$this->_fakeLogin(1);
		$postData = array('User' => array(
			'name' => 'Mr Flibble',
			'email' => 'flibble@example.com',
		));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__('Your changes have been saved.'), 'default', array(), 'success');
		$this->testAction('/account/details', array('method' => 'post', 'return' => 'vars', 'data' => $postData));
		$this->assertAuthorized();

		$retrieved = $this->User->find('first', array(
			'conditions' => array('id' => 1),
			'fields' => array('name', 'email'),
			'recursive' => -1,
		));
		$this->assertEquals($postData, $retrieved);
	}

	public function testEditSettingsExternal() {
		$this->_fakeLogin(23);
		$postData = array('User' => array(
			'name' => 'Mr Flibble',
			'email' => 'flibble@example.com',
		));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__('Your changes have been saved.'), 'default', array(), 'success');

		$this->testAction('/account/details', array('method' => 'post', 'return' => 'vars', 'data' => $postData));
		$this->assertAuthorized();

		$retrieved = $this->User->find('first', array(
			'conditions' => array('id' => 23),
			'fields' => array('name', 'email'),
			'recursive' => -1,
		));

		// Check that the email address was not updated
		$postData['User']['email'] = 'ldap-user@example.com';
		$this->assertEquals($postData, $retrieved);
	}

	public function testRegisterRegistrationDisabled() {
		$this->Setting->save(array(
			'name' => 'Users.register_enabled',
			'value' => false,
		));
		$this->testAction('/register', array('method' => 'get', 'return' => 'vars'));
		$this->assertContains("Registration disabled", $this->view);
	}

	public function testRegisterNotLoggedIn() {

		// Now make sure we get the registration form
		$this->testAction('/register', array('method' => 'get', 'return' => 'view'));
	}

	public function testRegisterLoggedIn() {
		$this->_fakeLogin(2);

		// Now make sure we get the registration form
		$this->testAction('/register', array('method' => 'get', 'return' => 'vars'));
	}

	private function __testRegister($postData, $expectSuccess = true) {

		// Register an account
		$this->testAction('/register', array('method' => 'post', 'return' => 'vars', 'data' => $postData));

		if ($expectSuccess) {

			$this->assertContains("We've emailed you a confirmation link which you will need to click on to activate your account.", $this->view);
	
			// Check the account now exists and has an SSH key, but is not active
			$retrieved = $this->User->findByEmail('grim@example.com');
			$this->assertEquals($postData['User']['name'], $retrieved['User']['name']);
			$this->assertEquals($postData['User']['email'], $retrieved['User']['email']);
			$this->assertEquals(true, $retrieved['User']['is_internal']);
			$this->assertEquals(false, $retrieved['User']['is_admin']);
			$this->assertEquals(false, $retrieved['User']['is_active']);
			$this->assertContains($retrieved['SshKey'][0]['key'], $postData['User']['ssh_key']);
			$this->assertEquals('Default key', $retrieved['SshKey'][0]['comment']);

			return $retrieved;
		}
	}

	public function testRegisterMismatchedPassword() {
		// Fake out the email component
		$this->controller->Email
			->expects($this->never())
			->method('send');
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("<h4 class='alert-heading'>Error</h4>The passwords do not match. Please try again.");
		
		$this->__testRegister(array('User' => array(
			'name' => 'Manny Calavera',
			'email' => 'grim@example.com',
			'password' => 'Yatta!Stairs?',
			'password_confirm' => 'Stairs!Yatta?',
		)), false);
	}

	public function testRegisterShortPassword() {
		// Fake out the email component
		$this->controller->Email
			->expects($this->never())
			->method('send');
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("<h4 class='alert-heading'>Error</h4>One or more fields were not filled in correctly. Please try again.");
		
		$this->__testRegister(array('User' => array(
			'name' => 'Manny Calavera',
			'email' => 'grim@example.com',
			'password' => 'ham',
			'password_confirm' => 'ham',
		)), false);

		$this->assertContains("Your password must be at least 8 characters", $this->view);
	}

	public function testRegisterBadPassword() {
		// Fake out the email component
		$this->controller->Email
			->expects($this->never())
			->method('send');
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("<h4 class='alert-heading'>Oh Dear...</h4>I see what you did there. 'password' is not a good password. Be more original!");
		
		$this->__testRegister(array('User' => array(
			'name' => 'Manny Calavera',
			'email' => 'grim@example.com',
			'password' => 'password',
			'password_confirm' => 'password',
		)), false);
	}

	public function testRegisterOK() {
		// Fake out the email component
		$this->controller->Email
			->expects($this->once())
			->method('send')
			->will($this->returnValue(true));
		
		$this->__testRegister(array('User' => array(
			'name' => 'Manny Calavera',
			'email' => 'grim@example.com',
			'password' => 'Yatta!Stairs?',
			'password_confirm' => 'Yatta!Stairs?',
			'ssh_key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQC4DRX0zeM9yzL9b8U0qpFzyIAX8bvSODDZ/Ou2VaLli1VGTAl1k/oJwEdUPcJOuVhz0bmtjBe7Y8V/ioXJHYw6k3isoQU28/5sfnXlPgDohAtF6osy1EnlSVSSxMUeBH75DO1nmivA5nhjGJkfizm3ULmUAvqRGYtYyoCvEYcq1Qxpzewu4676Yzj5nHHinNsXXVDsz21dHjxnWnXMIyHsa8vAww+Xif4MO6fahR3qUI8OKaKz8hRqc6g46oxkmhTbuiyMAVspHJEIPTR3eB1ZqohZ2k3CGBdQQNMUDTaGTePTOquLUX4uF5F5dnmhTMeFoySq6gUsFMAVPupNcejN prump@prumpmaster.local',
		)), true);
	}

	public function testActivateNoKey() {

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("The key given was not a valid activation key.");

		$this->testAction('/activate', array('method' => 'get', 'return' => 'vars'));

		// Should get sent to the login page with a success message
		$this->assertRedirect('/');
	}

	public function testActivateBadKey() {

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("<h4 class='alert-heading'>Error</h4>The link given was not valid. Please contact your system administrator to manually activate your account.");

		// Activate our non-active account
		$this->testAction('/activate/123456c5ce588f16647fe32603fb1593', array('method' => 'get', 'return' => 'vars'));

		$this->assertRedirect('/');
	}

	public function testActivateFailed() {

		$this->controller->User = $this->getMockForModel('User', array('save'));
		$this->controller->User
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("<h4 class='alert-heading'>Error</h4>An error occured, please contact your system administrator."));

		// Activate our non-active account
		$this->testAction('/activate/ba6f23c5ce588f16647fe32603fb1593', array('method' => 'get', 'return' => 'vars'));

		$this->assertRedirect('/');
	}

	public function testActivateOK() {

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("<h4 class='alert-heading'>Success</h4>Your account is now activated. You can now login.");

		// Activate our non-active account
		$this->testAction('/activate/ba6f23c5ce588f16647fe32603fb1593', array('method' => 'get', 'return' => 'vars'));

		// Should get sent to the login page with a success message
		$this->assertRedirect('/login');
		$retrieved = $this->User->findById(11);
		$this->assertTrue($retrieved['User']['is_active'], "Activation failed");
	}

	public function testLostPasswordForm() {
		$this->testAction('/lost_password', array('method' => 'get', 'return' => 'vars'));
		$this->assertRegexp('|<form action=".*'.Router::url('/lost_password').'"|', $this->view);
	}

	public function testLostPasswordPostFail() {
		$this->testAction('/lost_password', array('method' => 'postt', 'return' => 'vars'));
		$this->assertRedirect('/lost_password');
	}

	public function testLostPasswordUnknownEmail() {
		$this->controller->Email
			->expects($this->never())
			->method('send');
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("A problem occurred when resetting your password - if the problem persists you should contact <a href='mailto:admin@example.org'>the system administrator</a>.");

		$postData = array('User' => array(
			'email' => 'somefakeaccount@nowhere.example.org',
		));

		$this->testAction('/lost_password', array('method' => 'post', 'return' => 'vars', 'data' => $postData));

		// Should get sent to the login page with a success message
		$this->assertRedirect('/login');
	}

	public function testLostPasswordExternalAccount() {
		$this->controller->Email
			->expects($this->never())
			->method('send');
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("It looks like you're using an account that is not managed by ".$this->controller->sourcekettle_config['UserInterface']['alias']['value']." - unfortunately, we can't help you reset your password. Try talking to <a href='mailto:admin@example.org'>the system administrator</a>.");

		$postData = array('User' => array(
			'email' => 'snaitf@example.com',
		));

		$this->testAction('/lost_password', array('method' => 'post', 'return' => 'vars', 'data' => $postData));

		// Should get sent to the login page with a success message
		$this->assertRedirect('/login');
	}

	public function testLostPasswordSendFailed() {
		$this->controller->Email
			->expects($this->once())
			->method('send')
			->will($this->returnValue(false));
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("There was a problem sending the lost password email");

		$postData = array('User' => array(
			'email' => 'mrs.smith@example.com',
		));

		$this->testAction('/lost_password', array('method' => 'post', 'return' => 'vars', 'data' => $postData));

		// Should get the form again
		$this->assertRegexp('|<form action=".*'.Router::url('/lost_password').'"|', $this->view);

	}

	public function testLostPasswordSuccess() {
		$this->controller->Email
			->expects($this->once())
			->method('send')
			->will($this->returnValue(true));
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("An email was sent to the given email address. Please use the link to reset your password.");

		$postData = array('User' => array(
			'email' => 'mrs.smith@example.com',
		));

		$this->testAction('/lost_password', array('method' => 'post', 'return' => 'vars', 'data' => $postData));

		// Should get sent to the login page with a success message
		$this->assertRedirect('/login');
	}

	public function testResetPasswordNoKey() {
		$this->testAction('/reset_password', array('method' => 'post', 'return' => 'vars'));
		$this->assertRedirect('/lost_password');
	}

	public function testResetPasswordInvalidKey() {
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("The key given was invalid");

		$this->testAction('/reset_password/notarealkeyherefoobar', array('method' => 'post', 'return' => 'vars'));
		$this->assertRedirect('/lost_password');
	}

	public function testResetPasswordForm() {
		$this->testAction('/reset_password/ab169f5ff7fbbcdd7db9bd078', array('method' => 'get', 'return' => 'vars'));
		$this->assertRegexp('|<form action=".*'.Router::url('/reset_password/ab169f5ff7fbbcdd7db9bd078').'"|', $this->view);
	}

	public function testResetPasswordPutFail() {
		$this->testAction('/reset_password', array('method' => 'put', 'return' => 'vars'));
		$this->assertRedirect('/lost_password');
	}

	public function testResetPasswordKeyExpired() {

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("The key given has expired");

		$this->testAction('/reset_password/ab169f5ff7fbbcdd7db9bd077', array('method' => 'post', 'return' => 'vars'));

		$this->assertRedirect('/lost_password');
	}

	public function testResetPasswordNoMatch() {
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("Your passwords do not match. Please try again.");

		$postData = array('User' => array(
			'password' => 'Yatta!Stairs?',
			'password_confirm' => 'Stairs!Yatta?',
		));

		$this->testAction('/reset_password/ab169f5ff7fbbcdd7db9bd078', array('method' => 'post', 'return' => 'vars', 'data' => $postData));

	}

	public function testResetPasswordShortPassword() {
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("There was a problem resetting your password. Please try again.");

		$postData = array('User' => array(
			'password' => 'short',
			'password_confirm' => 'short',
		));

		$this->testAction('/reset_password/ab169f5ff7fbbcdd7db9bd078', array('method' => 'post', 'return' => 'vars', 'data' => $postData));

		$this->assertContains("Your password must be at least 8 characters", $this->view);
	}

	public function testResetPasswordBadPassword() {
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("<h4 class='alert-heading'>Oh Dear...</h4>I see what you did there. 'password' is not a good password. Be more original!");
		$postData = array('User' => array(
			'password' => 'password',
			'password_confirm' => 'password',
		));

		$this->testAction('/reset_password/ab169f5ff7fbbcdd7db9bd078', array('method' => 'post', 'return' => 'vars', 'data' => $postData));
	}

	public function testResetFailure() {
		$this->controller->User = $this->getMockForModel('User', array('save'));
		$this->controller->User
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("There was a problem resetting your password. Please try again."));

		$postData = array('User' => array(
			'password' => 'Yatta!Stairs?',
			'password_confirm' => 'Yatta!Stairs?',
		));

		$this->testAction('/reset_password/ab169f5ff7fbbcdd7db9bd078', array('method' => 'post', 'return' => 'vars', 'data' => $postData));

	}

	public function testResetPasswordOK() {
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("Your password has been reset. You can now login.");

		$postData = array('User' => array(
			'password' => 'Yatta!Stairs?',
			'password_confirm' => 'Yatta!Stairs?',
		));

		$this->testAction('/reset_password/ab169f5ff7fbbcdd7db9bd078', array('method' => 'post', 'return' => 'vars', 'data' => $postData));

		$this->assertRedirect('/login');

	}


	public function testAdminApproveForm() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/users/approve', array('method' => 'get', 'return' => 'vars'));
		$this->assertRegexp('|<form.*action=".*'.Router::url('/admin/users/approve/ba6f23c5ce588f16647fe32603fb1593').'"|', $this->view);
	}

	public function testAdminApproveNoKey() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/users/approve', array('method' => 'post', 'return' => 'vars'));
		$this->assertRedirect('/admin/users/approve');
	}

	public function testAdminApproveBadKey() {
		$this->testAction('/admin/users/approve/123123123123123123123123123', array('method' => 'post', 'return' => 'vars'));
		$this->assertRedirect('/admin/users/approve');
	}

	public function testAdminApproveFailure() {
		$this->_fakeLogin(5);
		$this->controller->User = $this->getMockForModel('User', array('save'));
		$this->controller->User
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));

		$this->testAction('/admin/users/approve/ba6f23c5ce588f16647fe32603fb1593', array('method' => 'post', 'return' => 'vars'));
		$this->assertRedirect('/admin/users/approve');

		$key = $this->controller->User->EmailConfirmationKey->findByKey('ba6f23c5ce588f16647fe32603fb1593', array('recursive' => 0));
		$this->assertEquals(array('EmailConfirmationKey' => array(
			'id' => 2,
			'user_id' => 11,
			'key' => 'ba6f23c5ce588f16647fe32603fb1593',
			'created' => '2012-06-01 12:33:03',
			'modified' => '2012-06-01 12:33:03',
		)), $key);

		$isActive = $this->controller->User->find('first', array('recursive' => 0, 'conditions' => array('id' => 11), 'fields' => array('is_active')));
		$this->assertEquals(array('User' => array('id' => 11, 'is_active' => false)), $isActive);
	}

	public function testAdminApproveOK() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/users/approve/ba6f23c5ce588f16647fe32603fb1593', array('method' => 'post', 'return' => 'vars'));
		$this->assertRedirect('/admin/users/approve');

		$key = $this->controller->User->EmailConfirmationKey->findByKey('ba6f23c5ce588f16647fe32603fb1593');
		$this->assertEquals(array(), $key);

		$isActive = $this->controller->User->find('first', array('recursive' => 0, 'conditions' => array('id' => 11), 'fields' => array('is_active')));
		$this->assertEquals(array('User' => array('id' => 11, 'is_active' => true)), $isActive);
	}




	public function testAdminIndexNotLoggedIn() {
		$this->testAction('/admin/users', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminIndexNotSystemAdmin() {
		$this->_fakeLogin(1);
		$this->testAction('/admin/users', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminIndexInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/admin/users', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminIndexInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/admin/users', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminIndexOK() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/users', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testAdminIndexUserRedirectFail() {
		$this->_fakeLogin(5);
		$postData = array('User' => array('name' => 'Foo Bar [foobar@xample.com]'));
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__('The specified user does not exist. Please try again.'), 'default', array(), 'error');
		$this->testAction('/admin/users', array('method' => 'post', 'return' => 'vars', 'data' => $postData));
		$this->assertAuthorized();
	}

	public function testAdminIndexUserRedirectOK() {
		$this->_fakeLogin(5);
		$postData = array('User' => array('name' => 'Foo Bar [ldap-user@example.com]'));
		$this->testAction('/admin/users', array('method' => 'post', 'return' => 'vars', 'data' => $postData));
		$this->assertAuthorized();
		$this->assertRedirect('/admin/users/view/23');
	}

	public function testIndexNotLoggedIn() {
		$this->testAction('/users', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testIndexNormalUser() {
		$this->_fakeLogin(2);
		$this->testAction('/users', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertRedirect('/account/details');
	}
	public function testIndexSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/users', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertRedirect('/account/details');
	}
	public function testIndexInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/users', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}
	public function testIndexInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/users', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminViewNotLoggedIn() {
		$this->testAction('/admin/users/view/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminViewNotSystemAdmin() {
		$this->_fakeLogin(1);
		$this->testAction('/admin/users/view/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminViewInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/admin/users/view/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminViewInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/admin/users/view/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminViewInvalidUser() {
		$this->_fakeLogin(5);
		try{
			$this->testAction('/admin/users/view/999', array('method' => 'get', 'return' => 'vars'));
		} catch (NotFoundException $e) {
			$this->assertTrue(true, "Correct exception thrown");
			return;
		} catch (Exception $e) {
			$this->assertTrue(false, "Incorrect exception thrown: ".$e->getMessage());
			return;
		}
		$this->assertTrue(true, "No exception thrown");
	}

	public function testAdminViewOK() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/users/view/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testViewNotLoggedIn() {
		$this->testAction('/users/view/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testViewInvalidUser() {
		try{
			$this->testAction('/users/view/999', array('method' => 'get', 'return' => 'vars'));
		} catch(NotFoundException $e) {
			$this->assertTrue(true, "Correct exception thrown");
			return;
		} catch(Exception $e) {
			$this->assertTrue(false, "Incorrect exception thrown: ".$e->getMessage());
			return;
		}
		$this->assertTrue(false, "No exception thrown");
	}

	public function testViewNotSystemAdmin() {
		$this->_fakeLogin(1);
		$this->testAction('/users/view/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$projects = array_map(function($a) {return $a['Project']['id'];}, $this->vars['projects']);
		$sharedProjects = array_map(function($a) {return $a['Project']['id'];}, $this->vars['shared_projects']);
		$this->assertEquals(array(), $projects);
		$this->assertEquals(array(1), $sharedProjects);
	}

	public function testViewOwnProfile() {
		$this->_fakeLogin(3);
		$this->testAction('/users/view/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$projects = array_map(function($a) {return $a['Project']['id'];}, $this->vars['projects']);
		$sharedProjects = array_map(function($a) {return $a['Project']['id'];}, $this->vars['shared_projects']);
		$this->assertEquals(array(), $projects);
		$this->assertEquals(array(), $sharedProjects);
	}

	public function testViewSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/users/view/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$projects = array_map(function($a) {return $a['Project']['id'];}, $this->vars['projects']);
		$sharedProjects = array_map(function($a) {return $a['Project']['id'];}, $this->vars['shared_projects']);
		$this->assertEquals(array(), $projects);
		$this->assertEquals(array(1), $sharedProjects);
	}

	public function testViewUserInTeams() {
		$this->_fakeLogin(5);
		$this->testAction('/users/view/19', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$projects = array_map(function($a) {return $a['Project']['id'];}, $this->vars['projects']);
		$sharedProjects = array_map(function($a) {return $a['Project']['id'];}, $this->vars['shared_projects']);
		$teams = array_map(function($a) {return $a['id'];}, $this->vars['user']['Team']);
		$this->assertEquals(array(), $projects);
		$this->assertEquals(array(), $sharedProjects);
		$this->assertEquals(array(1, 2, 3), $teams);

	}

	public function testAdd() {
		$this->testAction('/users/add', array('method' => 'get', 'return' => 'view'));
		$this->assertRedirect('/users/register');
	}

	public function testAdminAddNotLoggedIn() {
		$this->testAction('/admin/users/add', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminAddNotSystemAdmin() {
		$this->_fakeLogin(1);
		$this->testAction('/admin/users/add', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminAddInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/admin/users/add', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminAddInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/admin/users/add', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminAddFormOK() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/users/add', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/admin/users/add').'"|', $this->view);
	}

	public function testAdminAddUserFail() {
		$postData = array('User' => array(
			'name' => 'Someone new',
			'email' => 'newbie@example.com',
		));
		$this->_fakeLogin(5);
		$this->controller->User = $this->getMockForModel('User', array('save'));
		$this->controller->User
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("<h4 class='alert-heading'>".__("Error")."</h4>".__("One or more fields were not filled in correctly. Please try again."), 'default', array(), 'error');

		$this->testAction('/admin/users/add', array('method' => 'post', 'data' => $postData, 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testAdminAddUserOK() {
		$postData = array('User' => array(
			'name' => 'Someone new',
			'email' => 'newbie@example.com',
		));
		$this->_fakeLogin(5);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("New User added successfully."));
		$this->testAction('/admin/users/add', array('method' => 'post', 'data' => $postData, 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertRedirect("/admin/users/view/".$this->controller->User->getLastInsertId());

		$user = $this->controller->User->findById($this->controller->User->getLastInsertId());
		$this->assertEquals('Someone new', $user['User']['name']);
		$this->assertEquals('newbie@example.com', $user['User']['email']);
	}


	public function testAdminEditNotLoggedIn() {
		$this->testAction('/admin/users/edit/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminEditNotSystemAdmin() {
		$this->_fakeLogin(1);
		$this->testAction('/admin/users/edit/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminEditInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/admin/users/edit/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminEditInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/admin/users/edit/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminEditInvalidUser() {
		try{
			$this->testAction('/admin/users/edit/999', array('method' => 'get', 'return' => 'vars'));
		} catch(NotFoundException $e) {
			$this->assertTrue(true, "Correct exception thrown");
			return;
		} catch(Exception $e) {
			$this->assertTrue(false, "Incorrect exception thrown: ".$e->getMessage());
			return;
		}
		$this->assertTrue(false, "No exception thrown");
	}

	public function testAdminEditGetRedirect() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/users/edit/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertRedirect("/admin/users/view/3");
	}

	public function testAdminEditUserFail() {
		$postData = array('User' => array(
			'id' => 3,
			'name' => 'Someone new',
			'email' => 'newbie@example.com',
		));
		$this->_fakeLogin(5);
		$this->controller->User = $this->getMockForModel('User', array('save'));
		$this->controller->User
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("User '<strong>%s</strong>' could not be updated. Please try again.", "Mrs Guest"));

		$this->testAction('/admin/users/edit/3', array('method' => 'post', 'data' => $postData, 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertRedirect("/admin/users/view/3");

		$user = $this->controller->User->findById(3);
		$this->assertNotEquals('Someone new', $user['User']['name']);
		$this->assertNotEquals('newbie@example.com', $user['User']['email']);
	}

	public function testAdminEditUserOK() {
		$postData = array('User' => array(
			'id' => 3,
			'name' => 'Someone new',
			'email' => 'newbie@example.com',
		));
		$this->_fakeLogin(5);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("User '<strong>%s</strong>' has been updated.", "Someone new"));
		$this->testAction('/admin/users/edit/3', array('method' => 'post', 'data' => $postData, 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertRedirect("/admin/users");

		$user = $this->controller->User->findById(3);
		$this->assertEquals('Someone new', $user['User']['name']);
		$this->assertEquals('newbie@example.com', $user['User']['email']);
	}

	public function testDetails() {
	}

	public function testSecurityNotLoggedIn() {
		try {
			$this->testAction('/users/security', array('method' => 'get', 'return' => 'vars'));
		// We're not logged in but the test bypasses auth, so ignore this error
		} catch (Exception $e) {}

		$this->assertNotAuthorized();
	}

	public function testSecurityNotSystemAdmin() {
		$this->_fakeLogin(1);
		$this->testAction('/users/security', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testSecurityInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/users/security', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testSecurityInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/users/security', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testSecuritySystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/users/security', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testSecurityGetForm() {
		$this->_fakeLogin(1);
		$this->testAction('/users/security', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/users/security').'"|', $this->view);

	}

	public function testSecurityIncorrectPassword() {
		$postData = array('User' => array(
			'password_current' => 'shoes',
			'password_confirm' => 'fooBarBaz',
			'password' => 'fooBarBaz',
		));
		$this->_fakeLogin(1);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__('Your current password was incorrect. Please try again.'), 'default', array(), 'error');
		$this->testAction('/users/security', array('method' => 'post', 'data' => $postData, 'return' => 'vars'));
		$this->assertAuthorized();

	}

	public function testSecurityMismatchedPassword() {
		$this->controller->User->save(array('User' => array('id' => 1, 'password' => 'shoeBagsAndDog')));
		$postData = array('User' => array(
			'password_current' => 'shoeBagsAndDog',
			'password_confirm' => 'fooBarBaz',
			'password' => 'fooBarBazThud',
		));
		$this->_fakeLogin(1);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__('Your passwords did not match. Please try again.'), 'default', array(), 'error');
		$this->testAction('/users/security', array('method' => 'post', 'data' => $postData, 'return' => 'vars'));
		$this->assertAuthorized();

	}

	public function testSecurityResetPassword() {
		$this->controller->User->save(array('User' => array('id' => 1, 'password' => 'shoeBagsAndDog')));
		$postData = array('User' => array(
			'password_current' => 'shoeBagsAndDog',
			'password_confirm' => 'fooBarBaz',
			'password' => 'fooBarBaz',
		));
		$this->_fakeLogin(1);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__('Your changes have been saved.'), 'default', array(), 'success');
		$this->testAction('/users/security', array('method' => 'post', 'data' => $postData, 'return' => 'vars'));
		$this->assertAuthorized();

	}

	public function testTheme() {
	}

	public function testDelete() {
	}

	public function testAdminDeleteNotLoggedIn() {
		$this->testAction('/admin/users/delete/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminDeleteNotSystemAdmin() {
		$this->_fakeLogin(1);
		$this->testAction('/admin/users/delete/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminDeleteInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/admin/users/delete/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminDeleteInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/admin/users/delete/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAdminDeleteOK() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/users/delete/3', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	private function __testChangeAdminLevel($loginAs, $userId, $setAdmin, $resultAdmin) {
		$this->_fakeLogin($loginAs);
		$action = ($setAdmin? 'promote' : 'demote');
		$ret = $this->testAction("/admin/users/$action/$userId", array('method' => 'post', 'return' => 'view'));
		$this->assertAuthorized();
		$user = $this->controller->User->findById($userId);
		$this->assertEquals($resultAdmin, $user['User']['is_admin']);
		$this->assertRedirect('/admin/users');
	}

	public function testAdminPromoteNotLoggedIn () {
		$ret = $this->testAction('/admin/users/promote/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAdminPromoteNotSystemAdmin () {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/admin/users/promote/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAdminPromoteInactiveUser () {
		$this->_fakeLogin(6);
		$ret = $this->testAction('/admin/users/promote/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAdminPromoteInactiveAdmin () {
		$this->_fakeLogin(22);
		$ret = $this->testAction('/admin/users/promote/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAdminPromoteFail () {
		$this->controller->User = $this->getMockForModel('User', array('save'));
		$this->controller->User
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__('Account was not promoted'), 'default', array(), 'error');
		$this->__testChangeAdminLevel(5, 1, true, false);
		$this->assertRedirect("/admin/users");
	}

	public function testAdminPromoteGetRedirect () {
		$this->testAction("/admin/users/promote/1", array('method' => 'get'));
		$this->assertRedirect("/admin/users");
	}

	public function testAdminPromoteAlreadyAdmin () {
		$this->__testChangeAdminLevel(5, 9, true, true);
	}

	public function testAdminPromoteSelfAlreadyAdmin () {
		$this->__testChangeAdminLevel(5, 5, true, true);
	}

	public function testAdminPromoteUserToAdmin () {
		$this->__testChangeAdminLevel(5, 1, true, true);
	}

	public function testAdminPromoteInactiveUserToAdmin () {
		$this->__testChangeAdminLevel(5, 6, true, false);
	}

	public function testAdminDemoteNotLoggedIn () {
		$ret = $this->testAction('/admin/users/demote/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAdminDemoteNotSystemAdmin () {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/admin/users/demote/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAdminDemoteInactiveUser () {
		$this->_fakeLogin(6);
		$ret = $this->testAction('/admin/users/demote/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAdminDemoteInactiveAdmin () {
		$this->_fakeLogin(22);
		$ret = $this->testAction('/admin/users/demote/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAdminDemoteFail () {
		$this->controller->User = $this->getMockForModel('User', array('save'));
		$this->controller->User
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__('Account was not demoted'), 'default', array(), 'error');
		$this->__testChangeAdminLevel(5, 9, false, true);
		$this->assertRedirect("/admin/users");
	}

	public function testAdminDemoteGetRedirect () {
		$this->testAction("/admin/users/demote/1", array('method' => 'get'));
		$this->assertRedirect("/admin/users");
	}

	public function testAdminDemoteAlreadyNotAdmin () {
		$this->__testChangeAdminLevel(5, 1, false, false);
	}

	public function testAdminDemoteSelfFail () {
		$this->__testChangeAdminLevel(5, 5, false, true);
	}

	public function testAdminDemoteAdminToUser () {
		$this->__testChangeAdminLevel(5, 9, false, false);
	}

	// Note that we have an invalid test fixture to test whether 'inactive admins' have access to things
	// We will happily demote an inactive admin, but NOT promote an inactive user to admin.
	public function testAdminDemoteInactiveAdminToUser () {
		$this->__testChangeAdminLevel(5, 22, false, false);
	}

	public function testApiAutocompleteNotLoggedIn() {
		$this->testAction('/api/users/autocomplete');
		$this->assertNotAuthorized();
	}

	public function testApiAutocompleteNormalUser() {
		$this->_fakeLogin(3);
		$this->testAction('/api/users/autocomplete');
		$this->assertAuthorized();
	}

	public function testApiAutocompleteSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/api/users/autocomplete');
		$this->assertAuthorized();
	}

	public function testApiAutocompleteTwoCharsAndBelow() {
		$this->_fakeLogin(3);
		$this->testAction('/api/users/autocomplete?query=a');

		$this->assertAuthorized();
		$this->assertEquals(array(
			'A Deletable User [deletable@example.com]',
			'An only-admin [only-admin@example.com]',
			'An admin with no projects [admin-no-projects@example.com]',
			'Another user [another-user@example.com]',
			'A non-confirmed user [non-confirmed@example.com]',
			'A user [user-@example.com]',
			'A PHP developer [php-dev@example.com]',
			'A Java developer [java-dev@example.com]',
			'A Python developer [python-dev@example.com]',
			'A PHP and Python developer [php-and-python-dev@example.com]',
			'A PHP and Java developer [php-and-java-dev@example.com]',
			'A Python and Java developer [python-and-java-dev@example.com]',
			'A PHP, Python and Java developer [php-python-and-java-dev@example.com]',
			'A Perl developer [perl-dev@example.com]',
			'Another Perl developer [another-perl-dev@example.com]',
			'An inactive admin user [inactive-admin@example.com]',
			'An external account [ldap-user@example.com]',
			'Account with real password [realperson@example.com]',
		), $this->vars['data']['users']);

		$this->testAction('/api/users/autocomplete?query=an');
		$this->assertAuthorized();
		$this->assertEquals(array(
			'An only-admin [only-admin@example.com]',
			'An admin with no projects [admin-no-projects@example.com]',
			'Another user [another-user@example.com]',
			'Another Perl developer [another-perl-dev@example.com]',
			'An inactive admin user [inactive-admin@example.com]',
			'An external account [ldap-user@example.com]',
		), $this->vars['data']['users']);

		$this->testAction('/api/users/autocomplete?query=er');
		$this->assertAuthorized();
		$this->assertEquals(array(), $this->vars['data']['users']);
	}

	public function testApiAutocompleteThreeCharsAndAbove() {
		$this->_fakeLogin(3);
		$this->testAction('/api/users/autocomplete?query=pyt');
		$this->assertAuthorized();
		$this->assertEquals(array(
			'A Python developer [python-dev@example.com]',
			'A PHP and Python developer [php-and-python-dev@example.com]',
			'A Python and Java developer [python-and-java-dev@example.com]',
			'A PHP, Python and Java developer [php-python-and-java-dev@example.com]',
		), $this->vars['data']['users']);

		$this->testAction('/api/users/autocomplete?query=devel');
		$this->assertAuthorized();
		$this->assertEquals(array(
			'A PHP developer [php-dev@example.com]',
			'A Java developer [java-dev@example.com]',
			'A Python developer [python-dev@example.com]',
			'A PHP and Python developer [php-and-python-dev@example.com]',
			'A PHP and Java developer [php-and-java-dev@example.com]',
			'A Python and Java developer [python-and-java-dev@example.com]',
			'A PHP, Python and Java developer [php-python-and-java-dev@example.com]',
			'A Perl developer [perl-dev@example.com]',
			'Another Perl developer [another-perl-dev@example.com]',
		), $this->vars['data']['users']);

	}

}
