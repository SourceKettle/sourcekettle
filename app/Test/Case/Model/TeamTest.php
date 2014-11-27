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

	public function testIsMember() {
		$this->assertTrue($this->Team->isMember(1, 13));
		$this->assertTrue($this->Team->isMember(1, 16));
		$this->assertTrue($this->Team->isMember(1, 17));
		$this->assertTrue($this->Team->isMember(1, 19));
		$this->assertTrue($this->Team->isMember(2, 14));
		$this->assertTrue($this->Team->isMember(2, 17));
		$this->assertTrue($this->Team->isMember(2, 18));
		$this->assertTrue($this->Team->isMember(2, 19));
		$this->assertTrue($this->Team->isMember(3, 15));
		$this->assertTrue($this->Team->isMember(3, 16));
		$this->assertTrue($this->Team->isMember(3, 18));
		$this->assertTrue($this->Team->isMember(3, 19));
		$this->assertTrue($this->Team->isMember(4, 20));
		$this->assertTrue($this->Team->isMember(4, 21));
		$this->assertTrue($this->Team->isMember(5, 2));


		$this->assertFalse($this->Team->isMember(1, 14));
		$this->assertFalse($this->Team->isMember(2, 13));
		$this->assertFalse($this->Team->isMember(3, 17));
		$this->assertFalse($this->Team->isMember(4, 18));
		$this->assertFalse($this->Team->isMember(5, 1));
	}

}
