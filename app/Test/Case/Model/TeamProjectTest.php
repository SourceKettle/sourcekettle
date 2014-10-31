<?php
App::uses('TeamProject', 'Model');

/**
 * TeamProject Test Case
 *
 */
class TeamProjectTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.team_project',
		'app.team',
		'app.team_member',
		'app.user',
		'app.collaborator',
		'app.project',
		'app.repo_type',
		'app.task',
		'app.task_type',
		'app.task_status',
		'app.task_priority',
		'app.milestone',
		'app.milestone_burndown_log',
		'app.task_comment',
		'app.time',
		'app.task_dependency',
		'app.source',
		'app.blob',
		'app.commit',
		'app.project_history',
		'app.attachment',
		'app.project_burndown_log',
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TeamProject = ClassRegistry::init('TeamProject');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TeamProject);

		parent::tearDown();
	}

}
