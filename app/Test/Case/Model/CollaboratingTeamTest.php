<?php
App::uses('CollaboratingTeam', 'Model');

/**
 * CollaboratingTeam Test Case
 *
 */
class CollaboratingTeamTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.collaborating_team',
		'app.team',
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
		'app.project_group',
		'app.project_groups_project',
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
		'app.teams_user'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CollaboratingTeam = ClassRegistry::init('CollaboratingTeam');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CollaboratingTeam);

		parent::tearDown();
	}

}
