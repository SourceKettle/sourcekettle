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
		'app.user',
		'app.collaborator',
		'app.project',
		'app.repo_type',
		'app.task',
		'app.task_type',
		'app.task_status',
		'app.task_priority',
		'app.milestone',
		'app.task_comment',
		'app.time',
		'app.task_dependency',
		'app.source',
		'app.project_history',
		'app.attachment',
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
		'app.setting'
	);

	public function setUp() {
		parent::setUp("Users");
		$this->Setting = ClassRegistry::init("Setting");
		$this->User = ClassRegistry::init("User");
		$this->SshKey = ClassRegistry::init("SshKey");
	}
/**
 * testRegister method
 *
 * @return void
 */
	public function testRegisterRegistrationDisabled() {
		$this->testAction('/register', array('method' => 'get', 'return' => 'vars'));
		$this->assertContains("Registration disabled", $this->view);
	}

	public function testRegisterNotLoggedIn() {

		// First, enable registration
		$this->Setting->save(array(
			'name' => 'register_enabled',
			'value' => true,
		));

		// Now make sure we get the registration form
		$this->testAction('/register', array('method' => 'get', 'return' => 'vars'));
		$this->assertContains("<h1>Register with SourceKettle <small>Hello! Bonjour! Willkommen!..</small></h1>", $this->view);
	}

	public function testRegisterLoggedIn() {
		$this->_fakeLogin(2);

		// First, enable registration
		$this->Setting->save(array(
			'name' => 'register_enabled',
			'value' => true,
		));

		// Now make sure we get the registration form
		$this->testAction('/register', array('method' => 'get', 'return' => 'vars'));
		$this->assertContains("<h1>Register with SourceKettle <small>Hello! Bonjour! Willkommen!..</small></h1>", $this->view);
	}

	private function __testRegister($postData, $expectSuccess = true) {

		// First, enable registration
		$this->Setting->save(array(
			'name' => 'register_enabled',
			'value' => true,
		));


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

/**
 * testActivate method
 *
 * @return void
 */
	public function testActivateNoKey() {

		// First, enable registration
		$this->Setting->save(array(
			'name' => 'register_enabled',
			'value' => true,
		));
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("The key given was not a valid activation key.");

		$this->testAction('/activate', array('method' => 'get', 'return' => 'vars'));

		// Should get sent to the login page with a success message
		$this->assertRedirect('/');
	}

	public function testActivateBadKey() {

		// First, enable registration
		$this->Setting->save(array(
			'name' => 'register_enabled',
			'value' => true,
		));
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("<h4 class='alert-heading'>Error</h4>The link given was not valid. Please contact your system administrator to manually activate your account.");

		// Activate our non-active account
		$this->testAction('/activate/123456c5ce588f16647fe32603fb1593', array('method' => 'get', 'return' => 'vars'));

		$this->assertRedirect('/');
	}

	public function testActivateFailed() {

		// First, enable registration
		$this->Setting->save(array(
			'name' => 'register_enabled',
			'value' => true,
		));
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

		// First, enable registration
		$this->Setting->save(array(
			'name' => 'register_enabled',
			'value' => true,
		));

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

/**
 * testLostPassword method
 *
 * @return void
 */
	public function testLostPasswordForm() {
		$this->testAction('/lost_password', array('method' => 'get', 'return' => 'vars'));
		$this->assertRegexp('|<form action=".*'.Router::url('/lost_password').'"|', $this->view);
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
			->with("It looks like you're using an account that is not managed by SourceKettle - unfortunately, we can't help you reset your password. Try talking to <a href='mailto:admin@example.org'>the system administrator</a>.");

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

/**
 * testResetPassword method
 *
 * @return void
 */
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

/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndex() {
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
	}

/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminView() {
	}

/**
 * testView method
 *
 * @return void
 */
	public function testView() {
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {
	}

/**
 * testAdminAdd method
 *
 * @return void
 */
	public function testAdminAdd() {
	}

/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEdit() {
	}

/**
 * testDetails method
 *
 * @return void
 */
	public function testDetails() {
	}

/**
 * testSecurity method
 *
 * @return void
 */
	public function testSecurity() {
	}

/**
 * testTheme method
 *
 * @return void
 */
	public function testTheme() {
	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {
	}

/**
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete() {
	}

/**
 * testAdminPromote method
 *
 * @return void
 */
	public function testAdminPromote() {
	}

/**
 * testAdminDemote method
 *
 * @return void
 */
	public function testAdminDemote() {
	}

/**
 * testApiRegister method
 *
 * @return void
 */
	public function testApiRegister() {
	}

/**
 * testApiView method
 *
 * @return void
 */
	public function testApiView() {
	}

/**
 * testApiAll method
 *
 * @return void
 */
	public function testApiAll() {
	}

/**
 * testApiAutocomplete method
 *
 * @return void
 */
	public function testApiAutocomplete() {
	}

}
