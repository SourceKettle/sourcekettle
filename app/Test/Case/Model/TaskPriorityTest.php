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

	public function testidToName() {
		$fail    = $this->TaskPriority->idToName(0);
		$minor   = $this->TaskPriority->idToName(1);
		$major   = $this->TaskPriority->idToName(2);
		$urgent  = $this->TaskPriority->idToName(3);
		$blocker = $this->TaskPriority->idToName(4);

		$this->assertEqual(null, $fail,    "Got valid name for invalid status 'fail'");
		$this->assertEqual('minor', $minor,   "Got incorrect name for status 'minor'");
		$this->assertEqual('major', $major,   "Got incorrect name for status 'major'");
		$this->assertEqual('urgent', $urgent,  "Got incorrect name for status 'urgent'");
		$this->assertEqual('blocker', $blocker, "Got incorrect name for status 'blocker'");
	}

	public function testNumberOfItems() {
		$this->assertEqual(4, $this->TaskPriority->find('count'), "Wrong number of task priorities returned (tests need updating?)");
	}

	public function testGetLookupTable() {
		$table = $this->TaskPriority->getLookupTable();
		$this->assertEqual(array(
			1 => array('id' => '1', 'name' => 'minor',   'label' => 'Minor',   'level' => 1, 'icon' => 'download', 'class' => ''),
			2 => array('id' => '2', 'name' => 'major',   'label' => 'Major',   'level' => 2, 'icon' => 'upload', 'class' => ''),
			3 => array('id' => '3', 'name' => 'urgent',  'label' => 'Urgent',  'level' => 3, 'icon' => 'exclamation-sign', 'class' => ''),
			4 => array('id' => '4', 'name' => 'blocker', 'label' => 'Blocker', 'level' => 4, 'icon' => 'ban-circle', 'class' => ''),
		), $table, "Incorrect lookup table returned (tests need updating?)");
	}

}
