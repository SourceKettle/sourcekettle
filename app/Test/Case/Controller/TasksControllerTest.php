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
	public function testIndexMineNotLoggedIn() {

		// Cannot see the page when not logged in
		$this->testAction('/project/private/tasks', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testIndexMine() {

		$this->_fakeLogin(2);
		// Perform the action, and check the user was authorized
		$ret = $this->testAction('/project/public/tasks', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

		// Check the page content looks roughly OK
		$this->assertContains('<h1>public <small>My Tasks for the Project</small></h1>', $this->view);
		$this->assertContains('<strong>Assigned to you</strong>', $this->view);

	}

	public function testOthers() {
		$this->_fakeLogin(2);
		// Perform the action, and check the user was authorized
		$ret = $this->testAction('/project/public/tasks/others', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

		// Check the page content looks roughly OK
		$this->assertContains('<h1>public <small>Others\' Tasks for the Project</small></h1>', $this->view);
		$this->assertContains('<strong>Assigned to others</strong>', $this->view);
	}

/**
 * testNobody method
 *
 * @return void
 */
	public function testNobody() {
		$this->_fakeLogin(2);
		// Perform the action, and check the user was authorized
		$ret = $this->testAction('/project/public/tasks/nobody', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

		// Check the page content looks roughly OK
		$this->assertContains('<h1>public <small>Unassigned Tasks for the Project</small></h1>', $this->view);
		$this->assertContains('<strong>Assigned to nobody</strong>', $this->view);
	}

/**
 * testAll method
 *
 * @return void
 */
	public function testAll() {
		$this->_fakeLogin(2);
		// Perform the action, and check the user was authorized
		$ret = $this->testAction('/project/public/tasks/all', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

		// Check the page content looks roughly OK
		$this->assertContains('<h1>public <small>All Tasks for the Project</small></h1>', $this->view);
		$this->assertContains('<strong>All tasks</strong>', $this->view);
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
