<?php
App::uses('Milestone', 'Model');

/**
 * Milestone Test Case
 *
 */
class MilestoneTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'core.cake_session',
		'app.setting',
		'app.user_setting',
		'app.project_setting',
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
		'app.milestone_burndown_log',
		'app.project_burndown_log',
		'app.collaborating_team',
		'app.group_collaborating_team',
		'app.team',
		'app.teams_user',
		'app.project_group',
		'app.project_groups_project',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Milestone = ClassRegistry::init('Milestone');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Milestone);
		parent::tearDown();
	}

	public function testRetrieve() {
		$milestone = $this->Milestone->open(1);
		$this->assertEquals(array(
			'id' => '1',
			'project_id' => '2',
			'subject' => 'Sprint 1',
			'description' => 'Short description here',
			'starts' => '2013-01-02',
			'due' => '2013-01-24',
			'is_open' => true,
			'created' => '2012-06-02 20:05:59',
			'modified' => '2012-06-02 20:05:59',
			'deleted' => '0',
			'deleted_date' => null,
		), $milestone['Milestone']);

		$this->assertEquals(array(
			'open' => array('numTasks' => 2, 'points' => 0),
			'in progress' => array('numTasks' => 2, 'points' => 0),
			'resolved' => array('numTasks' => 1, 'points' => 0),
			'closed' => array('numTasks' => 1, 'points' => 0),
			'dropped' => array('numTasks' => 1, 'points' => 0),
		), $milestone['Tasks']);

		$milestone = $this->Milestone->open(2);
		$this->assertEquals(array(
			'id' => '2',
			'project_id' => '2',
			'subject' => 'Sprint 2',
			'description' => '<b>Foo</b>',
			'starts' => '2012-12-02',
			'due' => '2013-01-01',
			'is_open' => false,
			'created' => '2012-08-02 20:05:59',
			'modified' => '2012-09-02 20:05:59',
			'deleted' => '0',
			'deleted_date' => null,
		), $milestone['Milestone']);

		$this->assertEquals(array(
			'open' => array('numTasks' => 0, 'points' => 0),
			'in progress' => array('numTasks' => 1, 'points' => 14),
			'resolved' => array('numTasks' => 1, 'points' => 12),
			'closed' => array('numTasks' => 0, 'points' => 0),
			'dropped' => array('numTasks' => 0, 'points' => 0),
		), $milestone['Tasks']);

		$milestone = $this->Milestone->open(3);
		$this->assertEquals(array(
			'id' => '3',
			'project_id' => '2',
			'subject' => 'Longer <i>subject</i>',
			'description' => 'Short description here',
			'starts' => '2013-05-02',
			'due' => '2013-05-24',
			'is_open' => true,
			'created' => '2012-06-02 20:05:59',
			'modified' => '2012-06-02 20:05:59',
			'deleted' => '0',
			'deleted_date' => null,
		), $milestone['Milestone']);

		$this->assertEquals(array(
			'open' => array('numTasks' => 0, 'points' => 0),
			'in progress' => array('numTasks' => 0, 'points' => 0),
			'resolved' => array('numTasks' => 0, 'points' => 0),
			'closed' => array('numTasks' => 0, 'points' => 0),
			'dropped' => array('numTasks' => 0, 'points' => 0),
		), $milestone['Tasks']);
	}
	
	public function testOpenTasksForMilestone() {

		$openTasks = $this->Milestone->tasksOfStatusForMilestone(1, 'open');

		// Correct number of tasks
		$this->assertEqual(2, count($openTasks));

		// All tasks are open
		foreach ($openTasks as $task) {
			$this->assertEqual('open', $task['TaskStatus']['name']);
		}
		
		// Correct task IDs returned
		$openTasks = array_map(function($a){return $a['Task']['id'];}, $openTasks);
		$this->assertEqual(array(5, 2), $openTasks);

	}

	public function testInProgressTasksForMilestone() {

		$inProgressTasks = $this->Milestone->tasksOfStatusForMilestone(1, 'in progress');
		
		// Correct number of tasks
		$this->assertEqual(2, count($inProgressTasks));
		
		// All tasks are in progress
		foreach ($inProgressTasks as $task) {
			$this->assertEqual('in progress', $task['TaskStatus']['name']);
		}

		// Correct task IDs returned
		$inProgressTasks = array_map(function($a){return $a['Task']['id'];}, $inProgressTasks);
		$this->assertEqual(array(6, 4), $inProgressTasks);
	}

	public function testResolvedTasksForMilestone() {

		$resolvedTasks = $this->Milestone->tasksOfStatusForMilestone(1, 'resolved');

		// Correct number of tasks
		$this->assertEqual(1, count($resolvedTasks));

		// All tasks are resolved
		foreach ($resolvedTasks as $task) {
			$this->assertEqual('resolved', $task['TaskStatus']['name']);
		}

		// Correct task IDs returned
		$resolvedTasks = array_map(function($a){return $a['Task']['id'];}, $resolvedTasks);
		$this->assertEqual(array(7), $resolvedTasks);
	}

	public function testClosedTasksForMilestone() {

		$closedTasks = $this->Milestone->tasksOfStatusForMilestone(1, 'closed');

		// Correct number of tasks
		$this->assertEqual(1, count($closedTasks));

		// All tasks are closed
		foreach ($closedTasks as $task) {
			$this->assertEqual('closed', $task['TaskStatus']['name']);
		}

		// Correct task IDs returned
		$closedTasks = array_map(function($a){return $a['Task']['id'];}, $closedTasks);
		$this->assertEqual(array(8), $closedTasks);
	}

	public function testClosedOrResolvedTasksForMilestone() {

		$closedOrResolvedTasks = $this->Milestone->tasksOfStatusForMilestone(1, array('closed', 'resolved'));

		// Correct number of tasks
		$this->assertEqual(2, count($closedOrResolvedTasks));

		// Correct task IDs returned
		$closedOrResolvedTasks = array_map(function($a){return $a['Task']['id'];}, $closedOrResolvedTasks);
		$this->assertEqual(array(8, 7), $closedOrResolvedTasks);
	}

	public function testDroppedTasksForMilestone() {

		$droppedTasks = $this->Milestone->tasksOfStatusForMilestone(1, 'dropped');

		// Correct number of tasks
		$this->assertEqual(1, count($droppedTasks));

		// All tasks are dropped
		foreach ($droppedTasks as $task) {
			$this->assertEqual('dropped', $task['TaskStatus']['name']);
		}

		// Correct task IDs returned
		$droppedTasks = array_map(function($a){return $a['Task']['id'];}, $droppedTasks);
		$this->assertEqual(array(9), $droppedTasks);
	}

	public function testBlockerTasksForMilestone() {

		$blockerTasks = $this->Milestone->tasksOfPriorityForMilestone(1, 'blocker');

		// Correct number of tasks
		$this->assertEqual(2, count($blockerTasks));

		// All tasks are blockers
		foreach ($blockerTasks as $task) {
			$this->assertEqual('blocker', $task['TaskPriority']['name']);
		}

		// Correct task IDs returned
		$blockerTasks = array_map(function($a){return $a['Task']['id'];}, $blockerTasks);
		$this->assertEqual(array(6, 8), $blockerTasks);
	}

	public function testUrgentTasksForMilestone() {

		$urgentTasks = $this->Milestone->tasksOfPriorityForMilestone(1, 'urgent');

		// Correct number of tasks
		$this->assertEqual(2, count($urgentTasks));

		// All tasks are urgent
		foreach ($urgentTasks as $task) {
			$this->assertEqual('urgent', $task['TaskPriority']['name']);
		}

		// Correct task IDs returned
		$urgentTasks = array_map(function($a){return $a['Task']['id'];}, $urgentTasks);
		$this->assertEqual(array(4, 5), $urgentTasks);
	}

	public function testMajorTasksForMilestone() {

		$majorTasks = $this->Milestone->tasksOfPriorityForMilestone(1, 'major');

		// Correct number of tasks
		$this->assertEqual(1, count($majorTasks));

		// All tasks are major
		foreach ($majorTasks as $task) {
			$this->assertEqual('major', $task['TaskPriority']['name']);
		}

		// Correct task IDs returned
		$majorTasks = array_map(function($a){return $a['Task']['id'];}, $majorTasks);
		$this->assertEqual(array(7), $majorTasks);
	}

	public function testMinorTasksForMilestone() {

		$minorTasks = $this->Milestone->tasksOfPriorityForMilestone(1, 'minor');

		// Correct number of tasks
		$this->assertEqual(2, count($minorTasks));

		// All tasks are minor
		foreach ($minorTasks as $task) {
			$this->assertEqual('minor', $task['TaskPriority']['name']);
		}

		// Correct task IDs returned
		$minorTasks = array_map(function($a){return $a['Task']['id'];}, $minorTasks);
		$this->assertEqual(array(2, 9), $minorTasks);
	}

	public function testGetOpenMilestones() {
		$this->Milestone->Project->id = 2;
		$openMilestones = $this->Milestone->getOpenMilestones();
		$openMilestones = array_map(function($a){return $a['Milestone']['id'];}, $openMilestones);
		asort($openMilestones);
		$this->assertEquals(array(1, 3), $openMilestones);
	}

	public function testGetClosedMilestones() {
		$this->Milestone->Project->id = 2;
		$closedMilestones = $this->Milestone->getClosedMilestones();
		$closedMilestones = array_map(function($a){return $a['Milestone']['id'];}, $closedMilestones);
		asort($closedMilestones);
		$this->assertEquals(array(2), $closedMilestones);
	}


 	public function testShiftTasksNull() {
		$this->assertFalse($this->Milestone->shiftTasks(null));
	}

	public function testShiftTasksNothingToShift() {

		// Load in both milestones' data before moving
		$milestone5_pre = $this->Milestone->findById(5);
		$milestone1_pre = $this->Milestone->findById(1);

		$this->assertEqual($milestone5_pre['Tasks'], array(
			'open'        => array('numTasks' => '0', 'points' => '0'),
			'in progress' => array('numTasks' => '0', 'points' => '0'),
			'resolved'    => array('numTasks' => '0', 'points' => '0'),
			'closed'      => array('numTasks' => '0', 'points' => '0'),
			'dropped'     => array('numTasks' => '0', 'points' => '0'),
		));
		$this->assertEqual($milestone1_pre['Tasks'], array(
			'open'        => array('numTasks' => '2', 'points' => '0'),
			'in progress' => array('numTasks' => '2', 'points' => '0'),
			'resolved'    => array('numTasks' => '1', 'points' => '0'),
			'closed'      => array('numTasks' => '1', 'points' => '0'),
			'dropped'     => array('numTasks' => '1', 'points' => '0'),
		));

		// Shift only the non-resolved/closed tasks and check all is well
		$this->Milestone->shiftTasks(5, 1, false, array('callbacks' => false));

		$milestone5_post = $this->Milestone->findById(5);
		$milestone1_post = $this->Milestone->findById(1);
		$this->assertEqual($milestone5_post['Tasks'], array(
			'open'        => array('numTasks' => '0', 'points' => '0'),
			'in progress' => array('numTasks' => '0', 'points' => '0'),
			'resolved'    => array('numTasks' => '0', 'points' => '0'),
			'closed'      => array('numTasks' => '0', 'points' => '0'),
			'dropped'     => array('numTasks' => '0', 'points' => '0'),
		));
		$this->assertEqual($milestone1_post['Tasks'], array(
			'open'        => array('numTasks' => '2', 'points' => '0'),
			'in progress' => array('numTasks' => '2', 'points' => '0'),
			'resolved'    => array('numTasks' => '1', 'points' => '0'),
			'closed'      => array('numTasks' => '1', 'points' => '0'),
			'dropped'     => array('numTasks' => '1', 'points' => '0'),
		));
	}

	public function testShiftTasksOpenOnly() {

		// Load in both milestones' data before moving
		$milestone1_pre = $this->Milestone->findById(1);
		$milestone3_pre = $this->Milestone->findById(3);

		$this->assertEqual($milestone1_pre['Tasks'], array(
			'open'        => array('numTasks' => '2', 'points' => '0'),
			'in progress' => array('numTasks' => '2', 'points' => '0'),
			'resolved'    => array('numTasks' => '1', 'points' => '0'),
			'closed'      => array('numTasks' => '1', 'points' => '0'),
			'dropped'     => array('numTasks' => '1', 'points' => '0'),
		));
		$this->assertEqual($milestone3_pre['Tasks'], array(
			'open'        => array('numTasks' => '0', 'points' => '0'),
			'in progress' => array('numTasks' => '0', 'points' => '0'),
			'resolved'    => array('numTasks' => '0', 'points' => '0'),
			'closed'      => array('numTasks' => '0', 'points' => '0'),
			'dropped'     => array('numTasks' => '0', 'points' => '0'),
		));

		// Shift only the non-resolved/closed tasks and check all is well
		$this->Milestone->shiftTasks(1, 3, false, array('callbacks' => false));

		$milestone1_post = $this->Milestone->findById(1);
		$milestone3_post = $this->Milestone->findById(3);
		$this->assertEqual($milestone1_post['Tasks'], array(
			'open'        => array('numTasks' => '0', 'points' => '0'),
			'in progress' => array('numTasks' => '0', 'points' => '0'),
			'resolved'    => array('numTasks' => '1', 'points' => '0'),
			'closed'      => array('numTasks' => '1', 'points' => '0'),
			'dropped'     => array('numTasks' => '0', 'points' => '0'),
		));
		$this->assertEqual($milestone3_post['Tasks'], array(
			'open'        => array('numTasks' => '2', 'points' => '0'),
			'in progress' => array('numTasks' => '2', 'points' => '0'),
			'resolved'    => array('numTasks' => '0', 'points' => '0'),
			'closed'      => array('numTasks' => '0', 'points' => '0'),
			'dropped'     => array('numTasks' => '1', 'points' => '0'),
		));
	}

	public function testShiftTasksAll() {

		// Load in both milestones' data before moving
		$milestone1_pre = $this->Milestone->findById(1);
		$milestone3_pre = $this->Milestone->findById(3);

		$this->assertEqual($milestone1_pre['Tasks'], array(
			'open'        => array('numTasks' => '2', 'points' => '0'),
			'in progress' => array('numTasks' => '2', 'points' => '0'),
			'resolved'    => array('numTasks' => '1', 'points' => '0'),
			'closed'      => array('numTasks' => '1', 'points' => '0'),
			'dropped'     => array('numTasks' => '1', 'points' => '0'),
		));
		$this->assertEqual($milestone3_pre['Tasks'], array(
			'open'        => array('numTasks' => '0', 'points' => '0'),
			'in progress' => array('numTasks' => '0', 'points' => '0'),
			'resolved'    => array('numTasks' => '0', 'points' => '0'),
			'closed'      => array('numTasks' => '0', 'points' => '0'),
			'dropped'     => array('numTasks' => '0', 'points' => '0'),
		));
		$this->Milestone->shiftTasks(1, 3, true, array('callbacks' => false));

		$milestone1_post = $this->Milestone->findById(1);
		$milestone3_post = $this->Milestone->findById(3);
		$this->assertEqual($milestone1_post['Tasks'], array(
			'open'        => array('numTasks' => '0', 'points' => '0'),
			'in progress' => array('numTasks' => '0', 'points' => '0'),
			'resolved'    => array('numTasks' => '0', 'points' => '0'),
			'closed'      => array('numTasks' => '0', 'points' => '0'),
			'dropped'     => array('numTasks' => '0', 'points' => '0'),
		));
		$this->assertEqual($milestone3_post['Tasks'], array(
			'open'        => array('numTasks' => '2', 'points' => '0'),
			'in progress' => array('numTasks' => '2', 'points' => '0'),
			'resolved'    => array('numTasks' => '1', 'points' => '0'),
			'closed'      => array('numTasks' => '1', 'points' => '0'),
			'dropped'     => array('numTasks' => '1', 'points' => '0'),
		));
	}


	public function testFetchHistory() {
		$history = $this->Milestone->fetchHistory(2);
		$this->assertEquals($history, array(array(
			'modified' => '2014-07-23 15:01:12',
			'Type' => 'Milestone',
			'Project' => array(
				'id' => '2',
				'name' => 'public'
			),
			'Actioner' => array(
				'id' => '2',
				'name' => 'Mrs Smith',
				'email' => 'mrs.smith@example.com',
				'exists' => true
			),
			'Subject' => array(
				'id' => '3',
				'title' => 'Longer <i>subject</i>',
				'exists' => true
			),
			'Change' => array(
				'field' => 'is_open',
				'field_old' => '1',
				'field_new' => '0'
			)
		)), "Incorrect history data returned");
	}

	public function testDelete() {
		$milestone_pre = $this->Milestone->findById(1);
		$this->assertNotEquals($milestone_pre, array(), "Empty milestone returned before delete happened");

		$this->Milestone->id = 1;
		$this->Milestone->delete();
		$milestone_post = $this->Milestone->findById(1);

		$this->assertEquals($milestone_post, array(), "Milestone was not deleted");
	}

	public function testListMilestoneOptions() {

		$this->Milestone->Project->id = 2;
		$options = $this->Milestone->listMilestoneOptions();
		$this->assertEquals(array(
			__('No assigned milestone'),
			__('Open') => array(
				1 => 'Sprint 1',
				3 => 'Longer <i>subject</i>',
			),
			__('Closed') => array(
				2 => 'Sprint 2',
			),
		), $options);
	}

	public function testFetchBurndownLog() {
		
		// Pull out all logs for milestone 2 from before and after we actually have logs
		$log = $this->Milestone->fetchBurndownLog(2, new DateTime('2012-12-30'), new DateTime('2013-01-12'));
		$this->assertEquals(array(
			'2012-12-29' => array(
				'open'   => array('points' => 0, 'tasks' => 0, 'minutes' => 0,),
				'closed' => array('points' => 0, 'tasks' => 0, 'minutes' => 0,),
			),
			'2012-12-30' => array(
				'open'   => array('points' => 0, 'tasks' => 0, 'minutes' => 0,),
				'closed' => array('points' => 0, 'tasks' => 0, 'minutes' => 0,),
			),
			'2012-12-31' => array(
				'open'   => array('points' => 0, 'tasks' => 0, 'minutes' => 0,),
				'closed' => array('points' => 0, 'tasks' => 0, 'minutes' => 0,),
			),
			'2013-01-01' => array(
				'open'   => array('points' => 0, 'tasks' => 0, 'minutes' => 0,),
				'closed' => array('points' => 0, 'tasks' => 0, 'minutes' => 0,),
			),
			'2013-01-02' => array(
				'open'   => array('points' => 0, 'tasks' => 0, 'minutes' => 0,),
				'closed' => array('points' => 0, 'tasks' => 0, 'minutes' => 0,),
			),
			'2013-01-03' => array(
				'open'   => array('points' => 0, 'tasks' => 0, 'minutes' => 0,),
				'closed' => array('points' => 0, 'tasks' => 0, 'minutes' => 0,),
			),
			'2013-01-04' => array(
				'open'   => array('points' => 2, 'tasks' => 2, 'minutes' => 2,),
				'closed' => array('points' => 2, 'tasks' => 2, 'minutes' => 2,),
			),
			'2013-01-05' => array(
				'open'   => array('points' => 4, 'tasks' => 4, 'minutes' => 4,),
				'closed' => array('points' => 4, 'tasks' => 4, 'minutes' => 4,),
			),
			'2013-01-06' => array(
				'open'   => array('points' => 5, 'tasks' => 5, 'minutes' => 5,),
				'closed' => array('points' => 5, 'tasks' => 5, 'minutes' => 5,),
			),
			'2013-01-07' => array(
				'open'   => array('points' => 6, 'tasks' => 6, 'minutes' => 6,),
				'closed' => array('points' => 6, 'tasks' => 6, 'minutes' => 6,),
			),
			'2013-01-08' => array(
				'open'   => array('points' => 7, 'tasks' => 7, 'minutes' => 7,),
				'closed' => array('points' => 7, 'tasks' => 7, 'minutes' => 7,),
			),
			'2013-01-09' => array(
				'open'   => array('points' => 8, 'tasks' => 8, 'minutes' => 8,),
				'closed' => array('points' => 8, 'tasks' => 8, 'minutes' => 8,),
			),
			'2013-01-10' => array(
				'open'   => array('points' => 9, 'tasks' => 9, 'minutes' => 9,),
				'closed' => array('points' => 9, 'tasks' => 9, 'minutes' => 9,),
			),
			'2013-01-11' => array(
				'open'   => array('points' => 10, 'tasks' => 10, 'minutes' => 10,),
				'closed' => array('points' => 10, 'tasks' => 10, 'minutes' => 10,),
			),
			'2013-01-12' => array(
				'open'   => array('points' => 10, 'tasks' => 10, 'minutes' => 10,),
				'closed' => array('points' => 10, 'tasks' => 10, 'minutes' => 10,),
			),
			'2013-01-13' => array(
				'open'   => array('points' => 10, 'tasks' => 10, 'minutes' => 10,),
				'closed' => array('points' => 10, 'tasks' => 10, 'minutes' => 10,),
			),
		), $log);

		// Now pull out a slice of the logs
		$log = $this->Milestone->fetchBurndownLog(2, new DateTime('2013-01-05'), new DateTime('2013-01-08'));
		$this->assertEquals(array(
			'2013-01-04' => array(
				'open'   => array('points' => 2, 'tasks' => 2, 'minutes' => 2,),
				'closed' => array('points' => 2, 'tasks' => 2, 'minutes' => 2,),
			),
			'2013-01-05' => array(
				'open'   => array('points' => 4, 'tasks' => 4, 'minutes' => 4,),
				'closed' => array('points' => 4, 'tasks' => 4, 'minutes' => 4,),
			),
			'2013-01-06' => array(
				'open'   => array('points' => 5, 'tasks' => 5, 'minutes' => 5,),
				'closed' => array('points' => 5, 'tasks' => 5, 'minutes' => 5,),
			),
			'2013-01-07' => array(
				'open'   => array('points' => 6, 'tasks' => 6, 'minutes' => 6,),
				'closed' => array('points' => 6, 'tasks' => 6, 'minutes' => 6,),
			),
			'2013-01-08' => array(
				'open'   => array('points' => 7, 'tasks' => 7, 'minutes' => 7,),
				'closed' => array('points' => 7, 'tasks' => 7, 'minutes' => 7,),
			),
			// TODO I'm not sure this is quite right?
			'2013-01-09' => array(
				'open'   => array('points' => 7, 'tasks' => 7, 'minutes' => 7,),
				'closed' => array('points' => 7, 'tasks' => 7, 'minutes' => 7,),
			),
		), $log);

	}

}
