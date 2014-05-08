<?php
App::uses('TaskPriority', 'Model');

/**
 * TaskPriority Test Case
 *
 */
class TaskPriorityTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.task_priority',
		'app.task',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TaskPriority = ClassRegistry::init('TaskPriority');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TaskPriority);

		parent::tearDown();
	}

/**
 * testNameToID method
 *
 * @return void
 */
	public function testNameToID() {
		$fail    = $this->TaskPriority->nameToID('fail');
		$minor   = $this->TaskPriority->nameToID('minor');
		$major   = $this->TaskPriority->nameToID('major');
		$urgent  = $this->TaskPriority->nameToID('urgent');
		$blocker = $this->TaskPriority->nameToID('blocker');

		$this->assertEqual(0, $fail,    "Got valid ID for invalid status 'fail'");
		$this->assertEqual(1, $minor,   "Got incorrect ID for status 'minor'");
		$this->assertEqual(2, $major,   "Got incorrect ID for status 'major'");
		$this->assertEqual(3, $urgent,  "Got incorrect ID for status 'urgent'");
		$this->assertEqual(4, $blocker, "Got incorrect ID for status 'blocker'");
	}
	public function testNumberOfItems() {
		$this->assertEqual(4, $this->TaskPriority->find('count'), "Wrong number of task priorities returned (tests need updating?)");
	}

}
