<?php
App::uses('GroupCollaboratingTeam', 'Model');

/**
 * GroupCollaboratingTeam Test Case
 *
 */
class GroupCollaboratingTeamTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.group_collaborating_team',
		'app.project_group',
		'app.project',
		'app.repo_type',
		'app.task',
		'app.user',
		'app.collaborator',
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
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
		'app.collaborating_team',
		'app.team',
		'app.teams_user',
		'app.project_groups_project'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->GroupCollaboratingTeam = ClassRegistry::init('GroupCollaboratingTeam');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->GroupCollaboratingTeam);

		parent::tearDown();
	}

}
