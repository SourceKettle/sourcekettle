<?php
App::uses('TasksController', 'Controller');
require_once(__DIR__ . DS . 'AppControllerTest.php');

/**
 * TasksController Test Case
 *
 */
class TasksControllerTest extends AppControllerTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.setting',
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
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
	);

	public function setUp() {
		parent::setUp("Tasks");
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndexNotLoggedIn() {

		// Cannot see the page when not logged in
		$this->testAction('/project/private/tasks', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}


	private function __testTaskIndex($userId, $url, $expectedTasks) {

		$this->_fakeLogin($userId);

		// Perform the action, and check the user was authorized
		$ret = $this->testAction($url, array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

		// Check the page content looks roughly OK
		$this->assertContains('<h1>public <small>Things to Do...</small></h1>', $this->view);
		$this->assertContains('<h2>Task list</h2>', $this->view);

		// Check correct tasks are returned
		$task_ids = array_map(function($a){return $a['Task']['id'];}, $this->vars['tasks']);
		$this->assertEquals($expectedTasks, $task_ids, "Incorrect task list returned");
	
	}

	public function testIndexDefault() {
		$this->__testTaskIndex(2, '/project/public/tasks', array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10));
	}

	public function testIndexAssignedAll() {
		$this->__testTaskIndex(2, '/project/public/tasks?assignees=all', array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10));
	}


	public function testIndexAssignedToOne() {
		$this->__testTaskIndex(2, '/project/public/tasks?assignees=2', array(1, 4, 10));
	}

	public function testIndexUnassigned() {
		$this->__testTaskIndex(2, '/project/public/tasks?assignees=0', array(2, 3, 5, 6, 7, 8, 9));
	}

	public function testPriorityAll() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=all', array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10));
	}
	public function testPriorityMinor() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=1', array(2, 9));
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=minor', array(2, 9));
	}
	public function testIndexPriorityMajor() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=2', array(1, 7, 10));
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=major', array(1, 7, 10));
	}
	public function testIndexPriorityUrgent() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=3', array(3, 4, 5));
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=urgent', array(3, 4, 5));
	}
	public function testIndexPriorityBlocker() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=4', array(6, 8));
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=blocker', array(6, 8));
	}

	public function testIndexStatusOpen() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=1', array(2, 5));
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=open', array(2, 5));
	}
	public function testIndexStatusInProgress() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=2', array(3, 4, 6, 10));
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=in%20progress', array(3, 4, 6, 10));
	}
	public function testIndexStatusResolved() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=3', array(1, 7));
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=resolved', array(1, 7));
	}
	public function testIndexStatusClosed() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=4', array(8));
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=closed', array(8));
	}
	public function testIndexStatusDropped() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=5', array(9));
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=dropped', array(9));
	}


/**
 * testView method
 *
 * @return void
 */
	public function testView() {
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {
	}

/**
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {
	}

/**
 * testStarttask method
 *
 * @return void
 */
	public function testStarttask() {
	}

/**
 * testStoptask method
 *
 * @return void
 */
	public function testStoptask() {
	}

/**
 * testOpentask method
 *
 * @return void
 */
	public function testOpentask() {
	}

/**
 * testClosetask method
 *
 * @return void
 */
	public function testClosetask() {
	}

/**
 * testResolve method
 *
 * @return void
 */
	public function testResolve() {
	}

/**
 * testUnresolve method
 *
 * @return void
 */
	public function testUnresolve() {
	}

/**
 * testFreeze method
 *
 * @return void
 */
	public function testFreeze() {
	}

/**
 * testSetBlocker method
 *
 * @return void
 */
	public function testSetBlocker() {
	}

/**
 * testSetUrgent method
 *
 * @return void
 */
	public function testSetUrgent() {
	}

/**
 * testSetMajor method
 *
 * @return void
 */
	public function testSetMajor() {
	}

/**
 * testSetMinor method
 *
 * @return void
 */
	public function testSetMinor() {
	}

/**
 * testDetachFromMilestone method
 *
 * @return void
 */
	public function testDetachFromMilestone() {
	}

/**
 * testApiView method
 *
 * @return void
 */
	public function testApiView() {
	}

/**
 * testApiUpdate method
 *
 * @return void
 */
	public function testApiUpdate() {
	}

/**
 * testApiAll method
 *
 * @return void
 */
	public function testApiAll() {
	}

/**
 * testApiMarshalled method
 *
 * @return void
 */
	public function testApiMarshalled() {
	}

}
