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
	public function testIdToName() {
		$fail          = $this->TaskType->idToName(0);
		$bug           = $this->TaskType->idToName(1);
		$duplicate     = $this->TaskType->idToName(2);
		$enhancement   = $this->TaskType->idToName(3);
		$invalid       = $this->TaskType->idToName(4);
		$question      = $this->TaskType->idToName(5);
		$wontfix       = $this->TaskType->idToName(6);
		$documentation = $this->TaskType->idToName(7);
		$meeting       = $this->TaskType->idToName(8);
		$maintenance   = $this->TaskType->idToName(9);
		$testing       = $this->TaskType->idToName(10);

		$this->assertEqual(null, $fail,          "Got valid name for invalid status 'fail'");
		$this->assertEqual('bug', $bug,           "Got incorrect name for status 'bug'");
		$this->assertEqual('duplicate', $duplicate,     "Got incorrect name for status 'duplicate'");
		$this->assertEqual('enhancement', $enhancement,   "Got incorrect name for status 'enhancement'");
		$this->assertEqual('invalid', $invalid,       "Got incorrect name for status 'invalid'");
		$this->assertEqual('question', $question,      "Got incorrect name for status 'question'");
		$this->assertEqual('wontfix', $wontfix,       "Got incorrect name for status 'wontfix'");
		$this->assertEqual('documentation', $documentation, "Got incorrect name for status 'documentation'");
		$this->assertEqual('meeting', $meeting,       "Got incorrect name for status 'meeting'");
		$this->assertEqual('maintenance', $maintenance, "Got incorrect name for status 'maintenance'");
		$this->assertEqual('testing', $testing,       "Got incorrect name for status 'testing'");
	}

	public function testNameToId() {
		$fail          = $this->TaskType->nameToID('fail');
		$bug           = $this->TaskType->nameToID('bug');
		$duplicate     = $this->TaskType->nameToID('duplicate');
		$enhancement   = $this->TaskType->nameToID('enhancement');
		$invalid       = $this->TaskType->nameToID('invalid');
		$question      = $this->TaskType->nameToID('question');
		$wontfix       = $this->TaskType->nameToID('wontfix');
		$documentation = $this->TaskType->nameToID('documentation');
		$meeting       = $this->TaskType->nameToID('meeting');
		$maintenance   = $this->TaskType->nameToID('maintenance');
		$testing       = $this->TaskType->nameToID('testing');

		$this->assertEqual(0, $fail,          "Got valid ID for invalid status 'fail'");
		$this->assertEqual(1, $bug,           "Got incorrect ID for status 'bug'");
		$this->assertEqual(2, $duplicate,     "Got incorrect ID for status 'duplicate'");
		$this->assertEqual(3, $enhancement,   "Got incorrect ID for status 'enhancement'");
		$this->assertEqual(4, $invalid,       "Got incorrect ID for status 'invalid'");
		$this->assertEqual(5, $question,      "Got incorrect ID for status 'question'");
		$this->assertEqual(6, $wontfix,       "Got incorrect ID for status 'wontfix'");
		$this->assertEqual(7, $documentation, "Got incorrect ID for status 'documentation'");
		$this->assertEqual(8, $meeting,       "Got incorrect ID for status 'meeting'");
		$this->assertEqual(9, $maintenance,   "Got incorrect ID for status 'maintenance'");
		$this->assertEqual(10, $testing,       "Got incorrect ID for status 'testing'");
	}

	public function testNumberOfItems() {
		$this->assertEqual(10, $this->TaskType->find('count'), "Wrong number of task types returned (tests need updating?)");
	}

	public function testGetLookupTable() {
		$table = $this->TaskType->getLookupTable();
		$this->assertEqual(array(
			1 => array('id' => 1, 'name' => 'bug',           'label' => 'Bug',           'icon' => '', 'class' => 'important'),
			2 => array('id' => 2, 'name' => 'duplicate',     'label' => 'Duplicate',     'icon' => '', 'class' => 'warning'),
			3 => array('id' => 3, 'name' => 'enhancement',   'label' => 'Enhancement',   'icon' => '', 'class' => 'success'),
			4 => array('id' => 4, 'name' => 'invalid',       'label' => 'Invalid',       'icon' => '', 'class' => ''),
			5 => array('id' => 5, 'name' => 'question',      'label' => 'Question',      'icon' => '', 'class' => 'info'),
			6 => array('id' => 6, 'name' => 'wontfix',       'label' => 'Won\'t Fix',    'icon' => '', 'class' => 'inverse'),
			7 => array('id' => 7, 'name' => 'documentation', 'label' => 'Documentation', 'icon' => '', 'class' => 'info'),
			8 => array('id' => 8, 'name' => 'meeting',       'label' => 'Meeting',       'icon' => '', 'class' => 'info'),
			9 => array('id' => 9, 'name' => 'maintenance',   'label' => 'Maintenance Work', 'icon' => '', 'class' => 'warning'),
			10 => array('id' => 10, 'name' => 'testing',      'label' => 'Testing',         'icon' => '', 'class' => 'success'),
		), $table, "Incorrect lookup table returned (tests need updating?)");
	}
}
