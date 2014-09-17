<?php
App::uses('TaskStatus', 'Model');

/**
 * TaskStatus Test Case
 *
 */
class TaskStatusTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.task_status',
		'app.task',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TaskStatus = ClassRegistry::init('TaskStatus');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TaskStatus);

		parent::tearDown();
	}

/**
 * testNameToID method
 *
 * @return void
 */
	public function testIDToName() {
		$fail        = $this->TaskStatus->idToName(0);
		$open        = $this->TaskStatus->idToName(1);
		$in_progress = $this->TaskStatus->idToName(2);
		$resolved    = $this->TaskStatus->idToName(3);
		$closed      = $this->TaskStatus->idToName(4);
		$dropped     = $this->TaskStatus->idToName(5);

		$this->assertEqual(null, $fail,    "Got valid status for invalid ID 0");
		$this->assertEqual('open', $open,   "Got incorrect status for ID 1");
		$this->assertEqual('in progress', $in_progress,   "Got incorrect status for ID 2");
		$this->assertEqual('resolved', $resolved,  "Got incorrect status for ID 3");
		$this->assertEqual('closed', $closed, "Got incorrect status for ID 4");
		$this->assertEqual('dropped', $dropped, "Got incorrect status for ID 5");
	}

	public function testNameToID() {
		$fail        = $this->TaskStatus->nameToID('fail');
		$open        = $this->TaskStatus->nameToID('open');
		$in_progress = $this->TaskStatus->nameToID('in progress');
		$resolved    = $this->TaskStatus->nameToID('resolved');
		$closed      = $this->TaskStatus->nameToID('closed');
		$dropped     = $this->TaskStatus->nameToID('dropped');

		$this->assertEqual(0, $fail,    "Got valid ID for invalid status 'fail'");
		$this->assertEqual(1, $open,   "Got incorrect ID for status 'open'");
		$this->assertEqual(2, $in_progress,   "Got incorrect ID for status 'in progress'");
		$this->assertEqual(3, $resolved,  "Got incorrect ID for status 'resolved'");
		$this->assertEqual(4, $closed, "Got incorrect ID for status 'closed'");
		$this->assertEqual(5, $dropped, "Got incorrect ID for status 'dropped'");
	}

	public function testGetCompletedIdList() {
		$this->assertEquals(array(3, 4), $this->TaskStatus->getCompletedIdList());
	}

	public function testNumberOfItems() {
		$this->assertEqual(5, $this->TaskStatus->find('count'), "Wrong number of task statuses returned (tests need updating?)");
	}

	public function testGetLookupTable() {
		$table = $this->TaskStatus->getLookupTable();
		$this->assertEqual(array(
			1 => array('id' => 1, 'name' => 'open',        'label' => 'Open',        'icon' => '', 'class' => 'important'),
			2 => array('id' => 2, 'name' => 'in progress', 'label' => 'In Progress', 'icon' => '', 'class' => 'warning'),
			3 => array('id' => 3, 'name' => 'resolved',    'label' => 'Resolved',    'icon' => '', 'class' => 'success'),
			4 => array('id' => 4, 'name' => 'closed',      'label' => 'Closed',      'icon' => '', 'class' => 'info'),
			5 => array('id' => 5, 'name' => 'dropped',     'label' => 'Dropped',     'icon' => '', 'class' => ''),
		), $table, "Incorrect lookup table returned (tests need updating?)");
	}

}
