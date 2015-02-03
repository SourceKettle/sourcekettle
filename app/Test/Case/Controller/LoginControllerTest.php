<?php
App::uses('LoginController', 'Controller');
require_once(__DIR__ . DS . 'AppControllerTest.php');

/**
 * LoginController Test Case
 *
 */
class LoginControllerTest extends AppControllerTest {

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
	);

	public function setUp() {
		ControllerTestCase::setUp();
		$this->controller = $this->generate('Login', array(
			'methods' => array(
				'_tryRememberMeLogin',
				'_checkSignUpProgress',
			),
			'components' => array(
				'Security' => array(
					'_validateCsrf',
				),
				'Session' => array(
					'setFlash',
				),
				'Email',
			)
		));
	}

	public function tearDown() {
		$this->controller->Session->destroy();
		ControllerTestCase::tearDown();
	}


/**
 * testIndex method
 *
 * @return void
 */
	public function testIndexGetRedirect() {
		
		$ret = $this->testAction('/login', array('method' => 'get', 'return' => 'view'));
		$this->assertRedirect(array('controller' => 'pages', 'action' => 'home'));
	}

	public function testIndexInvalidUser() {
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with('The credentials you entered were incorrect. Please try again, or have you <a href="'.Router::url('/lost_password').'">lost your password</a>?');

		$postData = array('User' => array('email' => 'moose@example.org', 'password' => 'SomePassword'));
		$ret = $this->testAction('/login', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertRedirect(array('controller' => 'pages', 'action' => 'home'));
	}

	public function testIndexInactiveUser() {
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with('The credentials you entered were incorrect. Please try again, or have you <a href="'.Router::url('/lost_password').'">lost your password</a>?');

		$postData = array('User' => array('email' => 'inactiverealperson@example.com', 'password' => 'RealGoodPassword'));
		$ret = $this->testAction('/login', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertRedirect(array('controller' => 'pages', 'action' => 'home'));
	}

	public function testIndexActiveUser() {

		$postData = array('User' => array('email' => 'realperson@example.com', 'password' => 'RealGoodPassword'));
		$ret = $this->testAction('/login', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertRedirect(array('controller' => 'dashboard', 'action' => 'index'));
		$this->assertNotNull($this->controller->Auth->user());
	}

	public function testIndexAlreadyLoggedIn() {

		$postData = array('User' => array('email' => 'realperson@example.com', 'password' => 'RealGoodPassword'));
		$ret = $this->testAction('/login', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertRedirect(array('controller' => 'dashboard', 'action' => 'index'));
		$ret = $this->testAction('/login', array('method' => 'get', 'return' => 'view'));
		$this->assertRedirect(array('controller' => 'dashboard', 'action' => 'index'));
	}
/**
 * testLogout method
 *
 * @return void
 */
	public function testLogout() {
		$postData = array('User' => array('email' => 'realperson@example.com', 'password' => 'RealGoodPassword'));
		$ret = $this->testAction('/login', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertRedirect(array('controller' => 'dashboard', 'action' => 'index'));
		$this->testAction("/logout");
		$this->assertRedirect("/");
		$this->assertNull($this->controller->Auth->user());
	}

}
