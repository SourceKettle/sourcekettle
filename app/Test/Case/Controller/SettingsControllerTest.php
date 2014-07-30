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
		'app.setting',
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
	public function testAdminIndex() {
		$result = $this->testAction('/admin/settings/index');
		debug($result);
	}
/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminView() {
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
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete() {
	}

}
