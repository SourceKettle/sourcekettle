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
		'core.cake_session',
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


	// Helper function - given a list of tasks, and a list of expected task IDs,
	// check that the two are equivalent
	private function __checkTaskList($tasks, $expectedTasks) {
		$this->assertTrue(is_array($tasks));
		$task_ids = array_map(function($a){return $a['Task']['id'];},$tasks);
		$this->assertEquals($expectedTasks, $task_ids, "Incorrect task list returned");
	}

	// Helper function for testing an index URL with various filter parameters
	private function __testTaskIndex($userId, $url, $expectedTasks) {

		$this->_fakeLogin($userId);

		// Perform the action, and check the user was authorized
		$ret = $this->testAction($url, array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

		// Check the page content looks roughly OK
		$this->assertContains('<h1>public <small>Things to Do...</small></h1>', $this->view);
		$this->assertContains('<h2>Task list</h2>', $this->view);

		// Check correct tasks are returned
		$this->__checkTaskList($this->vars['tasks'], $expectedTasks);
	
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
	public function testPriorityMinorNum() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=1', array(2, 9));
	}
	public function testPriorityMinorName() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=minor', array(2, 9));
	}
	public function testIndexPriorityMajorNum() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=2', array(1, 7, 10));
	}
	public function testIndexPriorityMajorName() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=major', array(1, 7, 10));
	}
	public function testIndexPriorityUrgentNum() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=3', array(3, 4, 5));
	}
	public function testIndexPriorityUrgentName() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=urgent', array(3, 4, 5));
	}
	public function testIndexPriorityBlockerNum() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=4', array(6, 8));
	}
	public function testIndexPriorityBlockerName() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=blocker', array(6, 8));
	}
	public function testIndexStatusOpenNum() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=1', array(2, 5));
	}
	public function testIndexStatusOpenName() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=open', array(2, 5));
	}
	public function testIndexStatusInProgressNum() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=2', array(3, 4, 6, 10));
	}
	public function testIndexStatusInProgressName() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=in%20progress', array(3, 4, 6, 10));
	}
	public function testIndexStatusResolvedNum() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=3', array(1, 7));
	}
	public function testIndexStatusResolvedName() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=resolved', array(1, 7));
	}
	public function testIndexStatusClosedNum() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=4', array(8));
	}
	public function testIndexStatusClosedName() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=closed', array(8));
	}
	public function testIndexStatusDroppedNum() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=5', array(9));
	}
	public function testIndexStatusDroppedName() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=dropped', array(9));
	}


/**
 * testView method
 *
 * @return void
 */
	public function testViewNotLoggedIn() {
		$ret = $this->testAction('/project/public/tasks/view/1', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testView() {
		$this->_fakeLogin(2);

		// Perform the action, and check the user was authorized
		$ret = $this->testAction('/project/public/tasks/view/1', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

		// Check the page content looks roughly OK
		$this->assertContains('<h1>public <small>A task for the Project</small></h1>', $this->view);

		// Check we've got the right stuff back
		$this->assertEquals($this->vars['task']['Task']['id'], 1);
		$this->assertEquals(array(1, 4, 10), array_keys($this->vars['tasks']['Your Tasks']));
		$this->assertEquals(array(2, 3, 5, 6, 7), array_keys($this->vars['tasks']['Others Tasks']));

	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAddTaskNotLoggedIn() {
		$ret = $this->testAction('/project/public/tasks/add', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAddTaskForm() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/tasks/add', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/project/public/tasks/add').'"|', $this->view);
	}

	public function testAddTask() {
		$this->_fakeLogin(2);
		$postData = array(
			'Task' => array(
				'subject' => 'new task for public project',
				'project_id' => 2,
				'task_type_id' => 2,
				'task_status_id' => 3,
				'task_priority_id' => 2,
				'assignee_id' => 3,
				'milestone_id' => 1,
				'time_estimate' => 145,
				'story_points' => 4,
				'description' => 'Look ma, I created a task!',
			)
		);

		$this->testAction('/project/public/tasks/add', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		$id = $this->controller->Task->getLastInsertID();

		// We should be redirected to the new task
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/project/public/tasks/view/'.$id, true), $this->headers['Location']);
	}
/**
 * testEdit method
 *
 * @return void
 */
	public function testEditTaskNotLoggedIn() {
		$this->testAction('/project/public/tasks/edit/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testEditTaskForm() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/tasks/edit/1', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/project/public/tasks/edit/1').'"|', $this->view);
	}

	public function testEditTask() {
		$this->_fakeLogin(2);
		$postData = array(
			'Task' => array(
				'subject' => 'updated task for public project',
				'task_type_id' => 2,
				'task_status_id' => 3,
				'task_priority_id' => 2,
				'assignee_id' => 3,
				'milestone_id' => 1,
				'time_estimate' => 145,
				'story_points' => 4,
				'description' => 'Look ma, I updated a task!',
			)
		);

		$this->testAction('/project/public/tasks/edit/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/project/public/tasks/view/1', true), $this->headers['Location']);
	}


	public function __testStatusChanges($url, $id, $status) {
		// Perform the action, and check the user was authorized
		$ret = $this->testAction($url, array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/project/public/tasks/view/'.$id, true), $this->headers['Location']);

		$retrieved = $this->controller->Task->findById($id);
		$this->assertTrue(is_array($retrieved));
		$this->assertTrue(is_array($retrieved['Task']));
		$this->assertTrue(is_array($retrieved['TaskStatus']));
		$this->assertEquals($status, $retrieved['TaskStatus']['name']);
		
	}

	public function testStarttaskNotLoggedIn() {
		$ret = $this->testAction('/project/public/tasks/starttask/1', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testStarttask() {
		$this->_fakeLogin(2);
		$this->__testStatusChanges('/project/public/tasks/starttask/1', 1, 'in progress');
	}

	public function testStoptask() {
		$this->_fakeLogin(2);
		$this->__testStatusChanges('/project/public/tasks/stoptask/1', 1, 'open');
	}
	public function testStoptaskNotLoggedIn() {
		$ret = $this->testAction('/project/public/tasks/stoptask/1', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testResolvetaskNotLoggedIn() {
		$ret = $this->testAction('/project/public/tasks/resolve/1', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testResolvetask() {
		$this->_fakeLogin(2);
		$this->__testStatusChanges('/project/public/tasks/resolve/1', 1, 'resolved');
	}

	public function testUnresolvetaskNotLoggedIn() {
		$ret = $this->testAction('/project/public/tasks/unresolve/1', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testUnresolvetask() {
		$this->_fakeLogin(2);
		$this->__testStatusChanges('/project/public/tasks/unresolve/1', 1, 'open');
	}

	public function testOpentaskNotLoggedIn() {
		$ret = $this->testAction('/project/public/tasks/opentask/1', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testOpentask() {
		$this->_fakeLogin(2);
		$this->__testStatusChanges('/project/public/tasks/opentask/1', 1, 'open');
	}

	public function testClosetaskNotLoggedIn() {
		$ret = $this->testAction('/project/public/tasks/closetask/1', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testClosetask() {
		$this->_fakeLogin(2);
		$this->__testStatusChanges('/project/public/tasks/closetask/1', 1, 'closed');
	}

	public function testFreezetaskNotLoggedIn() {
		$ret = $this->testAction('/project/public/tasks/freeze/1', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testFreezetask() {
		$this->_fakeLogin(2);
		$this->__testStatusChanges('/project/public/tasks/freeze/1', 1, 'dropped');
	}


	// Assigning tasks
	public function testAssignTaskNotLoggedIn() {
		$this->testAction('/project/public/tasks/assign/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAssignTaskForm() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/tasks/assign/1', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/project/public/tasks/view/1', true), $this->headers['Location']);
	}

	public function testAssignTask() {
		$this->_fakeLogin(2);
		$postData = array(
			'Assignee' => array(
				'id' => 1
			)
		);

		$this->testAction('/project/public/tasks/assign/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/project/public/tasks/view/1', true), $this->headers['Location']);

		$task = $this->controller->Task->find('first', array('conditions' => array('id' => 1), 'recursive' => -1));
		$this->assertEquals(1, $task['Task']['assignee_id']);
	}

	// Comments
	public function testCommentNotLoggedIn() {
		$this->testAction('/project/public/tasks/comment/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testCommentGetRedirect() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/tasks/comment/1', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/project/public/tasks/view/1', true), $this->headers['Location']);
	}

	public function testComment() {
		$this->_fakeLogin(2);
		$postData = array(
			'TaskComment' => array(
				'comment' => 'Something terrible...'
			)
		);

		$this->testAction('/project/public/tasks/comment/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/project/public/tasks/view/1', true), $this->headers['Location']);

		$task = $this->controller->Task->TaskComment->find('first', array('conditions' => array('task_id' => 1), 'fields' => array('user_id', 'task_id', 'comment'), 'recursive' => -1));
		$this->assertEquals('Something terrible...', $task['TaskComment']['comment']);
		$this->assertEquals(1, $task['TaskComment']['task_id']);
		$this->assertEquals(2, $task['TaskComment']['user_id']);
	}

	public function testUpdateCommentTaskNotLoggedIn() {
		$this->testAction('/project/public/tasks/updateComment/2', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testUpdateCommentGetRedirect() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/tasks/updateComment/2', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/project/public/tasks/view/2', true), $this->headers['Location']);
	}

	public function testUpdateComment() {
		$this->_fakeLogin(1);
		$postData = array(
			'TaskCommentEdit' => array(
				'id' => 2,
				'comment' => 'A further terrible thing...'
			)
		);

		$this->testAction('/project/public/tasks/updateComment/2', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/project/public/tasks/view/2', true), $this->headers['Location']);

		$task = $this->controller->Task->TaskComment->find('first', array('conditions' => array('id' => 2), 'fields' => array('user_id', 'task_id', 'comment'), 'recursive' => -1));
		$this->assertEquals('A further terrible thing...', $task['TaskComment']['comment']);
		$this->assertEquals(2, $task['TaskComment']['task_id']);
		$this->assertEquals(1, $task['TaskComment']['user_id']);
	}

	public function testDeleteCommentNotLoggedIn() {
		$this->testAction('/project/public/tasks/deleteComment/2', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testDeleteCommentGetRedirect() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/tasks/deleteComment/2', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/project/public/tasks/view/2', true), $this->headers['Location']);
	}

	public function testDeleteComment() {
		$this->_fakeLogin(1);
		$postData = array(
			'TaskCommentDelete' => array(
				'id' => 2,
			)
		);

		$this->testAction('/project/public/tasks/deleteComment/2', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/project/public/tasks/view/2', true), $this->headers['Location']);

		$task = $this->controller->Task->TaskComment->find('first', array('conditions' => array('id' => 2), 'fields' => array('user_id', 'task_id', 'comment'), 'recursive' => -1));
		$this->assertEquals(array(), $task);
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
