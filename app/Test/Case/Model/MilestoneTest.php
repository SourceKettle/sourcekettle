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
		'app.milestone',
		'app.project',
		'app.task',
		'app.task_type',
		'app.task_status',
		'app.task_priority',
		'app.task_comment',
		'app.task_dependency',
		'app.time',
		'app.user',
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
		'project_id' => '0',
		'subject' => 'Sprint 1',
		'description' => 'Short description here',
		'due' => '2013-01-24',
		'is_open' => true,
		'created' => '2012-06-02 20:05:59',
		'modified' => '2012-06-02 20:05:59',
		'deleted' => '0',
		'deleted_date' => null,
		'percent' => (float) 33.333333333333
		), $milestone['Milestone']);

		$milestone = $this->Milestone->open(2);
		$this->assertEquals(array(
		'id' => '2',
		'project_id' => '0',
		'subject' => 'Sprint 2',
		'description' => '<b>Foo</b>',
		'due' => '2013-01-01',
		'is_open' => false,
		'created' => '2012-08-02 20:05:59',
		'modified' => '2012-09-02 20:05:59',
		'deleted' => '0',
		'deleted_date' => null,
		'percent' => (int) 100
		), $milestone['Milestone']);

		$milestone = $this->Milestone->open(3);
		$this->assertEquals(array(
		'id' => '3',
		'project_id' => '0',
		'subject' => 'Longer <i>subject</i>',
		'description' => 'Short description here',
		'due' => '2013-05-24',
		'is_open' => true,
		'created' => '2012-06-02 20:05:59',
		'modified' => '2012-06-02 20:05:59',
		'deleted' => '0',
		'deleted_date' => null,
		'percent' => (int) 0
		), $milestone['Milestone']);
	}
	
/**
 * testOpenTasksForMilestone method
 *
 * @return void
 */
	public function testOpenTasksForMilestone() {
		$openTasks = $this->Milestone->openTasksForMilestone(1);
		$this->assertEqual(2, count($openTasks));
		foreach ($openTasks as $task) {
			$this->assertEqual('open', $task['TaskStatus']['name']);
		}
		$this->assertEqual(array(
			'id' => '5',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '1',
			'task_priority_id' => '3',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '20h 45m',
			'story_points' => '0',
			'subject' => 'Open Urgent Task 5 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '5',
			'dependenciesComplete' => false
			), $openTasks[0]['Task']);
		$this->assertEqual(array(
			'id' => '2',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '1',
			'task_priority_id' => '1',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '4h 0m',
			'story_points' => '0',
			'subject' => 'Open Minor Task 2 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '2',
			'dependenciesComplete' => false
			), $openTasks[1]['Task']);

	}

/**
 * testInProgressTasksForMilestone method
 *
 * @return void
 */
	public function testInProgressTasksForMilestone() {
		$inProgressTasks = $this->Milestone->inProgressTasksForMilestone(1);
		$this->assertEqual(2, count($inProgressTasks));
		foreach ($inProgressTasks as $task) {
			$this->assertEqual('in progress', $task['TaskStatus']['name']);
		}
		$this->assertEqual(array(
			'id' => '6',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '2',
			'task_priority_id' => '4',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '2h 25m',
			'story_points' => '0',
			'subject' => 'In Progress Blocker Task 7 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '6',
			'dependenciesComplete' => false
			), $inProgressTasks[0]['Task']);
		$this->assertEqual(array(
			'id' => '4',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '2',
			'task_priority_id' => '3',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '2h 25m',
			'story_points' => '0',
			'subject' => 'In progress Urgent Task 4 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '4',
			'dependenciesComplete' => false
			), $inProgressTasks[1]['Task']);
	}

/**
 * testResolvedTasksForMilestone method
 *
 * @return void
 */
	public function testResolvedTasksForMilestone() {
		$resolvedTasks = $this->Milestone->resolvedTasksForMilestone(1);
		$this->assertEqual(1, count($resolvedTasks));
		foreach ($resolvedTasks as $task) {
			$this->assertEqual('resolved', $task['TaskStatus']['name']);
		}
		$this->assertEqual(array(
			'id' => '7',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '3',
			'task_priority_id' => '2',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '2h 25m',
			'story_points' => '0',
			'subject' => 'Resolved Major Task 7 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '7',
			'dependenciesComplete' => false
			), $resolvedTasks[0]['Task']);
	}

/**
 * testClosedTasksForMilestone method
 *
 * @return void
 */
	public function testClosedTasksForMilestone() {
		$closedTasks = $this->Milestone->closedTasksForMilestone(1);
		$this->assertEqual(1, count($closedTasks));
		foreach ($closedTasks as $task) {
			$this->assertEqual('closed', $task['TaskStatus']['name']);
		}
		$this->assertEqual(array(
			'id' => '8',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '4',
			'task_priority_id' => '4',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '2h 25m',
			'story_points' => '0',
			'subject' => 'Closed Blocker Task 8 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '8',
			'dependenciesComplete' => false
			), $closedTasks[0]['Task']);
	}

/**
 * testClosedOrResolvedTasksForMilestone method
 *
 * @return void
 */
	public function testClosedOrResolvedTasksForMilestone() {
		$closedOrResolvedTasks = $this->Milestone->closedOrResolvedTasksForMilestone(1);
		$this->assertEqual(2, count($closedOrResolvedTasks));
		$this->assertEqual(array(
			'id' => '8',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '4',
			'task_priority_id' => '4',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '2h 25m',
			'story_points' => '0',
			'subject' => 'Closed Blocker Task 8 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '8',
			'dependenciesComplete' => false
			), $closedOrResolvedTasks[0]['Task']);
		$this->assertEqual(array(
			'id' => '7',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '3',
			'task_priority_id' => '2',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '2h 25m',
			'story_points' => '0',
			'subject' => 'Resolved Major Task 7 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '7',
			'dependenciesComplete' => false
			), $closedOrResolvedTasks[1]['Task']);
	}

/**
 * testDroppedTasksForMilestone method
 *
 * @return void
 */
	public function testDroppedTasksForMilestone() {
		$droppedTasks = $this->Milestone->droppedTasksForMilestone(1);
		$this->assertEqual(1, count($droppedTasks));
		foreach ($droppedTasks as $task) {
			$this->assertEqual('dropped', $task['TaskStatus']['name']);
		}
		$this->assertEqual(array(
			'id' => '9',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '5',
			'task_priority_id' => '1',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '2h 25m',
			'story_points' => '0',
			'subject' => 'Dropped Minor Task 9 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '9',
			'dependenciesComplete' => false
			), $droppedTasks[0]['Task']);
	}

/**
 * testBlockerTasksForMilestone method
 *
 * @return void
 */
	public function testBlockerTasksForMilestone() {
		$blockerTasks = $this->Milestone->blockerTasksForMilestone(1);
		$this->assertEqual(2, count($blockerTasks));
		foreach ($blockerTasks as $task) {
			$this->assertEqual('blocker', $task['TaskPriority']['name']);
		}
		$this->assertEqual(array(
			'id' => '6',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '2',
			'task_priority_id' => '4',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '2h 25m',
			'story_points' => '0',
			'subject' => 'In Progress Blocker Task 7 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '6',
			'dependenciesComplete' => false
			), $blockerTasks[0]['Task']);
		$this->assertEqual(array(
			'id' => '8',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '4',
			'task_priority_id' => '4',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '2h 25m',
			'story_points' => '0',
			'subject' => 'Closed Blocker Task 8 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '8',
			'dependenciesComplete' => false
			), $blockerTasks[1]['Task']);
	}

/**
 * testUrgentTasksForMilestone method
 *
 * @return void
 */
	public function testUrgentTasksForMilestone() {
		$urgentTasks = $this->Milestone->urgentTasksForMilestone(1);
		$this->assertEqual(2, count($urgentTasks));
		foreach ($urgentTasks as $task) {
			$this->assertEqual('urgent', $task['TaskPriority']['name']);
		}
		$this->assertEqual(array(
			'id' => '4',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '2',
			'task_priority_id' => '3',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '2h 25m',
			'story_points' => '0',
			'subject' => 'In progress Urgent Task 4 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '4',
			'dependenciesComplete' => false
		), $urgentTasks[0]['Task']);
		$this->assertEqual(array(
			'id' => '5',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '1',
			'task_priority_id' => '3',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '20h 45m',
			'story_points' => '0',
			'subject' => 'Open Urgent Task 5 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '5',
			'dependenciesComplete' => false
			), $urgentTasks[1]['Task']);
	}

/**
 * testMajorTasksForMilestone method
 *
 * @return void
 */
	public function testMajorTasksForMilestone() {
		$majorTasks = $this->Milestone->majorTasksForMilestone(1);
		$this->assertEqual(1, count($majorTasks));
		foreach ($majorTasks as $task) {
			$this->assertEqual('major', $task['TaskPriority']['name']);
		}
		$this->assertEqual(array(
			'id' => '7',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '3',
			'task_priority_id' => '2',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '2h 25m',
			'story_points' => '0',
			'subject' => 'Resolved Major Task 7 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '7',
			'dependenciesComplete' => false
			), $majorTasks[0]['Task']);
	}

/**
 * testMinorTasksForMilestone method
 *
 * @return void
 */
	public function testMinorTasksForMilestone() {
		$minorTasks = $this->Milestone->minorTasksForMilestone(1);
		$this->assertEqual(2, count($minorTasks));
		foreach ($minorTasks as $task) {
			$this->assertEqual('minor', $task['TaskPriority']['name']);
		}
		$this->assertEqual(array(
			'id' => '2',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '1',
			'task_priority_id' => '1',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '4h 0m',
			'story_points' => '0',
			'subject' => 'Open Minor Task 2 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '2',
			'dependenciesComplete' => false
			), $minorTasks[0]['Task']);
		$this->assertEqual(array(
			'id' => '9',
			'project_id' => '2',
			'owner_id' => '3',
			'task_type_id' => '1',
			'task_status_id' => '5',
			'task_priority_id' => '1',
			'assignee_id' => '0',
			'milestone_id' => '1',
			'time_estimate' => '2h 25m',
			'story_points' => '0',
			'subject' => 'Dropped Minor Task 9 for milestone 1',
			'description' => 'lorem ipsum dolor sit amet',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'deleted' => '0',
			'deleted_date' => null,
			'public_id' => '9',
			'dependenciesComplete' => false
			), $minorTasks[1]['Task']);
	}

/**
 * testGetOpenMilestones method
 *
 * @return void
 */
	public function testGetOpenMilestones() {
		$this->Milestone->open(2);
		$openMilestones = $this->Milestone->getOpenMilestones(true);
		$this->assertEquals(array(
			1 => 'Sprint 1',
			3 => 'Longer <i>subject</i>'
		), $openMilestones);
		$openMilestones = $this->Milestone->getOpenMilestones(false);
		$this->assertEquals(array(
			1 => '1',
			3 => '3'
		), $openMilestones);
	}

/**
 * testGetClosedMilestones method
 *
 * @return void
 */
	public function testGetClosedMilestones() {
		$this->Milestone->open(2);
		$closedMilestones = $this->Milestone->getClosedMilestones(true);
		$this->assertEquals(array(
			2 => 'Sprint 2'
		), $closedMilestones);
		$closedMilestones = $this->Milestone->getClosedMilestones(false);
		$this->assertEquals(array(
			2 => '2'
		), $closedMilestones);
	}

/**
 * testShiftTasks method
 *
 * @return void
 */
 	// TODO requires currently logged-in user to be set
	public function testShiftTasks() {
		//$this->Milestone->shiftTasks(2, 4);
	}

/**
 * testFetchHistory method
 *
 * @return void
 */
	public function testFetchHistory() {
	}

}
