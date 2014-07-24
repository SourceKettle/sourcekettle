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
		$details = $this->Task->findById(1);
		$this->assertEquals($details, array(
			'Task' => array(
				'id' => '1',
				'project_id' => '2',
				'owner_id' => '2',
				'task_type_id' => '1',
				'task_status_id' => '3',
				'task_priority_id' => '2',
				'assignee_id' => '2',
				'milestone_id' => '2',
				'time_estimate' => '2h 40m',
				'story_points' => '12',
				'subject' => 'Resolved Major Task 1 for milestone 2',
				'description' => 'lorem ipsum dolor sit amet',
				'created' => '0000-00-00 00:00:00',
				'modified' => '0000-00-00 00:00:00',
				'deleted' => '0',
				'deleted_date' => null,
				'public_id' => '1',
				'dependenciesComplete' => false
			),
			'Project' => array(
				'id' => '2',
				'name' => 'public',
				'description' => 'desc',
				'public' => true,
				'repo_type' => '1',
				'created' => '2012-06-01 12:46:07',
				'modified' => '2012-06-01 12:46:07'
			),
			'Owner' => array(
				'password' => 'Lorem ipsum dolor sit amet',
				'id' => '2',
				'name' => 'Mrs Smith',
				'email' => 'mrs.smith@example.com',
				'is_admin' => false,
				'is_active' => true,
				'theme' => 'default',
				'created' => '2012-06-01 12:50:08',
				'modified' => '2012-06-01 12:50:08',
				'deleted' => '0',
				'deleted_date' => null,
				'is_internal' => '1'
			),
			'TaskType' => array(
				'id' => '1',
				'name' => 'bug',
				'created' => '2014-07-21 07:09:13',
				'modified' => '2014-07-21 07:09:13'
			),
			'TaskStatus' => array(
				'id' => '3',
				'name' => 'resolved',
				'created' => '2014-07-21 07:09:13',
				'modified' => '2014-07-21 07:09:13'
			),
			'TaskPriority' => array(
				'id' => '2',
				'name' => 'major',
				'created' => '2014-07-21 07:09:13',
				'modified' => '2014-07-21 07:09:13'
			),
			'Assignee' => array(
				'password' => 'Lorem ipsum dolor sit amet',
				'id' => '2',
				'name' => 'Mrs Smith',
				'email' => 'mrs.smith@example.com',
				'is_admin' => false,
				'is_active' => true,
				'theme' => 'default',
				'created' => '2012-06-01 12:50:08',
				'modified' => '2012-06-01 12:50:08',
				'deleted' => '0',
				'deleted_date' => null,
				'is_internal' => '1'
			),
			'Milestone' => array(
				'id' => '2',
				'project_id' => '2',
				'subject' => 'Sprint 2',
				'description' => '<b>Foo</b>',
				'due' => '2013-01-01',
				'is_open' => false,
				'created' => '2012-08-02 20:05:59',
				'modified' => '2012-09-02 20:05:59',
				'deleted' => '0',
				'deleted_date' => null,
				'percent' => 100
			),
			'TaskComment' => array(),
			'Time' => array(
				0 => array(
					'id' => '1',
					'project_id' => '1',
					'user_id' => '1',
					'task_id' => '1',
					'mins' => '90',
					'description' => 'A description.',
					'date' => '2012-11-11',
					'created' => '2012-11-11 10:24:06',
					'modified' => '2012-11-11 10:24:06',
					'deleted' => '0',
					'deleted_date' => null,
					'minutes' => array(
						'w' => 0,
						'd' => 0,
						'h' => 1,
						'm' => 30,
						't' => 90,
						's' => '1h 30m'
					)
				),
				1 => array(
					'id' => '4',
					'project_id' => '1',
					'user_id' => '3',
					'task_id' => '1',
					'mins' => '19',
					'description' => 'A description of the fourth <b>task</b>.',
					'date' => '2012-11-11',
					'created' => '2012-11-11 10:24:06',
					'modified' => '2012-11-11 10:24:06',
					'deleted' => '0',
					'deleted_date' => null,
					'minutes' => array(
						'w' => 0,
						'd' => 0,
						'h' => 0,
						'm' => 19,
						't' => 19,
						's' => '0h 19m'
					)
				),
				2 => array(
					'id' => '5',
					'project_id' => '3',
					'user_id' => '2',
					'task_id' => '1',
					'mins' => '15',
					'description' => 'A description of the fourth <b>task</b>.',
					'date' => '2012-11-11',
					'created' => '2012-11-11 10:24:06',
					'modified' => '2012-11-11 10:24:06',
					'deleted' => '0',
					'deleted_date' => null,
					'minutes' => array(
						'w' => 0,
						'd' => 0,
						'h' => 0,
						'm' => 15,
						't' => 15,
						's' => '0h 15m'
					)
				)
			),
			'DependsOn' => array(
				0 => array(
					'id' => '2',
					'project_id' => '2',
					'owner_id' => '3',
					'task_type_id' => '1',
					'task_status_id' => '1',
					'task_priority_id' => '1',
					'assignee_id' => '0',
					'milestone_id' => '1',
					'time_estimate' => '240',
					'story_points' => '0',
					'subject' => 'Open Minor Task 2 for milestone 1',
					'description' => 'lorem ipsum dolor sit amet',
					'created' => '0000-00-00 00:00:00',
					'modified' => '0000-00-00 00:00:00',
					'deleted' => '0',
					'deleted_date' => null,
					'public_id' => '2',
					'TaskDependency' => array(
						'id' => '1',
						'child_task_id' => '1',
						'parent_task_id' => '2',
						'created' => '2014-04-29 10:38:45',
						'modified' => '2014-04-29 10:38:45'
					)
				)
			),
			'DependedOnBy' => array()
		), "Incorrect task data returned");
	}

/**
 * testIsAssignee method
 *
 * @return void
 */
	public function testIsAssignee() {
		$this->Task->id = 1;
		$this->assertTrue($this->Task->isAssignee(2));
		$this->assertFalse($this->Task->isAssignee(1));
		$this->assertFalse($this->Task->isAssignee(4));
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
	public function testFetchHistory() {
		$this->Task->Project->id = 2;
		$history = $this->Task->fetchHistory(2, 10, 0, 0);
		$this->assertEquals($history, array(), "Incorrect history data returned");
	}

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

/**
 * testFetchLoggableTasks method
 *
 * @return void
 */
	public function testFetchLoggableTasks() {
		$this->Task->Project->id = 2;
		$tasks = $this->Task->fetchLoggableTasks(2);
		$this->assertEquals($tasks, array(
			0 => 'No Assigned Task',
			'Your Tasks' => array(
				1 => 'Resolved Major Task 1 for milestone 2',
				4 => 'In progress Urgent Task 4 for milestone 1'
			),
			'Others Tasks' => array(
				2 => 'Open Minor Task 2 for milestone 1',
				3 => 'In Progress Urgent Task 3 for no milestone',
				5 => 'Open Urgent Task 5 for milestone 1',
				6 => 'In Progress Blocker Task 7 for milestone 1',
				7 => 'Resolved Major Task 7 for milestone 1'
			)
		), "Incorrect task list returned");
	}

}
