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
		'app.milestone_burndown_log',
		'app.project_burndown_log',
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


	public function testFindTaskWithDependencies() {
		$details = $this->Task->findById(2);
		// Ignore the modified/created dates on TaskStatus et al, we don't care...
		unset($details['Task']['created']);
		unset($details['Task']['modified']);

		$this->assertEquals('2', $details['Task']['id']);
		$this->assertEquals('0', $details['Task']['dependenciesComplete']);
	}

	public function testIsAssignee() {
		$this->Task->id = 1;
		$this->assertTrue($this->Task->isAssignee(2));
		$this->assertFalse($this->Task->isAssignee(1));
		$this->assertFalse($this->Task->isAssignee(4));
	}

	public function testIsOpen() {
		$this->Task->id = 1;
		$this->Task->read();
		$this->assertEquals(false, $this->Task->isOpen());
		$this->Task->id = 2;
		$this->Task->read();
		$this->assertEquals(true, $this->Task->isOpen());
	}

	public function testIsInProgress() {
		$this->Task->id = 1;
		$this->Task->read();
		$this->assertEquals(false, $this->Task->isInProgress());
		$this->Task->id = 3;
		$this->Task->read();
		$this->assertEquals(true, $this->Task->isInProgress());
	}

	public function testFetchHistory() {
		$this->Task->Project->id = 2;
		$history = $this->Task->fetchHistory(2, 10, 0, 0);
		$this->assertEquals($history, array(), "Incorrect history data returned");
	}

	public function testGetTitleForHistory() {
		$this->assertEquals(null, $this->Task->getTitleForHistory());
		$this->Task->id = 1;
		$this->Task->read();
		$this->assertEquals('#1 (Resolved Major Task 1 for milestone 2)', $this->Task->getTitleForHistory());
		$this->assertEquals('#2 (Open Minor Task 2 for milestone 1)', $this->Task->getTitleForHistory(2));
	}

	public function testCreate() {
		$saved = $this->Task->save(array(
			'Task' => array(
				'project_id' => 1,
				'owner_id' => 2,
				'type' => 'enhancement',
				'status' => 'resolved',
				'priority' => 'major',
				'time_estimate' => '2h 10m',
				'story_points' => '2',
				'subject' => 'Do a thing',
			),
		));

		// Check the create/modify date are sane
		$this->assertNotNull($saved['Task']['created'], "Create date was null");
		$this->assertNotNull($saved['Task']['modified'], "Modify date was null");
		$this->assertEqual($saved['Task']['created'], $saved['Task']['modified']);

		unset($saved['Task']['modified']);
		unset($saved['Task']['created']);

		$this->assertEqual($saved, array(
			'Task' => array(
				'id' => $this->Task->getLastInsertID(),
				'project_id' => 1,
				'owner_id' => 2,
				'task_type_id' => $this->Task->TaskType->nameToID('enhancement'),
				'task_status_id' => $this->Task->TaskStatus->nameToID('resolved'),
				'task_priority_id' => $this->Task->TaskPriority->nameToID('major'),
				'time_estimate' => '130',
				'story_points' => '2',
				'subject' => 'Do a thing',
			)
		));

	}

	public function testCreateForMilestone() {
		$saved = $this->Task->save(array(
			'Task' => array(
				'project_id' => 2,
				'owner_id' => 3,
				'milestone_id' => 1,
				'type' => 'enhancement',
				'status' => 'resolved',
				'priority' => 'major',
				'time_estimate' => '2h 10m',
				'story_points' => '2',
				'subject' => 'Do a thing',
			),
		));

		// Check the create/modify date are sane
		$this->assertNotNull($saved['Task']['created'], "Create date was null");
		$this->assertNotNull($saved['Task']['modified'], "Modify date was null");
		$this->assertEqual($saved['Task']['created'], $saved['Task']['modified']);

		unset($saved['Task']['modified']);
		unset($saved['Task']['created']);

		$this->assertEqual($saved, array(
			'Task' => array(
				'id' => $this->Task->getLastInsertID(),
				'project_id' => 2,
				'owner_id' => 3,
				'milestone_id' => 1,
				'task_type_id' => $this->Task->TaskType->nameToID('enhancement'),
				'task_status_id' => $this->Task->TaskStatus->nameToID('resolved'),
				'task_priority_id' => $this->Task->TaskPriority->nameToID('major'),
				'time_estimate' => '130',
				'story_points' => '2',
				'subject' => 'Do a thing',
			)
		));
	}

	public function testCreateNullMilestone() {
		$saved = $this->Task->save(array(
			'Task' => array(
				'project_id' => 2,
				'owner_id' => 3,
				'milestone_id' => null,
				'type' => 'enhancement',
				'status' => 'resolved',
				'priority' => 'major',
				'time_estimate' => '2h 10m',
				'story_points' => '2',
				'subject' => 'Do a thing',
			),
		));

		// Check the create/modify date are sane
		$this->assertNotNull($saved['Task']['created'], "Create date was null");
		$this->assertNotNull($saved['Task']['modified'], "Modify date was null");
		$this->assertEqual($saved['Task']['created'], $saved['Task']['modified']);

		unset($saved['Task']['modified']);
		unset($saved['Task']['created']);

		$this->assertEqual($saved, array(
			'Task' => array(
				'id' => $this->Task->getLastInsertID(),
				'project_id' => 2,
				'owner_id' => 3,
				'milestone_id' => 0,
				'task_type_id' => $this->Task->TaskType->nameToID('enhancement'),
				'task_status_id' => $this->Task->TaskStatus->nameToID('resolved'),
				'task_priority_id' => $this->Task->TaskPriority->nameToID('major'),
				'time_estimate' => '130',
				'story_points' => '2',
				'subject' => 'Do a thing',
			)
		));
	}


	public function testCreateWithDependencies() {
		$saved = $this->Task->save(array(
			'Task' => array(
				'project_id' => 2,
				'owner_id' => 2,
				'type' => 'enhancement',
				'status' => 'resolved',
				'priority' => 'major',
				'time_estimate' => '2h 5m',
				'story_points' => '20',
				'subject' => 'Do a thing that depends on other things',
			),
			'DependsOn' => array(
				'DependsOn' => array(
					2, 3, 4
				),
			),
		));

		// Check the create/modify date are sane
		$this->assertNotNull($saved['Task']['created'], "Create date was null");
		$this->assertNotNull($saved['Task']['modified'], "Modify date was null");
		$this->assertEqual($saved['Task']['created'], $saved['Task']['modified']);

		unset($saved['Task']['modified']);
		unset($saved['Task']['created']);

		$this->assertEqual($saved, array(
			'Task' => array(
				'id' => $this->Task->getLastInsertID(),
				'project_id' => 2,
				'owner_id' => 2,
				'task_type_id' => $this->Task->TaskType->nameToID('enhancement'),
				'task_status_id' => $this->Task->TaskStatus->nameToID('resolved'),
				'task_priority_id' => $this->Task->TaskPriority->nameToID('major'),
				'time_estimate' => 125,
				'story_points' => '20',
				'subject' => 'Do a thing that depends on other things',
			),
			'DependsOn' => array(
				'DependsOn' => array(
					2, 3, 4
				),
			),
		));
		
	}

	public function testCreateWithDependenciesIncludingSelf() {
		$saved = $this->Task->save(array(
			'Task' => array(
				'id' => 1,
				'project_id' => 2,
				'owner_id' => 2,
				'type' => 'enhancement',
				'status' => 'resolved',
				'priority' => 'major',
				'time_estimate' => '2h 5m',
				'story_points' => '20',
				'subject' => 'Do a thing that depends on other things',
			),
			'DependsOn' => array(
				'DependsOn' => array(
					1, 2, 3, 4
				),
			),
		));

		// Check the create/modify date are sane
		$this->assertNotNull($saved['Task']['modified'], "Modify date was null");

		unset($saved['Task']['modified']);

		$this->assertEqual(array(
			'Task' => array(
				'id' => 1,
				'project_id' => 2,
				'owner_id' => 2,
				'task_type_id' => $this->Task->TaskType->nameToID('enhancement'),
				'task_status_id' => $this->Task->TaskStatus->nameToID('resolved'),
				'task_priority_id' => $this->Task->TaskPriority->nameToID('major'),
				'time_estimate' => 125,
				'story_points' => '20',
				'subject' => 'Do a thing that depends on other things',
			),
			'DependsOn' => array(
				'DependsOn' => array(
					2, 3, 4
				),
			),
		), $saved);
		
	}

	public function testCreateEmptyData() {
		$this->assertFalse($this->Task->save(array()));
	}

	public function testFetchLoggableTasks() {
		$this->Task->Project->id = 2;
		$tasks = $this->Task->fetchLoggableTasks(2);
		$this->assertEquals($tasks, array(
			0 => __('No Assigned Task'),
			__('Your Tasks') => array(
				1  => 'Resolved Major Task 1 for milestone 2',
				4  => 'In progress Urgent Task 4 for milestone 1',
				10 => 'In Progress Major Task 1 for milestone 2',
				11 => 'Task with differing public ID',
			),
			__('Others Tasks') => array(
				2 => 'Open Minor Task 2 for milestone 1',
				3 => 'In Progress Urgent Task 3 for no milestone',
				5 => 'Open Urgent Task 5 for milestone 1',
				6 => 'In Progress Blocker Task 7 for milestone 1',
				7 => 'Resolved Major Task 7 for milestone 1',
				12 => 'Java dev task 1 open',
				13 => 'Java dev task 2 open',
				14 => 'Java dev task 3 in progress',
				15 => 'Java dev task 4 in progress',
				16 => 'Java dev task 5 resolved',
			)
		), __('Incorrect task list returned'));
	}

	public function testOpenNull() {
		try{
			$found = $this->Task->open();
		} catch (NotFoundException $e) {
			$this->assertTrue(true);
		}
	}

	public function testOpenNullWithModelId() {
		$this->Task->id = 1;
		$found = $this->Task->open();
		$this->assertEquals($found['Task']['id'], 1);
	}

	public function testOpenNotFound() {
		try {
			$found = $this->Task->open(9999);
		} catch (NotFoundException $e) {
			$this->assertTrue(true);
		}
	}

	public function testOpenOK() {
		$found = $this->Task->open(1);
		$this->assertEquals($found['Task']['id'], 1);
	}

	private function crunchTaskList($tasks) {
		return array_map(function($a){return $a['Task']['id'];}, $tasks);
	}

	public function testTasksOfStatusForAssignee() {
		$open = $this->crunchTaskList($this->Task->listTasksOfStatusFor('open', 'Assignee', 2));
		$inProgress = $this->crunchTaskList($this->Task->listTasksOfStatusFor('in progress', 'Assignee', 2));
		$resolved = $this->crunchTaskList($this->Task->listTasksOfStatusFor('resolved', 'Assignee', 2));
		$closed = $this->crunchTaskList($this->Task->listTasksOfStatusFor('closed', 'Assignee', 2));
		$dropped = $this->crunchTaskList($this->Task->listTasksOfStatusFor('dropped', 'Assignee', 2));
		$done = $this->crunchTaskList($this->Task->listTasksOfStatusFor(array('resolved', 'closed'), 'Assignee', 2));

		$this->assertEquals(array(), $open);
		$this->assertEquals(array(4, 10, 11, 12, 13), $inProgress);
		$this->assertEquals(array(1), $resolved);
		$this->assertEquals(array(), $closed);
		$this->assertEquals(array(), $dropped);
		$this->assertEquals(array(1), $done);
	}

	public function testTasksOfStatusForMilestone() {
		$open = $this->crunchTaskList($this->Task->listTasksOfStatusFor('open', 'Milestone', 1));
		$inProgress = $this->crunchTaskList($this->Task->listTasksOfStatusFor('in progress', 'Milestone', 1));
		$resolved = $this->crunchTaskList($this->Task->listTasksOfStatusFor('resolved', 'Milestone', 1));
		$closed = $this->crunchTaskList($this->Task->listTasksOfStatusFor('closed', 'Milestone', 1));
		$dropped = $this->crunchTaskList($this->Task->listTasksOfStatusFor('dropped', 'Milestone', 1));
		$done = $this->crunchTaskList($this->Task->listTasksOfStatusFor(array('resolved', 'closed'), 'Milestone', 1));

		$this->assertEquals(array(5, 2), $open);
		$this->assertEquals(array(6, 4), $inProgress);
		$this->assertEquals(array(7), $resolved);
		$this->assertEquals(array(8), $closed);
		$this->assertEquals(array(9), $dropped);
		$this->assertEquals(array(8, 7), $done);
	}

	public function testTasksOfPriorityForAssignee() {
		$blocker = $this->crunchTaskList($this->Task->listTasksOfPriorityFor('blocker', 'Assignee', 2));
		$urgent = $this->crunchTaskList($this->Task->listTasksOfPriorityFor('urgent', 'Assignee', 2));
		$major = $this->crunchTaskList($this->Task->listTasksOfPriorityFor('major', 'Assignee', 2));
		$minor = $this->crunchTaskList($this->Task->listTasksOfPriorityFor('minor', 'Assignee', 2));

		$this->assertEquals(array(), $blocker);
		$this->assertEquals(array(4), $urgent);
		$this->assertEquals(array(1, 10, 11, 12, 13), $major);
		$this->assertEquals(array(), $minor);
	}

	public function testTasksOfPriorityForMilestone() {
		$blocker = $this->crunchTaskList($this->Task->listTasksOfPriorityFor('blocker', 'Milestone', 1));
		$urgent = $this->crunchTaskList($this->Task->listTasksOfPriorityFor('urgent', 'Milestone', 1));
		$major = $this->crunchTaskList($this->Task->listTasksOfPriorityFor('major', 'Milestone', 1));
		$minor = $this->crunchTaskList($this->Task->listTasksOfPriorityFor('minor', 'Milestone', 1));

		$this->assertEquals(array(6, 8), $blocker);
		$this->assertEquals(array(4, 5), $urgent);
		$this->assertEquals(array(7), $major);
		$this->assertEquals(array(2, 9), $minor);
	}

	public function testValidate() {
		unset($this->Task->id);
		$saved = $this->Task->save(array('Task' => array(
			'subject' => 'flibble',
			'assignee_id' => 999,
			'milestone_id' => 999,
			'owner_id' => 999,
			'task_status_id' => 999,
			'task_priority_id' => 999,
		)));

		$this->assertFalse($saved);
	}

	public function testGetTreeInvalid() {
		$this->assertEquals(array(), $this->Task->getTree(12, 1000));
	}

	public function testGetTree() {
		$tree = $this->Task->getTree(12, 1);

		$this->assertEquals(1, $tree['public_id']);
		$this->assertEquals(21, $tree['id']);
		$this->assertFalse($tree['loop']);

		$this->assertEquals(2, $tree['subTasks'][0]['public_id']);
		$this->assertEquals(22, $tree['subTasks'][0]['id']);
		$this->assertFalse($tree['subTasks'][0]['loop']);

		$this->assertEquals(3, $tree['subTasks'][0]['subTasks'][0]['public_id']);
		$this->assertEquals(23, $tree['subTasks'][0]['subTasks'][0]['id']);
		$this->assertFalse($tree['subTasks'][0]['subTasks'][0]['loop']);

		$this->assertEquals(4, $tree['subTasks'][0]['subTasks'][0]['subTasks'][0]['public_id']);
		$this->assertEquals(24, $tree['subTasks'][0]['subTasks'][0]['subTasks'][0]['id']);
		$this->assertFalse($tree['subTasks'][0]['subTasks'][0]['subTasks'][0]['loop']);

		$this->assertEquals(1, $tree['subTasks'][0]['subTasks'][0]['subTasks'][1]['public_id']);
		$this->assertEquals(21, $tree['subTasks'][0]['subTasks'][0]['subTasks'][1]['id']);
		$this->assertTrue($tree['subTasks'][0]['subTasks'][0]['subTasks'][1]['loop']);

	}

}
