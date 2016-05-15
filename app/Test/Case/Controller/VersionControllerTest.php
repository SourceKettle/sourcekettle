<?php
App::uses('VersionController', 'Controller');
require_once(__DIR__ . DS . 'AppControllerTest.php');

/**
 * VersionController Test Case
 *
 */
class VersionControllerTest extends AppControllerTest {

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

	public function setUp($controllerName = null) {
		parent::setUp("Version");
	}
/**
 * testApiIndex method
 *
 * @return void
 */
	public function testApiIndex() {
		$this->testAction('/api/version', array('method' => 'get', 'return' => 'contents'));
		$this->assertEquals($this->contents, '{"version":"0.0.1"}');
	}

}
