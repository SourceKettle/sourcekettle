<?php
App::uses('MilestonesController', 'Controller');
require_once(__DIR__ . DS . 'AppControllerTest.php');

/**
 * MilestonesController Test Case
 *
 */
class MilestonesControllerTest extends AppControllerTest {

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
		parent::setUp("Milestones");
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndexNotLoggedIn() {
		$this->testAction('/project/public/milestones', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}
	public function testIndex() {
		$this->_fakeLogin(3);
		$this->testAction('/project/public/milestones', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();

		// We should be redirected to the list of open milestones
		$this->assertRedirect('/project/public/milestones/open');
	}

/**
 * testOpen method
 *
 * @return void
 */
	public function testOpen() {
		$this->_fakeLogin(3);
		$this->testAction('/project/public/milestones/open', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();

		$this->assertContains('<h1>public <small>Open Milestones</small></h1>', $this->view);
		$this->assertRegexp('|<a href=".*'.Router::url('/project/public/milestones/view/1').'"|', $this->view);
		$this->assertRegexp('|<a href=".*'.Router::url('/project/public/milestones/view/3').'"|', $this->view);

		$this->assertNotNull($this->vars['milestones']);
		$this->assertEquals(2, count($this->vars['milestones']));

		foreach ($this->vars['milestones'] as $milestone) {

			if ($milestone['Milestone']['id'] == 1
				&& $milestone['Tasks']['open']['numTasks'] == 2
				&& $milestone['Tasks']['in progress']['numTasks'] == 2
				&& $milestone['Tasks']['resolved']['numTasks'] == 1
				&& $milestone['Tasks']['closed']['numTasks'] == 1
				&& $milestone['Tasks']['dropped']['numTasks'] == 1
			) {
				$this->assertTrue(true, "Impossible to fail");
			} elseif ($milestone['Milestone']['id'] == 3
				&& $milestone['Tasks']['open']['numTasks'] == 0
				&& $milestone['Tasks']['in progress']['numTasks'] == 0
				&& $milestone['Tasks']['resolved']['numTasks'] == 0
				&& $milestone['Tasks']['closed']['numTasks'] == 0
				&& $milestone['Tasks']['dropped']['numTasks'] == 0
			) {
				$this->assertTrue(true, "Impossible to fail");
			} else {
				$this->assertTrue(false, "An unexpected milestone ID (".$milestone['Milestone']['id'].") or task list was retrieved");
			}
		}

	}

/**
 * testClosed method
 *
 * @return void
 */
	public function testClosed() {
		$this->_fakeLogin(3);
		$this->testAction('/project/public/milestones/closed', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();

		$this->assertContains('<h1>public <small>Closed Milestones</small></h1>', $this->view);
		$this->assertRegexp('|<a href=".*'.Router::url('/project/public/milestones/view/2').'"|', $this->view);

		$this->assertNotNull($this->vars['milestones']);
		$this->assertEquals(1, count($this->vars['milestones']));

		foreach ($this->vars['milestones'] as $milestone) {
			if ($milestone['Milestone']['id'] == 2
				&& $milestone['Tasks']['open']['numTasks'] == 0
				&& $milestone['Tasks']['in progress']['numTasks'] == 1
				&& $milestone['Tasks']['resolved']['numTasks'] == 1
				&& $milestone['Tasks']['closed']['numTasks'] == 0
				&& $milestone['Tasks']['dropped']['numTasks'] == 0
			) {
				$this->assertTrue(true, "Impossible to fail");
			} else {
				$this->assertTrue(false, "An unexpected milestone ID (".$milestone['Milestone']['id'].") or task list was retrieved");
			}
		}
	}

/**
 * testView method
 *
 * @return void
 */
	public function testView() {
		$this->_fakeLogin(3);
		$this->testAction('/project/public/milestones/view/1', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();

		$this->assertContains('<h1>public <small>'.h(__("Milestone board: '%s'", $this->vars['milestone']['Milestone']['subject'])).'</small></h1>', $this->view);

		$this->assertNotNull($this->vars['backlog']);
		$this->assertEquals(2, count($this->vars['backlog']));
		$this->assertNotNull($this->vars['inProgress']);
		$this->assertEquals(2, count($this->vars['inProgress']));
		$this->assertNotNull($this->vars['completed']);
		$this->assertEquals(2, count($this->vars['completed']));
		$this->assertNotNull($this->vars['iceBox']);
		$this->assertEquals(1, count($this->vars['iceBox']));

	}

/**
 * testPlan method
 *
 * @return void
 */
	public function testPlanNotProjectUser() {
		$this->_fakeLogin(7);
		$this->testAction('/project/private/milestones/plan/4', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}

	public function testPlanProjectGuest() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/milestones/plan/4', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}

	public function testPlanProjectUser() {
		$this->_fakeLogin(1);
		$this->testAction('/project/public/milestones/plan/1', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();

		$this->assertContains('<h1>public <small>'.h(__("Milestone planner: '%s'", $this->vars['milestone']['Milestone']['subject'])).'</small></h1>', $this->view);

		$this->assertNotNull($this->vars['mustHave']);
		$this->assertEquals(2, count($this->vars['mustHave']));
		$this->assertNotNull($this->vars['shouldHave']);
		$this->assertEquals(2, count($this->vars['shouldHave']));
		$this->assertNotNull($this->vars['couldHave']);
		$this->assertEquals(1, count($this->vars['couldHave']));
		$this->assertNotNull($this->vars['mightHave']);
		$this->assertEquals(2, count($this->vars['mightHave']));
		$this->assertNotNull($this->vars['wontHave']);
		$this->assertEquals(2, count($this->vars['wontHave']));
	}

	public function testPlanProjectAdmin() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/milestones/plan/1', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();

		$this->assertContains('<h1>public <small>'.h(__("Milestone planner: '%s'", $this->vars['milestone']['Milestone']['subject'])).'</small></h1>', $this->view);

		$this->assertNotNull($this->vars['mustHave']);
		$this->assertEquals(2, count($this->vars['mustHave']));
		$this->assertNotNull($this->vars['shouldHave']);
		$this->assertEquals(2, count($this->vars['shouldHave']));
		$this->assertNotNull($this->vars['couldHave']);
		$this->assertEquals(1, count($this->vars['couldHave']));
		$this->assertNotNull($this->vars['mightHave']);
		$this->assertEquals(2, count($this->vars['mightHave']));
		$this->assertNotNull($this->vars['wontHave']);
		$this->assertEquals(2, count($this->vars['wontHave']));
	}

	public function testPlanSystemAdmin() {
		$this->_fakeLogin(9);
		$this->testAction('/project/public/milestones/plan/1', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();

		$this->assertContains('<h1>public <small>'.h(__("Milestone planner: '%s'", $this->vars['milestone']['Milestone']['subject'])).'</small></h1>', $this->view);

		$this->assertNotNull($this->vars['mustHave']);
		$this->assertEquals(2, count($this->vars['mustHave']));
		$this->assertNotNull($this->vars['shouldHave']);
		$this->assertEquals(2, count($this->vars['shouldHave']));
		$this->assertNotNull($this->vars['couldHave']);
		$this->assertEquals(1, count($this->vars['couldHave']));
		$this->assertNotNull($this->vars['mightHave']);
		$this->assertEquals(2, count($this->vars['mightHave']));
		$this->assertNotNull($this->vars['wontHave']);
		$this->assertEquals(2, count($this->vars['wontHave']));
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAddNotProjectUser() {
		$this->_fakeLogin(7);
		$this->testAction('/project/private/milestones/add', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}

	public function testAddProjectGuest() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/milestones/add', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}

	public function testAddProjectUserForm() {
		$this->_fakeLogin(1);
		$this->testAction('/project/public/milestones/add', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();
		$this->assertContains('<h1>public <small>New Milestone</small></h1>', $this->view);
		$this->assertRegexp('|<form action=".*'.Router::url('/project/public/milestones/add').'"|', $this->view);
	}

	public function testAddProjectUser() {
		$this->_fakeLogin(1);
		$postData = array(
			'Milestone' => array(
				'subject' => 'A new milestone',
				'description' => 'A new milestone for the project',
				'due' => '2099-02-03',
			)
		);

		$this->testAction('/project/public/milestones/add', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$id = $this->controller->Milestone->getLastInsertId();
		$this->assertRedirect('/project/public/milestones/view/'.$id);

		// Check that it's been created properly
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => $id), 'fields' => array('subject', 'description', 'due'), 'recursive' => -1));
		$this->assertEquals($postData['Milestone'], $milestone['Milestone']);
	}

	public function testAddProjectAdmin() {
		$this->_fakeLogin(1);
		$postData = array(
			'Milestone' => array(
				'subject' => 'A new milestone',
				'description' => 'A new milestone for the project',
				'due' => '2099-02-03',
			)
		);

		$this->testAction('/project/private/milestones/add', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$id = $this->controller->Milestone->getLastInsertId();
		$this->assertRedirect('/project/private/milestones/view/'.$id);

		// Check that it's been created properly
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => $id), 'fields' => array('subject', 'description', 'due'), 'recursive' => -1));
		$this->assertEquals($postData['Milestone'], $milestone['Milestone']);

	}

	public function testAddSystemAdmin() {
		$this->_fakeLogin(5);
		$postData = array(
			'Milestone' => array(
				'subject' => 'A new milestone',
				'description' => 'A new milestone for the project',
				'due' => '2099-02-03',
			)
		);

		$this->testAction('/project/personal/milestones/add', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$id = $this->controller->Milestone->getLastInsertId();
		$this->assertRedirect('/project/personal/milestones/view/'.$id);

		// Check that it's been created properly
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => $id), 'fields' => array('subject', 'description', 'due'), 'recursive' => -1));
		$this->assertEquals($postData['Milestone'], $milestone['Milestone']);
	}

	public function testAddFail() {
		$this->_fakeLogin(5);
		$postData = array(
			'Milestone' => array(
				'subject' => 'A new milestone',
				'description' => 'A new milestone for the project',
				'due' => '2099-02-03',
			)
		);

		$this->controller->Milestone = $this->getMockForModel('Milestone', array('save'));
		$this->controller->Milestone
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("Milestone '<strong></strong>' could not be created. Please try again.");

		$this->testAction('/project/personal/milestones/add', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

	}

/**
 * testEdit method
 *
 * @return void
 */
	public function testEditNotProjectUser() {
		$this->_fakeLogin(7);
		$this->testAction('/project/private/milestones/edit/4', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}

	public function testEditProjectGuest() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/milestones/edit/4', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}

	public function testEditProjectUserForm() {
		$this->_fakeLogin(1);
		$this->testAction('/project/public/milestones/edit/1', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();
		$this->assertContains('<h1>public <small>Edit a Milestone</small></h1>', $this->view);
		$this->assertRegexp('|<form action=".*'.Router::url('/project/public/milestones/edit/1').'"|', $this->view);
	}

	public function testEditProjectUser() {
		$this->_fakeLogin(1);
		$postData = array(
			'Milestone' => array(
				'id' => '1',
				'subject' => 'Changed milestone',
				'description' => 'This has changed',
				'due' => '2099-02-03',
			)
		);

		$this->testAction('/project/public/milestones/edit/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$this->assertRedirect('/project/public/milestones/view/1');

		// Check that it's been updated
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 1), 'fields' => array('id', 'subject', 'description', 'due'), 'recursive' => -1));
		unset($milestone['Milestone']['percent']);
		$this->assertEquals($postData['Milestone'], $milestone['Milestone']);
	}

	public function testEditProjectAdmin() {
		$this->_fakeLogin(1);
		$postData = array(
			'Milestone' => array(
				'id' => 4,
				'subject' => 'Changed milestone',
				'description' => 'This has changed',
				'due' => '2099-02-03',
			)
		);

		$this->testAction('/project/private/milestones/edit/4', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$this->assertRedirect('/project/private/milestones/view/4');

		// Check that it's been updated
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 4), 'fields' => array('id', 'subject', 'description', 'due'), 'recursive' => -1));
		unset($milestone['Milestone']['percent']);
		$this->assertEquals($postData['Milestone'], $milestone['Milestone']);
	}

	public function testEditSystemAdmin() {
		$this->_fakeLogin(5);
		$postData = array(
			'Milestone' => array(
				'id' => '4',
				'subject' => 'Changed milestone',
				'description' => 'This has changed',
				'due' => '2099-02-03',
			)
		);

		$this->testAction('/project/private/milestones/edit/4', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$this->assertRedirect('/project/private/milestones/view/4');

		// Check that it's been updated
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 4), 'fields' => array('id', 'subject', 'description', 'due'), 'recursive' => -1));
		unset($milestone['Milestone']['percent']);
		$this->assertEquals($postData['Milestone'], $milestone['Milestone']);
	}

	public function testEditFail() {
		$this->_fakeLogin(5);
		$postData = array(
			'Milestone' => array(
				'id' => '4',
				'subject' => 'Changed milestone',
				'description' => 'This has changed',
				'due' => '2099-02-03',
			)
		);

		$this->controller->Milestone = $this->getMockForModel('Milestone', array('save'));
		$this->controller->Milestone
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("Milestone '<strong>Longer &lt;i&gt;subject&lt;/i&gt;</strong>' could not be updated. Please try again.");

		$this->testAction('/project/private/milestones/edit/4', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

	}

/**
 * testClose method
 *
 * @return void
 */
	public function testCloseNotLoggedIn() {
		$this->testAction('/project/private/milestones/close/4', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}

	public function testCloseNotProjectUser() {
		$this->_fakeLogin(7);
		$this->testAction('/project/private/milestones/close/4', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}

	public function testCloseProjectGuest() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/milestones/close/4', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}

	public function testCloseProjectUserForm() {
		$this->_fakeLogin(1);
		$this->testAction('/project/public/milestones/close/3', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();
		$this->assertContains('<h1>Close milestone</h1>', $this->view);
		$this->assertRegexp('|<form action=".*'.Router::url('/project/public/milestones/close/3').'"|', $this->view);
	}

	public function testCloseProjectUser() {
		$this->_fakeLogin(1);

		$this->testAction('/project/public/milestones/close/3', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$this->assertRedirect('/project/public/milestones/index');

		// Check that it's been closed
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 3), 'recursive' => -1));
		$this->assertEquals(3, $milestone['Milestone']['id']);
		$this->assertEquals(false, $milestone['Milestone']['is_open']);
	}

	public function testCloseAlreadyClosed() {
		$this->_fakeLogin(1);

		try{
			$this->testAction('/project/public/milestones/close/2', array('return' => 'view', 'method' => 'post'));
			$this->assertFalse(true);
		} catch (NotFoundException $e) {
			$this->assertTrue(true);
		}

		$this->assertAuthorized();

	}

	public function testCloseShiftTasks() {
		
		$this->_fakeLogin(1);

		$postData = array(
			'Milestone' => array('new_milestone' => 2)
		);

		$this->testAction('/project/public/milestones/close/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$this->assertRedirect('/project/public/milestones/index');

		// Check that it's been closed
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('Milestone.id' => 1), 'recursive' => 1));
		$this->assertEquals(1, $milestone['Milestone']['id']);
		$this->assertEquals(false, $milestone['Milestone']['is_open']);

		// Check that all tasks have been shifted
		foreach ($milestone['Task'] as $task) {
			$this->assertContains($task['task_status_id'], array(3, 4));
		}
	}

	public function testCloseShiftTasksFail() {
		
		$this->_fakeLogin(1);

		$postData = array(
			'Milestone' => array('new_milestone' => 2)
		);
		$this->controller->Milestone = $this->getMockForModel('Milestone', array('shiftTasks'));
		$this->controller->Milestone
			->expects($this->once())
			->method('shiftTasks')
			->will($this->returnValue(false));

		$this->testAction('/project/public/milestones/close/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// Check that it's not been closed
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('Milestone.id' => 1), 'recursive' => 1));
		$this->assertEquals(1, $milestone['Milestone']['id']);
		$this->assertEquals(true, $milestone['Milestone']['is_open']);

		// Check that tasks were not shifted
		$this->assertEquals(7, count($milestone['Task']));
	}

	public function testCloseSaveFail() {
		
		$this->_fakeLogin(1);

		$postData = array(
			'Milestone' => array('new_milestone' => 2)
		);
		$this->controller->Milestone = $this->getMockForModel('Milestone', array('save'));
		$this->controller->Milestone
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));

		$this->testAction('/project/public/milestones/close/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// Check that it's not been closed
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('Milestone.id' => 1), 'recursive' => 1));
		$this->assertEquals(1, $milestone['Milestone']['id']);
		$this->assertEquals(true, $milestone['Milestone']['is_open']);

		// Check that tasks were not shifted
		$this->assertEquals(7, count($milestone['Task']));
	}

	public function testCloseProjectAdmin() {
		$this->_fakeLogin(1);

		$this->testAction('/project/private/milestones/close/4', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$this->assertRedirect('/project/private/milestones/index');

		// Check that it's been closed
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 4), 'recursive' => -1));
		$this->assertEquals(4, $milestone['Milestone']['id']);
		$this->assertEquals(false, $milestone['Milestone']['is_open']);
	}

	public function testCloseSystemAdmin() {
		$this->_fakeLogin(5);

		$this->testAction('/project/private/milestones/close/4', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$this->assertRedirect('/project/private/milestones/index');

		// Check that it's been closed
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 4), 'recursive' => -1));
		$this->assertEquals(4, $milestone['Milestone']['id']);
		$this->assertEquals(false, $milestone['Milestone']['is_open']);
	}

/**
 * testReopen method
 *
 * @return void
 */
	public function testReopenAlreadyOpen() {
		try{
			$this->testAction('/project/private/milestones/reopen/4', array('method' => 'get', 'return' => 'contents'));
		} catch(NotFoundException $e) {
			$this->assertTrue(true);
		}
	}
	public function testReopenNotLoggedIn() {
		$this->testAction('/project/private/milestones/reopen/5', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}

	public function testReopenNotProjectUser() {
		$this->_fakeLogin(7);
		$this->testAction('/project/private/milestones/reopen/5', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}

	public function testReopenProjectGuest() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/milestones/reopen/5', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}

	public function testReopenProjectUserForm() {
		$this->_fakeLogin(1);
		$this->testAction('/project/private/milestones/reopen/5', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();
		$this->assertContains('<h1>Re-open milestone</h1>', $this->view);
		$this->assertRegexp('|<form action=".*'.Router::url('/project/private/milestones/reopen/5').'"|', $this->view);
	}

	public function testReopenProjectUser() {
		$this->_fakeLogin(1);

		$this->testAction('/project/private/milestones/reopen/5', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$this->assertRedirect('/project/private/milestones/index');

		// Check that it's been re-opened
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 5), 'recursive' => -1));
		$this->assertEquals($milestone['Milestone']['id'], 5);
		$this->assertEquals($milestone['Milestone']['is_open'], 1);
	}

	public function testReopenFail() {
		$this->_fakeLogin(1);
		$this->controller->Milestone = $this->getMockForModel('Milestone', array('save'));
		$this->controller->Milestone
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("Milestone '<strong>Longer &lt;i&gt;subject&lt;/i&gt;</strong>' could not be updated. Please try again.");

		$this->testAction('/project/private/milestones/reopen/5', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();
	}

	public function testReopenProjectAdmin() {
		$this->_fakeLogin(1);

		$this->testAction('/project/private/milestones/reopen/5', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$this->assertRedirect('/project/private/milestones/index');

		// Check that it's been re-opened
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 5), 'recursive' => -1));
		$this->assertEquals($milestone['Milestone']['id'], 5);
		$this->assertEquals($milestone['Milestone']['is_open'], 1);
	}

	public function testReopenSystemAdmin() {
		$this->_fakeLogin(5);

		$this->testAction('/project/private/milestones/reopen/5', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$this->assertRedirect('/project/private/milestones/index');

		// Check that it's been re-opened
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 5), 'recursive' => -1));
		$this->assertEquals($milestone['Milestone']['id'], 5);
		$this->assertEquals($milestone['Milestone']['is_open'], 1);
	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDeleteNotLoggedIn() {
		$this->testAction('/project/private/milestones/delete/5', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}

	public function testDeleteNotProjectUser() {
		$this->_fakeLogin(7);
		$this->testAction('/project/private/milestones/delete/5', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}

	public function testDeleteProjectGuest() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/milestones/delete/5', array('method' => 'get', 'return' => 'contents'));
		$this->assertNotAuthorized();
	}

	public function testDeleteProjectUserForm() {
		$this->_fakeLogin(1);
		$this->testAction('/project/private/milestones/delete/5', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();
		$this->assertContains('<h1>Delete milestone</h1>', $this->view);
		$this->assertRegexp('|<form action=".*'.Router::url('/project/private/milestones/delete/5').'"|', $this->view);
	}

	public function testDeleteProjectUser() {
		$this->_fakeLogin(1);

		$this->testAction('/project/private/milestones/delete/5', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$this->assertRedirect('/project/private/milestones/index');

		// Check it's been deleted
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 5), 'recursive' => -1));
		$this->assertEquals($milestone, array());
	}

	public function testDeleteProjectAdmin() {
		$this->_fakeLogin(1);

		$this->testAction('/project/private/milestones/delete/5', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$this->assertRedirect('/project/private/milestones/index');

		// Check it's been deleted
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 5), 'recursive' => -1));
		$this->assertEquals($milestone, array());
	}

	public function testDeleteSystemAdmin() {
		$this->_fakeLogin(5);

		$this->testAction('/project/private/milestones/delete/5', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$this->assertRedirect('/project/private/milestones/index');

		// Check it's been deleted
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 5), 'recursive' => -1));
		$this->assertEquals($milestone, array());
	}

	public function testDeleteShiftTasks() {
		
		$this->_fakeLogin(1);

		$postData = array(
			'Milestone' => array('new_milestone' => 2)
		);

		$this->testAction('/project/public/milestones/delete/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the milestone page
		$this->assertRedirect('/project/public/milestones/index');

		// Check that it's been deleted
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('Milestone.id' => 1), 'recursive' => 1));
		$this->assertEquals(array(), $milestone);

		// Check that all tasks have been shifted to milestone 2
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('Milestone.id' => 2), 'recursive' => 1));
		$this->assertEquals(9, count($milestone['Task']));
	}

	public function testDeleteShiftTasksFail() {
		
		$this->_fakeLogin(1);

		$postData = array(
			'Milestone' => array('new_milestone' => 2)
		);
		$this->controller->Milestone = $this->getMockForModel('Milestone', array('shiftTasks'));
		$this->controller->Milestone
			->expects($this->once())
			->method('shiftTasks')
			->will($this->returnValue(false));

		$this->testAction('/project/public/milestones/delete/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// Check that it's not been deleted
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('Milestone.id' => 1), 'recursive' => 1));
		$this->assertEquals(1, $milestone['Milestone']['id']);
		$this->assertEquals(true, $milestone['Milestone']['is_open']);

		// Check that tasks were not shifted
		$this->assertEquals(7, count($milestone['Task']));
	}

	public function testDeleteSaveFail() {
		
		$this->_fakeLogin(1);

		$postData = array(
			'Milestone' => array('new_milestone' => 2)
		);
		$this->controller->Milestone = $this->getMockForModel('Milestone', array('delete'));
		$this->controller->Milestone
			->expects($this->once())
			->method('delete')
			->will($this->returnValue(false));

		$this->testAction('/project/public/milestones/delete/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// Check that it's not been deleted
		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('Milestone.id' => 1), 'recursive' => 1));
		$this->assertEquals(1, $milestone['Milestone']['id']);
		$this->assertEquals(true, $milestone['Milestone']['is_open']);

		// Check that tasks were not shifted
		$this->assertEquals(7, count($milestone['Task']));
	}


	public function testBurndown() {
		
		$this->_fakeLogin(3);
		$this->testAction('/project/public/milestones/burndown/1', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();

		$this->assertEquals(array(
			array(
				'timestamp' => '2014-10-26 16:20:53',
				'open_task_count' => '1',
				'open_minutes_count' => '1',
				'open_points_count' => '1',
				'closed_task_count' => '1',
				'closed_minutes_count' => '1',
				'closed_points_count' => '1'
			),
		), $this->vars['log']);
	}

/**
 * testApiView method
 *
 * @return void
 */
	public function testApiView() {
	}

/**
 * testApiAll method
 *
 * @return void
 */
	public function testApiAll() {
	}

}
