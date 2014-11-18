<?php
App::uses('SettingsController', 'Controller');
require_once(__DIR__ . DS . 'AppControllerTest.php');

/**
 * SettingsController Test Case
 *
 */
class SettingsControllerTest extends AppControllerTest {

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
		parent::setUp("Settings");
	}


/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Settings);

		parent::tearDown();
	}

/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndexNotLoggedIn() {
		$result = $this->testAction('/admin/settings/index');
		$this->assertNotAuthorized();
	}

	public function testAdminIndexInactiveUser(){
		$this->_fakeLogin(6);
		$result = $this->testAction('/admin/settings/index');
		$this->assertNotAuthorized();
	}

	public function testAdminIndexInactiveAdmin(){
		$this->_fakeLogin(22);
		$result = $this->testAction('/admin/settings/index');
		$this->assertNotAuthorized();
	}

	public function testAdminIndexNotSystemAdmin() {
		$this->_fakeLogin(1);
		$result = $this->testAction('/admin/settings/index');
		$this->assertNotAuthorized();
	}

	public function testAdminIndexSystemAdmin() {
		$this->_fakeLogin(5);
		$result = $this->testAction('/admin/settings/index');
		$this->assertAuthorized();
	}

	public function testAdminSetNotLoggedIn() {
		$result = $this->testAction('/admin/settings/set');
		$this->assertNotAuthorized();
	}

	public function testAdminSetInactiveUser(){
		$this->_fakeLogin(6);
		$result = $this->testAction('/admin/settings/set');
		$this->assertNotAuthorized();
	}

	public function testAdminSetInactiveAdmin(){
		$this->_fakeLogin(22);
		$result = $this->testAction('/admin/settings/set');
		$this->assertNotAuthorized();
	}

	public function testAdminSetNotSystemAdmin() {
		$this->_fakeLogin(1);
		$result = $this->testAction('/admin/settings/set');
		$this->assertNotAuthorized();
	}

	public function testAdminSetSystemAdmin() {
		$this->_fakeLogin(5);
		$result = $this->testAction('/admin/settings/set');
		$this->assertAuthorized();
	}

	public function testAdminSetPost() {
		$this->_fakeLogin(5);
		$postData = array('Setting' => array(
			'Users' => array('sysadmin_email' => 'sysadmin@example.com', 'send_email_from' => 'noreply@example.com'),
			'Features' => array('task_enabled' => 'true', 'time_enabled' => 'false'),
		));
		$result = $this->testAction('/admin/settings/set', array('method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		$settings = $this->controller->Setting->loadConfigSettings();
		$this->assertEquals('sysadmin@example.com', $settings['Users']['sysadmin_email']['value']);
		$this->assertEquals('noreply@example.com', $settings['Users']['send_email_from']['value']);
		$this->assertEquals(1, $settings['Features']['task_enabled']['value']);
		$this->assertEquals(0, $settings['Features']['time_enabled']['value']);
	}

	public function testAdminSetAjax() {
		$this->_fakeLogin(5);
		$postData = array('Setting' => array(
			'Users' => array('sysadmin_email' => 'sysadmin@example.com', 'send_email_from' => 'noreply@example.com'),
			'Features' => array('task_enabled' => 'true', 'time_enabled' => 'false'),
		));

		$_ENV['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/settings/set', array('method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
		unset($_ENV['HTTP_X_REQUESTED_WITH']);

		$json = json_decode($this->view, true);
		$this->assertEquals(array('code' => 200, 'message' => __('Settings updated.')), $json);

		$settings = $this->controller->Setting->loadConfigSettings();
		$this->assertEquals('sysadmin@example.com', $settings['Users']['sysadmin_email']['value']);
		$this->assertEquals('noreply@example.com', $settings['Users']['send_email_from']['value']);
		$this->assertEquals(1, $settings['Features']['task_enabled']['value']);
		$this->assertEquals(0, $settings['Features']['time_enabled']['value']);
	}

	public function testAdminSetGetFail() {
		$this->_fakeLogin(5);
		try{
			$result = $this->testAction('/admin/settings/set', array('method' => 'get'));
		} catch(MethodNotAllowedException $e) {
			$this->assertTrue(true);
			return;
		}
		$this->assertFalse(true, "Exception not thrown");
	}

	public function testAdminLockNotLoggedIn() {
		$result = $this->testAction('/admin/settings/setLock');
		$this->assertNotAuthorized();
	}

	public function testAdminLockInactiveUser(){
		$this->_fakeLogin(6);
		$result = $this->testAction('/admin/settings/setLock');
		$this->assertNotAuthorized();
	}

	public function testAdminLockInactiveAdmin(){
		$this->_fakeLogin(22);
		$result = $this->testAction('/admin/settings/setLock');
		$this->assertNotAuthorized();
	}

	public function testAdminLockNotSystemAdmin() {
		$this->_fakeLogin(1);
		$result = $this->testAction('/admin/settings/setLock');
		$this->assertNotAuthorized();
	}

	public function testAdminLockGetFail() {
		$this->_fakeLogin(5);
		try{
			$result = $this->testAction('/admin/settings/setLock', array('method' => 'get'));
		} catch(MethodNotAllowedException $e) {
			$this->assertTrue(true);
			return;
		}
		$this->assertFalse(true, "Exception not thrown");
	}
	public function testAdminLockSystemAdmin() {
		$this->_fakeLogin(5);
		$result = $this->testAction('/admin/settings/setLock');
		$this->assertAuthorized();
	}
}
