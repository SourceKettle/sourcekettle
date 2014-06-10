<?php
App::uses('Task', 'Model');

/**
 * Task Test Case
 *
 */
class TaskTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.task',
		'app.project',
		'app.user',
		'app.milestone',
		'app.time',
		'app.task_type',
		'app.task_status',
		'app.task_priority',
		'app.task_comment',
		'app.task_dependency'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Task = ClassRegistry::init('Task');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Task);

		parent::tearDown();
	}

/**
 * testIsAssignee method
 *
 * @return void
 */
	public function testIsAssignee() {
		# TODO fake logged-in user
	}

/**
 * testIsOpen method
 *
 * @return void
 */
	public function testIsOpen() {
		$this->Task->id = 1;
		$this->Task->read();
		$this->assertEquals(false, $this->Task->isOpen());
		$this->Task->id = 2;
		$this->Task->read();
		$this->assertEquals(true, $this->Task->isOpen());
	}

/**
 * testIsInProgress method
 *
 * @return void
 */
	public function testIsInProgress() {
		$this->Task->id = 1;
		$this->Task->read();
		$this->assertEquals(false, $this->Task->isInProgress());
		$this->Task->id = 3;
		$this->Task->read();
		$this->assertEquals(true, $this->Task->isInProgress());
	}

/**
 * testFetchHistory method
 *
 * @return void
 */
	/*public function testFetchHistory() {
	}*/

/**
 * testGetTitleForHistory method
 *
 * @return void
 */
	public function testGetTitleForHistory() {
		$this->assertEquals(null, $this->Task->getTitleForHistory());
		$this->Task->id = 1;
		$this->Task->read();
		$this->assertEquals('#1', $this->Task->getTitleForHistory());
		$this->assertEquals('#2', $this->Task->getTitleForHistory(2));
	}

/**
 * testFetchLoggableTasks method
 *
 * @return void
 */
	public function testFetchLoggableTasks() {
	}

}
