<?php
App::uses('Team', 'Model');

/**
 * Team Test Case
 *
 */
class TeamTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.team',
		'app.collaborating_team',
		'app.project',
		'app.repo_type',
		'app.task',
		'app.user',
		'app.collaborator',
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
		'app.teams_user',
		'app.task_type',
		'app.task_status',
		'app.task_priority',
		'app.milestone',
		'app.milestone_burndown_log',
		'app.task_comment',
		'app.time',
		'app.task_dependency',
		'app.source',
		'app.project_history',
		'app.attachment',
		'app.project_burndown_log',
		'app.project_group',
		'app.group_collaborating_team',
		'app.project_groups_project'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Team = ClassRegistry::init('Team');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Team);

		parent::tearDown();
	}

/**
 * testTasksOfStatusForTeam method
 *
 * @return void
 */
	private function crunchTaskList($tasks) {
		return array_map(function($a){return $a['Task']['id'];}, $tasks);
	}

	public function testTasksOfStatusForTeam() {
		$open = $this->crunchTaskList($this->Team->tasksOfStatusForTeam(5, 'open'));
		$inProgress = $this->crunchTaskList($this->Team->tasksOfStatusForTeam(5, 'in progress'));
		$resolved = $this->crunchTaskList($this->Team->tasksOfStatusForTeam(5, 'resolved'));
		$closed = $this->crunchTaskList($this->Team->tasksOfStatusForTeam(5, 'closed'));
		$dropped = $this->crunchTaskList($this->Team->tasksOfStatusForTeam(5, 'dropped'));
		$done = $this->crunchTaskList($this->Team->tasksOfStatusForTeam(5, array('resolved', 'closed')));

		$this->assertEquals(array(), $open);
		$this->assertEquals(array(4, 10, 11, 12, 13), $inProgress);
		$this->assertEquals(array(1), $resolved);
		$this->assertEquals(array(), $closed);
		$this->assertEquals(array(), $dropped);
		$this->assertEquals(array(1), $done);
	}

}
