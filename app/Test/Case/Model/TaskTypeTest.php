<?php
App::uses('TaskType', 'Model');

/**
 * TaskType Test Case
 *
 */
class TaskTypeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.task_type',
		'app.task',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TaskType = ClassRegistry::init('TaskType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TaskType);

		parent::tearDown();
	}

/**
 * testNameToID method
 *
 * @return void
 */
	public function testNameToID() {
		$fail          = $this->TaskType->nameToID('fail');
		$bug           = $this->TaskType->nameToID('bug');
		$duplicate     = $this->TaskType->nameToID('duplicate');
		$enhancement   = $this->TaskType->nameToID('enhancement');
		$invalid       = $this->TaskType->nameToID('invalid');
		$question      = $this->TaskType->nameToID('question');
		$wontfix       = $this->TaskType->nameToID('wontfix');
		$documentation = $this->TaskType->nameToID('documentation');
		$meeting       = $this->TaskType->nameToID('meeting');

		$this->assertEqual(0, $fail,          "Got valid ID for invalid status 'fail'");
		$this->assertEqual(1, $bug,           "Got incorrect ID for status 'bug'");
		$this->assertEqual(2, $duplicate,     "Got incorrect ID for status 'duplicate'");
		$this->assertEqual(3, $enhancement,   "Got incorrect ID for status 'enhancement'");
		$this->assertEqual(4, $invalid,       "Got incorrect ID for status 'invalid'");
		$this->assertEqual(5, $question,      "Got incorrect ID for status 'question'");
		$this->assertEqual(6, $wontfix,       "Got incorrect ID for status 'wontfix'");
		$this->assertEqual(7, $documentation, "Got incorrect ID for status 'documentation'");
		$this->assertEqual(8, $meeting,       "Got incorrect ID for status 'meeting'");
	}

	public function testNumberOfItems() {
		$this->assertEqual(8, $this->TaskType->find('count'), "Wrong number of task types returned (tests need updating?)");
	}
}
