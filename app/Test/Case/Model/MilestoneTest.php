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
	
/**
 * testOpenTasksForMilestone method
 *
 * @return void
 */
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

/**
 * testInProgressTasksForMilestone method
 *
 * @return void
 */
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

/**
 * testResolvedTasksForMilestone method
 *
 * @return void
 */
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

/**
 * testClosedTasksForMilestone method
 *
 * @return void
 */
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

/**
 * testClosedOrResolvedTasksForMilestone method
 *
 * @return void
 */
	public function testClosedOrResolvedTasksForMilestone() {

		$closedOrResolvedTasks = $this->Milestone->tasksOfStatusForMilestone(1, array('closed', 'resolved'));

		// Correct number of tasks
		$this->assertEqual(2, count($closedOrResolvedTasks));

		// Correct task IDs returned
		$closedOrResolvedTasks = array_map(function($a){return $a['Task']['id'];}, $closedOrResolvedTasks);
		$this->assertEqual(array(8, 7), $closedOrResolvedTasks);
	}

/**
 * testDroppedTasksForMilestone method
 *
 * @return void
 */
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

/**
 * testBlockerTasksForMilestone method
 *
 * @return void
 */
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

/**
 * testUrgentTasksForMilestone method
 *
 * @return void
 */
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

/**
 * testMajorTasksForMilestone method
 *
 * @return void
 */
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

/**
 * testMinorTasksForMilestone method
 *
 * @return void
 */
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

/**
 * testGetOpenMilestones method
 *
 * @return void
 */
	public function testGetOpenMilestones() {
		$this->Milestone->Project->id = 2;
		$openMilestones = $this->Milestone->getOpenMilestones();
		$openMilestones = array_map(function($a){return $a['Milestone']['id'];}, $openMilestones);
		asort($openMilestones);
		$this->assertEquals(array(1, 3), $openMilestones);
	}

/**
 * testGetClosedMilestones method
 *
 * @return void
 */
	public function testGetClosedMilestones() {
		$this->Milestone->Project->id = 2;
		$closedMilestones = $this->Milestone->getClosedMilestones();
		$closedMilestones = array_map(function($a){return $a['Milestone']['id'];}, $closedMilestones);
		asort($closedMilestones);
		$this->assertEquals(array(2), $closedMilestones);
	}

/**
 * testShiftTasks method
 *
 * @return void
 */

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


/**
 * testFetchHistory method
 *
 * @return void
 */
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

}
