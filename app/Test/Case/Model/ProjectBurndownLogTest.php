<?php
App::uses('ProjectBurndownLog', 'Model');

/**
 * ProjectBurndownLog Test Case
 *
 */
class ProjectBurndownLogTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.project_burndown_log',
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
		$this->ProjectBurndownLog = ClassRegistry::init('ProjectBurndownLog');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ProjectBurndownLog);

		parent::tearDown();
	}

	public function testSaveWithChanges() {
		$data = array('ProjectBurndownLog' => array(
			'project_id' => 1,
			'open_task_count' => 3,
			'open_minutes_count' => 1535,
			'open_points_count' => 0,
			'closed_task_count' => 8,
			'closed_minutes_count' => 530,
			'closed_points_count' => 0,
		));

		$saved = $this->ProjectBurndownLog->save($data);
		$data['ProjectBurndownLog']['id'] = $this->ProjectBurndownLog->getLastInsertID();
		$this->assertEquals($data, $saved);

	}

	public function testSaveNoHistoryYet() {

		$data = array('ProjectBurndownLog' => array(
			'project_id' => 1000,
			'open_task_count' => 1,
			'open_minutes_count' => 1,
			'open_points_count' => 1,
			'closed_task_count' => 1,
			'closed_minutes_count' => 1,
			'closed_points_count' => 1,
		));

		$saved = $this->ProjectBurndownLog->save($data);
		$data['ProjectBurndownLog']['id'] = $this->ProjectBurndownLog->getLastInsertID();
		$this->assertEquals($data, $saved);

	}

	public function testSaveNoChanges() {
		$data = array('ProjectBurndownLog' => array(
			'project_id' => 1,
			'open_task_count' => 1,
			'open_minutes_count' => 1,
			'open_points_count' => 1,
			'closed_task_count' => 1,
			'closed_minutes_count' => 1,
			'closed_points_count' => 1,
		));

		$saved = $this->ProjectBurndownLog->save($data);

		$this->assertFalse($saved);

	}
}
