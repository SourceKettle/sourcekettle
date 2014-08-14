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
		), $table, "Incorrect lookup table returned (tests need updating?)");
	}
}
