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

/**
 * testOpenTasksForMilestone method
 *
 * @return void
 */
	public function testOpenTasksForMilestone() {
	}

/**
 * testInProgressTasksForMilestone method
 *
 * @return void
 */
	public function testInProgressTasksForMilestone() {
	}

/**
 * testResolvedTasksForMilestone method
 *
 * @return void
 */
	public function testResolvedTasksForMilestone() {
	}

/**
 * testClosedTasksForMilestone method
 *
 * @return void
 */
	public function testClosedTasksForMilestone() {
	}

/**
 * testClosedOrResolvedTasksForMilestone method
 *
 * @return void
 */
	public function testClosedOrResolvedTasksForMilestone() {
	}

/**
 * testDroppedTasksForMilestone method
 *
 * @return void
 */
	public function testDroppedTasksForMilestone() {
	}

/**
 * testTasksOfStatusForMilestone method
 *
 * @return void
 */
	public function testTasksOfStatusForMilestone() {
	}

/**
 * testBlockerTasksForMilestone method
 *
 * @return void
 */
	public function testBlockerTasksForMilestone() {
	}

/**
 * testUrgentTasksForMilestone method
 *
 * @return void
 */
	public function testUrgentTasksForMilestone() {
	}

/**
 * testMajorTasksForMilestone method
 *
 * @return void
 */
	public function testMajorTasksForMilestone() {
	}

/**
 * testMinorTasksForMilestone method
 *
 * @return void
 */
	public function testMinorTasksForMilestone() {
	}

/**
 * testTasksOfPriorityForMilestone method
 *
 * @return void
 */
	public function testTasksOfPriorityForMilestone() {
	}

/**
 * testGetOpenMilestones method
 *
 * @return void
 */
	public function testGetOpenMilestones() {
	}

/**
 * testGetClosedMilestones method
 *
 * @return void
 */
	public function testGetClosedMilestones() {
	}

/**
 * testShiftTasks method
 *
 * @return void
 */
	public function testShiftTasks() {
	}

/**
 * testFetchHistory method
 *
 * @return void
 */
	public function testFetchHistory() {
	}

}
