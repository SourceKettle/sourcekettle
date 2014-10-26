<?php
App::uses('MilestoneBurndownLog', 'Model');

/**
 * MilestoneBurndownLog Test Case
 *
 */
class MilestoneBurndownLogTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.milestone_burndown_log',
		'app.milestone',
		'app.project',
		'app.repo_type',
		'app.collaborator',
		'app.user',
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
		'app.task',
		'app.task_type',
		'app.task_status',
		'app.task_priority',
		'app.task_comment',
		'app.time',
		'app.task_dependency',
		'app.source',
		'app.blob',
		'app.commit',
		'app.project_history',
		'app.attachment'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MilestoneBurndownLog = ClassRegistry::init('MilestoneBurndownLog');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MilestoneBurndownLog);

		parent::tearDown();
	}

}
