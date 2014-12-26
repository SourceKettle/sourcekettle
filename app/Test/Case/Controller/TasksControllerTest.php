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
		'app.user_setting',
		'app.project_setting',
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
		'app.milestone_burndown_log',
		'app.project_burndown_log',
		'app.collaborating_team',
		'app.group_collaborating_team',
		'app.team',
		'app.teams_user',
		'app.project_group',
		'app.project_groups_project',
	);

	public function setUp() {
		parent::setUp("Tasks");
	}


	
	public function testIndexFeatureDisabledOnSystem() {

		ClassRegistry::init("Setting")->saveSettingsTree(array('Setting' => array('Features' => array('task_enabled' => false))));

		// Cannot see the page when not logged in
		try{
			$this->testAction('/project/private/tasks', array('method' => 'get', 'return' => 'vars'));
			$this->assertNotAuthorized();
		} catch (ForbiddenException $e){
			$this->assertTrue(true, "Correct exception thrown");
			return;
		} catch (Exception $e){
			$this->assertTrue(false, "Incorrect exception thrown: ".$e->getMessage());
			return;
		}
		$this->assertTrue(false, "No exception thrown");
	}

	public function testIndexFeatureDisabledOnProject() {

		ClassRegistry::init("ProjectSetting")->saveSettingsTree('private', array('ProjectSetting' => array('Features' => array('task_enabled' => false))));

		// Cannot see the page when not logged in
		try{
			$this->testAction('/project/private/tasks', array('method' => 'get', 'return' => 'vars'));
			$this->assertNotAuthorized();
		} catch (ForbiddenException $e){
			$this->assertTrue(true, "Correct exception thrown");
			return;
		} catch (Exception $e){
			$this->assertTrue(false, "Incorrect exception thrown: ".$e->getMessage());
			return;
		}
		$this->assertTrue(false, "No exception thrown");
	}

	public function testIndexNotLoggedIn() {

		// Cannot see the page when not logged in
		$this->testAction('/project/private/tasks', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testIndexInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/private/tasks', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testIndexInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/private/tasks', array('return' => 'view', 'method' => 'get'));
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
		$this->__testTaskIndex(2, '/project/public/tasks', array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 13, 14, 15, 16, 17, 18, 19, 20));
	}

	public function testIndexAssignedAll() {
		$this->__testTaskIndex(2, '/project/public/tasks?assignees=all', array(1, 4, 10, 13, 14, 15, 16, 17, 18, 19, 20));
	}


	public function testIndexAssignedToOne() {
		$this->__testTaskIndex(2, '/project/public/tasks?assignees=2', array(1, 4, 10, 13));
	}

	public function testIndexUnassigned() {
		$this->__testTaskIndex(2, '/project/public/tasks?assignees=0', array(2, 3, 5, 6, 7, 8, 9));
	}

	public function testPriorityAll() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=all', array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 13, 14, 15, 16, 17, 18, 19, 20));
	}
	public function testPriorityMinorNum() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=1', array(2, 9));
	}
	public function testPriorityMinorName() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=minor', array(2, 9));
	}
	public function testIndexPriorityMajorNum() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=2', array(1, 7, 10, 13, 14, 15, 16, 17, 18, 19, 20));
	}
	public function testIndexPriorityMajorName() {
		$this->__testTaskIndex(2, '/project/public/tasks?priorities=major', array(1, 7, 10, 13, 14, 15, 16, 17, 18, 19, 20));

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
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=1', array(2, 5, 14, 15));
	}
	public function testIndexStatusOpenName() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=open', array(2, 5, 14, 15));
	}
	public function testIndexStatusInProgressNum() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=2', array(3, 4, 6, 10, 13, 16, 17));
	}
	public function testIndexStatusInProgressName() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=in%20progress', array(3, 4, 6, 10, 13, 16, 17));
	}
	public function testIndexStatusResolvedNum() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=3', array(1, 7, 18));
	}
	public function testIndexStatusResolvedName() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=resolved', array(1, 7, 18));
	}
	public function testIndexStatusClosedNum() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=4', array(8, 19));
	}
	public function testIndexStatusClosedName() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=closed', array(8, 19));
	}
	public function testIndexStatusDroppedNum() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=5', array(9, 20));
	}
	public function testIndexStatusDroppedName() {
		$this->__testTaskIndex(2, '/project/public/tasks?statuses=dropped', array(9, 20));
	}


	public function testViewNotLoggedIn() {
		$ret = $this->testAction('/project/public/tasks/view/1', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testViewInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/private/tasks/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testViewInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/private/tasks/view/1', array('return' => 'view', 'method' => 'get'));
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
		$yours = array_keys($this->vars['tasks']['Your Tasks']);
		sort($yours);
		$others = array_keys($this->vars['tasks']['Others Tasks']);
		sort($others);
		$this->assertEquals(array(1, 4, 10, 11), $yours);
		$this->assertEquals(array(2, 3, 5, 6, 7, 12, 13, 14, 15, 16), $others);

	}

	public function testAddTaskNotLoggedIn() {
		$ret = $this->testAction('/project/public/tasks/add', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAddTaskNotCollaborator() {
		$this->_fakeLogin(23);
		$ret = $this->testAction('/project/public/tasks/add', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAddTaskProjectGuest() {
		$this->_fakeLogin(8);
		$ret = $this->testAction('/project/public/tasks/add', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAddTaskProjectUser() {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/public/tasks/add', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
	}

	public function testAddTaskProjectAdmin() {
		$this->_fakeLogin(2);
		$ret = $this->testAction('/project/public/tasks/add', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
	}

	public function testAddInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/private/tasks/add', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAddInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/private/tasks/add', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAddTaskForm() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/tasks/add', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/project/public/tasks/add').'"|', $this->view);
	}

	public function testAddTaskFormPreselectMilestone() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/tasks/add?milestone=2', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/project/public/tasks/add').'|', $this->view);
		$this->assertRegexp('|<option value="2" selected="selected">Sprint 2</option>|', $this->view);
	}

	public function testAddTask() {
		$this->_fakeLogin(2);
		$postData = array(
			'Task' => array(
				'subject' => 'new task for public project',
				'project_id' => 2,
				'task_type_id' => 2,
				'task_status_id' => 1,
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

		// The subject and description should be removed, and we should end up with the "add a new task" form again
		unset($postData['Task']['subject']);
		unset($postData['Task']['description']);
		$postData['Task']['owner_id'] = 2;
		$this->assertEquals($postData, $this->controller->request->data);
	}

	public function testEditTaskNotLoggedIn() {
		$this->testAction('/project/public/tasks/edit/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testEditTaskNotCollaborator() {
		$this->_fakeLogin(23);
		$ret = $this->testAction('/project/public/tasks/edit/1', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testEditTaskProjectGuest() {
		$this->_fakeLogin(8);
		$ret = $this->testAction('/project/public/tasks/edit/1', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testEditTaskProjectUser() {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/public/tasks/edit/1', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
	}

	public function testEditTaskProjectAdmin() {
		$this->_fakeLogin(2);
		$ret = $this->testAction('/project/public/tasks/edit/1', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
	}

	public function testEditInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/private/tasks/edit/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testEditInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/private/tasks/edit/1', array('return' => 'view', 'method' => 'get'));
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
		$this->assertRedirect('/project/public/tasks/view/1');
	}


	public function __testStatusChanges($url, $id, $status) {
		// Perform the action, and check the user was authorized
		$ret = $this->testAction($url, array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertRedirect('/project/public/tasks/view/'.$id);

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

	public function testStarttaskInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/public/tasks/starttask/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testStarttaskInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/public/tasks/starttask/1', array('return' => 'view', 'method' => 'get'));
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

	public function testStoptaskInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/public/tasks/stoptask/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testStoptaskInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/public/tasks/stoptask/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testResolvetaskNotLoggedIn() {
		$ret = $this->testAction('/project/public/tasks/resolve/1', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testResolvetaskInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/public/tasks/resolve/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testResolvetaskInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/public/tasks/resolve/1', array('return' => 'view', 'method' => 'get'));
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

	public function testUnresolvetaskInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/public/tasks/unresolve/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testUnresolvetaskInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/public/tasks/unresolve/1', array('return' => 'view', 'method' => 'get'));
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

	public function testOpentaskInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/public/tasks/opentask/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testOpentaskInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/public/tasks/opentask/1', array('return' => 'view', 'method' => 'get'));
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
	public function testClosetaskInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/public/tasks/closetask/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testClosetaskInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/public/tasks/closetask/1', array('return' => 'view', 'method' => 'get'));
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
	public function testFreezetaskInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/public/tasks/freeze/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testFreezetaskInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/public/tasks/freeze/1', array('return' => 'view', 'method' => 'get'));
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
	public function testAssigntaskInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/public/tasks/assign/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAssigntaskInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/public/tasks/assign/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAssignTaskForm() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/tasks/assign/1', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertRedirect('/project/public/tasks/view/1');
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
		$this->assertRedirect('/project/public/tasks/view/1');

		$task = $this->controller->Task->find('first', array('conditions' => array('id' => 1), 'recursive' => -1));
		$this->assertEquals(1, $task['Task']['assignee_id']);
	}

	public function testAssignTaskNotCollaborator() {
		$this->_fakeLogin(2);
		$postData = array(
			'Assignee' => array(
				'id' => 3
			)
		);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("The assignee could not be updated. The selected user is not a collaborator!"));

		$this->testAction('/project/public/tasks/assign/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertRedirect('/project/public/tasks/view/1');

		$task = $this->controller->Task->find('first', array('conditions' => array('id' => 3), 'recursive' => -1));
		$this->assertNotEquals(3, $task['Task']['assignee_id']);
	}

	// Comments
	public function testCommentNotLoggedIn() {
		$this->testAction('/project/public/tasks/comment/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}
	public function testCommentInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/public/tasks/comment/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testCommentInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/public/tasks/comment/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testCommentGetRedirect() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/tasks/comment/1', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertRedirect('/project/public/tasks/view/1');
	}

	public function testCommentNoData() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/tasks/comment/1', array('return' => 'view', 'method' => 'post', 'data' => array()));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertRedirect('/project/public/tasks/view/1');
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
		$this->assertRedirect('/project/public/tasks/view/1');

		$task = $this->controller->Task->TaskComment->find('first', array('conditions' => array('task_id' => 1), 'fields' => array('user_id', 'task_id', 'comment'), 'recursive' => -1));
		$this->assertEquals('Something terrible...', $task['TaskComment']['comment']);
		$this->assertEquals(1, $task['TaskComment']['task_id']);
		$this->assertEquals(2, $task['TaskComment']['user_id']);
	}

	public function testCommentFail() {
		$this->_fakeLogin(2);
		$postData = array(
			'TaskComment' => array(
				'comment' => 'Something terrible...'
			)
		);

		$this->controller->TaskComment = $this->getMockForModel('TaskComment', array('save'));
		$this->controller->TaskComment
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false)); 
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("The comment could not be saved. Please try again."));

		$this->testAction('/project/public/tasks/comment/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

	}

	public function testUpdateCommentTaskNotLoggedIn() {
		$this->testAction('/project/public/tasks/updateComment/2', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testUpdateCommentInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/public/tasks/updateComment/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testUpdateCommentInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/public/tasks/updateComment/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testUpdateCommentGetRedirect() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/tasks/updateComment/2', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertRedirect('/project/public/tasks/view/2');
	}

	public function testUpdateCommentNoDataRedirect() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/tasks/updateComment/2', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertRedirect('/project/public/tasks/view/2');
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
		$this->assertRedirect('/project/public/tasks/view/2');

		$task = $this->controller->Task->TaskComment->find('first', array('conditions' => array('id' => 2), 'fields' => array('user_id', 'task_id', 'comment'), 'recursive' => -1));
		$this->assertEquals('A further terrible thing...', $task['TaskComment']['comment']);
		$this->assertEquals(2, $task['TaskComment']['task_id']);
		$this->assertEquals(1, $task['TaskComment']['user_id']);
	}

	public function testUpdateCommentInvalidID() {
		$this->_fakeLogin(1);
		$postData = array(
			'TaskCommentEdit' => array(
				'id' => 2000,
				'comment' => 'A further terrible thing...'
			)
		);

		try{ 
			$this->testAction('/project/public/tasks/updateComment/2000', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		} catch(NotFoundException $e) {
			$this->assertTrue(true, "Correct exception thrown");
		} catch(Exception $e) {
			$this->assertTrue(false, "Incorrect exception thrown: ".$e->getMessage());
		}
		$this->assertAuthorized();
	}

	public function testUpdateCommentFail() {
		$this->_fakeLogin(1);
		$postData = array(
			'TaskCommentEdit' => array(
				'id' => 2,
				'comment' => 'A further terrible thing...'
			)
		);

		$this->controller->TaskComment = $this->getMockForModel('TaskComment', array('save'));
		$this->controller->TaskComment
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false)); 
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("The comment could not be updated. Please try again."));

		$this->testAction('/project/public/tasks/updateComment/2', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
	}

	public function testDeleteCommentNotLoggedIn() {
		$this->testAction('/project/public/tasks/deleteComment/2', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testDeleteCommentInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/public/tasks/deleteComment/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testDeleteCommentInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/public/tasks/deleteComment/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}
	public function testDeleteCommentGetRedirect() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/tasks/deleteComment/2', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		// We should be redirected to the task page
		$this->assertRedirect('/project/public/tasks/view/2');
	}

	public function testDeleteCommentInvalidID() {
		$this->_fakeLogin(1);
		$postData = array(
			'TaskCommentDelete' => array(
				'id' => 2000,
			)
		);

		try{ 
			$this->testAction('/project/public/tasks/deleteComment/2000', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		} catch(NotFoundException $e) {
			$this->assertTrue(true, "Correct exception thrown");
		} catch(Exception $e) {
			$this->assertTrue(false, "Incorrect exception thrown: ".$e->getMessage());
		}
		$this->assertAuthorized();
	}

	public function testDeleteCommentFail() {
		$this->_fakeLogin(1);
		$postData = array(
			'TaskCommentDelete' => array(
				'id' => 2,
			)
		);

		$this->controller->TaskComment = $this->getMockForModel('TaskComment', array('delete'));
		$this->controller->TaskComment
			->expects($this->once())
			->method('delete')
			->will($this->returnValue(false));
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("The comment could not be deleted. Please try again."));


		$this->testAction('/project/public/tasks/deleteComment/2', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
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
		$this->assertRedirect('/project/public/tasks/view/2');

		$task = $this->controller->Task->TaskComment->find('first', array('conditions' => array('id' => 2), 'fields' => array('user_id', 'task_id', 'comment'), 'recursive' => -1));
		$this->assertEquals(array(), $task);
	}

	// Tests for updates via API
	public function testApiUpdateSystemAdminOK() {
		$this->_fakeLogin(5);
		$postData = array('task_status_id' => 3, 'task_priority_id' => 4);
		$this->testAction('/api/tasks/private/update/2', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
		
		$returned = json_decode($this->view, true);
		unset($returned['modified']);
		$this->assertEquals($returned, array('task_status_id' => 3, 'task_priority_id' => 4, 'error' => 'no_error'));
	}

	public function testApiUpdateProjectAdminOK() {
		$this->_fakeLogin(1);
		$postData = array('task_status_id' => 3, 'task_priority_id' => 4);
		$this->testAction('/api/tasks/private/update/2', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
		
		$returned = json_decode($this->view, true);
		unset($returned['modified']);
		$this->assertEquals($returned, array('task_status_id' => 3, 'task_priority_id' => 4, 'error' => 'no_error'));
		
	}

	public function testApiUpdateProjectUserOK() {
		$this->_fakeLogin(4);
		$postData = array('task_status_id' => 3, 'task_priority_id' => 4);
		$this->testAction('/api/tasks/private/update/2', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
		
		$returned = json_decode($this->view, true);
		unset($returned['modified']);
		$this->assertEquals($returned, array('task_status_id' => 3, 'task_priority_id' => 4, 'error' => 'no_error'));
		
	}
	public function testApiUpdateProjectGuestFail() {
		$this->_fakeLogin(3);
		$postData = array('task_status_id' => 3, 'task_priority_id' => 4);
		$this->testAction('/api/tasks/private/update/2', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertNotAuthorized();
		
	}

	public function testApiUpdateNotProjectUserFail() {
		$this->_fakeLogin(8);
		$postData = array('task_status_id' => 3, 'task_priority_id' => 4);
		$this->testAction('/api/tasks/private/update/2', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertNotAuthorized();
		
	}

	public function testApiUpdateInactiveFail() {
		$this->_fakeLogin(6);
		$postData = array('task_status_id' => 3, 'task_priority_id' => 4);
		$this->testAction('/api/tasks/private/update/2', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertNotAuthorized();
		
	}

	public function testApiUpdateNoProject() {

		$this->_fakeLogin(4);
		$postData = array('task_status_id' => 3, 'task_priority_id' => 4);

		$this->testAction('/api/tasks/update/', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		
		$returned = json_decode($this->view, true);
		$this->assertEquals($returned, array('error' => '400', 'message' => __('Bad request, no project specified.')));
		
	}
	public function testApiUpdateNoId() {

		$this->_fakeLogin(4);
		$postData = array('task_status_id' => 3, 'task_priority_id' => 4);

		$this->testAction('/api/tasks/private/update/', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
		
		$returned = json_decode($this->view, true);
		$this->assertEquals($returned, array('error' => '400', 'message' => __('Bad request, no task ID specified.')));
		
	}

	public function testApiUpdateInvalidId() {

		$this->_fakeLogin(4);
		$postData = array('task_status_id' => 3, 'task_priority_id' => 4);

		$this->testAction('/api/tasks/private/update/shoe', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
		
		$returned = json_decode($this->view, true);
		$this->assertEquals($returned, array('error' => '400', 'message' => __('Bad request, task ID should be numeric.')));
		
	}

	public function testApiUpdateIncorrectId() {

		$this->_fakeLogin(4);
		$postData = array('task_status_id' => 3, 'task_priority_id' => 4);

		$this->testAction('/api/tasks/private/update/2000', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
		
		$returned = json_decode($this->view, true);
		$this->assertEquals($returned, array('error' => '404', 'message' => __('Task with ID %s not found for project %s', 2000, 'private')));
		
	}

	/* TODO fails due to incorrect table prefix, unknown reason so far...
	public function testApiUpdateFail() {
		$this->_fakeLogin(4);
		$postData = array('task_status_id' => 3, 'task_priority_id' => 4);

		$this->controller->Task = $this->getMockForModel('Task', array('save'));
		$this->controller->Task
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));

		$this->testAction('/api/tasks/private/update/2', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
		
		$returned = json_decode($this->view, true);
		$this->assertEquals($returned, array('error' => '500', 'message' => __('Task update failed')));
		
	}*/


	public function testPersonalKanbanNotLoggedIn() {
		
		$this->testAction('/kanban', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
		
	}
	public function testPersonalKanbanInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/kanban', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testPersonalKanbanInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/kanban', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testPersonalKanban() {
		
		$this->_fakeLogin(2);

		$this->testAction('/kanban', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		
		$backlog = array_map(function($a){return $a['Task']['id'];}, $this->vars['backlog']);
		$this->assertEquals(array(), $backlog);

		$inProgress = array_map(function($a){return $a['Task']['id'];}, $this->vars['inProgress']);
		$this->assertEquals(array(4, 10, 11, 12, 13), $inProgress);

		$completed = array_map(function($a){return $a['Task']['id'];}, $this->vars['completed']);
		$this->assertEquals(array(1), $completed);
	}

	public function testTeamKanbanNotLoggedIn() {
		
		$this->testAction('/team_kanban/java_developers', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
		
	}

	public function testTeamKanbanInvalidTeam() {
		
		$this->_fakeLogin(5);
		// This will break...
		try{
			$this->testAction('/team_kanban/java_developers_with_hats', array('return' => 'view', 'method' => 'get'));
		} catch(Exception $e) {
			debug($e->getMessage());
		}

		// This will actually check and throw an exception
		try{
			$this->assertNotAuthorized();
		} catch(NotFoundException $e) {
			$this->assertTrue(true, "Correct exception thrown");
			return;
		} catch(Exception $e) {
			$this->assertTrue(false, "Incorrect exception thrown: ".$e->getMessage());
			return;
		}
		$this->assertTrue(false, "No exception thrown");
		
	}
	public function testTeamKanbanInactiveUser() {
		$this->_fakeLogin(6);
		try{
			$this->testAction('/team_kanban/java_developers', array('return' => 'view', 'method' => 'get'));
		} catch (Exception $e){}
		$this->assertNotAuthorized();
	}

	public function testTeamKanbanInactiveAdmin() {
		$this->_fakeLogin(22);
		try{
			$this->testAction('/team_kanban/java_developers', array('return' => 'view', 'method' => 'get'));
		} catch (Exception $e){}
		$this->assertNotAuthorized();
	}

	public function testTeamKanbanNotTeamMember() {
		$this->_fakeLogin(2);
		try{
			$this->testAction('/team_kanban/java_developers', array('return' => 'view', 'method' => 'get'));
		} catch (Exception $e){}
		$this->assertNotAuthorized();
	}

	public function testTeamKanbanNotTeamMemberSystemAdmin() {
		$this->_fakeLogin(5);
		try{
			$this->testAction('/team_kanban/java_developers', array('return' => 'view', 'method' => 'get'));
		} catch (Exception $e){}
		$this->assertAuthorized();
	}

	public function testTeamKanban() {
		
		$this->_fakeLogin(14);

		$this->testAction('/team_kanban/java_developers', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		
		$backlog = array_map(function($a){return $a['Task']['id'];}, $this->vars['backlog']);
		$this->assertEquals(array(14, 15), $backlog);

		$inProgress = array_map(function($a){return $a['Task']['id'];}, $this->vars['inProgress']);
		$this->assertEquals(array(16, 17), $inProgress);

		$completed = array_map(function($a){return $a['Task']['id'];}, $this->vars['completed']);
		$this->assertEquals(array(18, 19), $completed);
	}

	
}
