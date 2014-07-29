<?php
App::uses('TimesController', 'Controller');

/**
 * TimesController Test Case
 *
 */
class TimesControllerTestCase extends CakeTestCase {

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
		parent::setUp("Times");
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Times);

		parent::tearDown();
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
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
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {
	}
/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {
	}

}
