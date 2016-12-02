<?php
App::uses('PagesController', 'Controller');
require_once __DIR__ . DS . 'AppControllerTest.php';
/**
 * PagesController Test Case
 *
 */
class PagesControllerTest extends AppControllerTest {

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
		'app.team',
		'app.teams_user',
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
	);

	public function setUp($controllerName = null) {
		parent::setUp("Pages");
	}
/**
 * testDisplay method
 *
 * @return void
 */
	public function testDisplayEmptyNotLoggedIn() {
		$this->testAction('/pages', array('return' => 'view', 'method' => 'get'));
		// We should be redirected to the home page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/', true), $this->headers['Location']);
	}

	public function testDisplayEmptyLoggedIn() {
		$this->_fakeLogin(2);
		$this->testAction('/pages', array('return' => 'view', 'method' => 'get'));

		// We should be redirected to the home page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/', true), $this->headers['Location']);
	}


/**
 * testHome method
 *
 * @return void
 */
	public function testDefaultNotLoggedIn() {

		// Logged out - show the home page at /
		$rendered = $this->testAction('/', array('return' => 'view', 'method' => 'get'));
		$this->assertContains($this->controller->sourcekettle_config['UserInterface']['alias']['value'].' uses cookies!', $this->view);
	}

	public function testDefaultLoggedIn() {
		// Logged in - redirect to the dashboard
		$this->_fakeLogin(2);
		$this->testAction('/', array('return' => 'result', 'method' => 'get'));

		// We should be redirected to the dashboard
		$this->assertRedirect('/dashboard');
	}

	public function testHomeNotLoggedIn() {
		$rendered = $this->testAction('/pages/home', array('return' => 'view', 'method' => 'get'));
		$this->assertContains($this->controller->sourcekettle_config['UserInterface']['alias']['value'].' uses cookies!', $this->view);
	}

	public function testHomeLoggedIn() {
		$this->_fakeLogin(2);
		$this->testAction('/pages/home', array('return' => 'result', 'method' => 'get'));
		$this->assertContains($this->controller->sourcekettle_config['UserInterface']['alias']['value'].' uses cookies!', $this->view);
	}

	/**
	 * test invite menu item
	 */

	public function testInviteShownForExternalUserWhileInvitesActive() {
		$this->_fakeLogin(23);
		$this->_invitesEnabled();

		$rendered = $this->testAction('/pages/home', array('return' => 'contents', 'method' => 'get'));

		$this->assertContains('<a href="/users/invite">Invite external user</a>', $rendered);
	}

	public function testInviteNotShownForExternalUserWhileInvitesInactive() {
		$this->_fakeLogin(23);
		$this->_invitesEnabled(false);

		$rendered = $this->testAction('/pages/home', array('return' => 'contents', 'method' => 'get'));

		$this->assertNotContains('<a href="/users/invite">Invite external user</a>', $rendered);
	}

	public function testInviteNotShownForInternalUserWhileInvitesActive() {
		$this->_fakeLogin(2);
		$this->_invitesEnabled();

		$rendered = $this->testAction('/pages/home', array('return' => 'contents', 'method' => 'get'));

		$this->assertNotContains('<a href="/users/invite">Invite external user</a>', $rendered);
	}

	public function testInviteNotShownForInternalUserWhileInvitesInactive() {
		$this->_fakeLogin(2);
		$this->_invitesEnabled(false);

		$rendered = $this->testAction('/pages/home', array('return' => 'contents', 'method' => 'get'));

		$this->assertNotContains('<a href="/users/invite">Invite external user</a>', $rendered);
	}
/**
 * testAbout method
 *
 * @return void
 */

	public function testAboutNotLoggedIn() {
		$this->testAction('/about', array('return' => 'result', 'method' => 'get'));
		$this->assertContains('We &hearts; open-source', $this->view);
		$this->assertNotContains('Tuba solo', $this->view);
	}
	public function testAboutNotSystemAdmin() {
		$this->_fakeLogin(2);
		$this->testAction('/about', array('return' => 'result', 'method' => 'get'));
		$this->assertContains('We &hearts; open-source', $this->view);
		$this->assertNotContains('Tuba solo', $this->view);
	}
	public function testAboutSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/about', array('return' => 'result', 'method' => 'get'));
		$this->assertContains('We &hearts; open-source', $this->view);
		$this->assertContains('Tuba solo', $this->view);
	}

}
