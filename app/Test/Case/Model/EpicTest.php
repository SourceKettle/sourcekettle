<?php
App::uses('Epic', 'Model');

/**
 * Epic Test Case
 *
 */
class EpicTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.epic',
		'app.project',
		'app.repo_type',
		'app.task',
		'app.user',
		'app.collaborator',
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
		'app.team',
		'app.collaborating_team',
		'app.group_collaborating_team',
		'app.project_group',
		'app.project_groups_project',
		'app.teams_user',
		'app.task_type',
		'app.task_status',
		'app.task_priority',
		'app.milestone',
		'app.milestone_burndown_log',
		'app.story',
		'app.task_comment',
		'app.time',
		'app.task_dependency',
		'app.source',
		'app.blob',
		'app.commit',
		'app.project_history',
		'app.attachment',
		'app.project_burndown_log',
		'app.project_setting'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Epic = ClassRegistry::init('Epic');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Epic);

		parent::tearDown();
	}

}
