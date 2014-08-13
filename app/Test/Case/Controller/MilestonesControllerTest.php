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

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'open',
			'project' => 'public',
			'named' => array(),
			'pass' => array('public'),
			'plugin' => null
		));
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
		$this->assertContains('<a href="/project/public/milestones/view/1">', $this->view);
		$this->assertContains('<a href="/project/public/milestones/view/3">', $this->view);

		$this->assertNotNull($this->vars['milestones']);
		$this->assertEquals(2, count($this->vars['milestones']));

		foreach ($this->vars['milestones'] as $milestone) {

			if ($milestone['Milestone']['id'] == 1
				&& count($milestone['Task']) == 7
				&& count($milestone['Tasks']['open']) == 2
				&& count($milestone['Tasks']['in_progress']) == 2
				&& count($milestone['Tasks']['resolved']) == 1
				&& count($milestone['Tasks']['completed']) == 1
				&& count($milestone['Tasks']['dropped']) == 1
			) {
				$this->assertTrue(true, "Impossible to fail");
			} elseif ($milestone['Milestone']['id'] == 3
				&& count($milestone['Task']) == 0
				&& count($milestone['Tasks']['open']) == 0
				&& count($milestone['Tasks']['in_progress']) == 0
				&& count($milestone['Tasks']['resolved']) == 0
				&& count($milestone['Tasks']['completed']) == 0
				&& count($milestone['Tasks']['dropped']) == 0
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
		$this->assertContains('<a href="/project/public/milestones/view/2">', $this->view);

		$this->assertNotNull($this->vars['milestones']);
		$this->assertEquals(1, count($this->vars['milestones']));

		foreach ($this->vars['milestones'] as $milestone) {
			if ($milestone['Milestone']['id'] == 2
				&& count($milestone['Task']) == 2
				&& count($milestone['Tasks']['open']) == 0
				&& count($milestone['Tasks']['in_progress']) == 1
				&& count($milestone['Tasks']['resolved']) == 1
				&& count($milestone['Tasks']['completed']) == 0
				&& count($milestone['Tasks']['dropped']) == 0
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

		$this->assertContains('<h1>public <small>Milestone board</small></h1>', $this->view);

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

		$this->assertContains('<h1>public <small>Milestone task planner</small></h1>', $this->view);

		$this->assertNotNull($this->vars['mustHave']);
		$this->assertEquals(2, count($this->vars['mustHave']));
		$this->assertNotNull($this->vars['shouldHave']);
		$this->assertEquals(2, count($this->vars['shouldHave']));
		$this->assertNotNull($this->vars['couldHave']);
		$this->assertEquals(1, count($this->vars['couldHave']));
		$this->assertNotNull($this->vars['mightHave']);
		$this->assertEquals(2, count($this->vars['mightHave']));
		$this->assertNotNull($this->vars['wontHave']);
		$this->assertEquals(1, count($this->vars['wontHave']));
	}

	public function testPlanProjectAdmin() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/milestones/plan/1', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();

		$this->assertContains('<h1>public <small>Milestone task planner</small></h1>', $this->view);

		$this->assertNotNull($this->vars['mustHave']);
		$this->assertEquals(2, count($this->vars['mustHave']));
		$this->assertNotNull($this->vars['shouldHave']);
		$this->assertEquals(2, count($this->vars['shouldHave']));
		$this->assertNotNull($this->vars['couldHave']);
		$this->assertEquals(1, count($this->vars['couldHave']));
		$this->assertNotNull($this->vars['mightHave']);
		$this->assertEquals(2, count($this->vars['mightHave']));
		$this->assertNotNull($this->vars['wontHave']);
		$this->assertEquals(1, count($this->vars['wontHave']));
	}

	public function testPlanSystemAdmin() {
		$this->_fakeLogin(9);
		$this->testAction('/project/public/milestones/plan/1', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();

		$this->assertContains('<h1>public <small>Milestone task planner</small></h1>', $this->view);

		$this->assertNotNull($this->vars['mustHave']);
		$this->assertEquals(2, count($this->vars['mustHave']));
		$this->assertNotNull($this->vars['shouldHave']);
		$this->assertEquals(2, count($this->vars['shouldHave']));
		$this->assertNotNull($this->vars['couldHave']);
		$this->assertEquals(1, count($this->vars['couldHave']));
		$this->assertNotNull($this->vars['mightHave']);
		$this->assertEquals(2, count($this->vars['mightHave']));
		$this->assertNotNull($this->vars['wontHave']);
		$this->assertEquals(1, count($this->vars['wontHave']));
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
		$this->assertContains('<form action="/project/public/milestones/add"', $this->view);
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

		// We should be redirected to the new project page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'view',
			'project' => 'public',
			'named' => array(),
			'pass' => array('public', '6'),
			'plugin' => null
		));

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

		// We should be redirected to the new project page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'view',
			'project' => 'private',
			'named' => array(),
			'pass' => array('private', '6'),
			'plugin' => null
		));

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

		// We should be redirected to the new project page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'view',
			'project' => 'personal',
			'named' => array(),
			'pass' => array('personal', '6'),
			'plugin' => null
		));

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
		$this->assertContains('<form action="/project/public/milestones/edit/1"', $this->view);
	}

	public function testEditProjectUser() {
		$this->_fakeLogin(1);
		$postData = array(
			'Milestone' => array(
				'subject' => 'A new milestone',
				'description' => 'A new milestone for the project',
				'due' => '2099-02-03',
			)
		);

		$this->testAction('/project/public/milestones/edit/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the new project page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'view',
			'project' => 'public',
			'named' => array(),
			'pass' => array('public', '1'),
			'plugin' => null
		));

	}

	public function testEditProjectAdmin() {
		$this->_fakeLogin(1);
		$postData = array(
			'Milestone' => array(
				'subject' => 'A new milestone',
				'description' => 'A new milestone for the project',
				'due' => '2099-02-03',
			)
		);

		$this->testAction('/project/private/milestones/edit/4', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the new project page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'view',
			'project' => 'private',
			'named' => array(),
			'pass' => array('private', '4'),
			'plugin' => null
		));

	}

	public function testEditSystemAdmin() {
		$this->_fakeLogin(5);
		$postData = array(
			'Milestone' => array(
				'subject' => 'A new milestone',
				'description' => 'A new milestone for the project',
				'due' => '2099-02-03',
			)
		);

		$this->testAction('/project/private/milestones/edit/4', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the new project page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'view',
			'project' => 'private',
			'named' => array(),
			'pass' => array('private', '4'),
			'plugin' => null
		));

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
		$this->assertContains('<form action="/project/public/milestones/close/3"', $this->view);
	}

	public function testCloseProjectUser() {
		$this->_fakeLogin(1);

		$this->testAction('/project/public/milestones/close/3', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the new project page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'index',
			'project' => 'public',
			'named' => array(),
			'pass' => array('public'),
			'plugin' => null
		));

	}

	public function testCloseProjectAdmin() {
		$this->_fakeLogin(1);

		$this->testAction('/project/private/milestones/close/4', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the new project page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'index',
			'project' => 'private',
			'named' => array(),
			'pass' => array('private'),
			'plugin' => null
		));

	}

	public function testCloseSystemAdmin() {
		$this->_fakeLogin(5);

		$this->testAction('/project/private/milestones/close/4', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the new project page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'index',
			'project' => 'private',
			'named' => array(),
			'pass' => array('private'),
			'plugin' => null
		));
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
		$this->assertContains('<form action="/project/private/milestones/reopen/5"', $this->view);
	}

	public function testReopenProjectUser() {
		$this->_fakeLogin(1);

		$this->testAction('/project/private/milestones/reopen/5', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the new project page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'index',
			'project' => 'private',
			'named' => array(),
			'pass' => array('private'),
			'plugin' => null
		));

		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 5), 'recursive' => -1));
		$this->assertEquals($milestone['Milestone']['id'], 5);
		$this->assertEquals($milestone['Milestone']['is_open'], 1);
	}

	public function testReopenProjectAdmin() {
		$this->_fakeLogin(1);

		$this->testAction('/project/private/milestones/reopen/5', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the new project page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'index',
			'project' => 'private',
			'named' => array(),
			'pass' => array('private'),
			'plugin' => null
		));

		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 5), 'recursive' => -1));
		$this->assertEquals($milestone['Milestone']['id'], 5);
		$this->assertEquals($milestone['Milestone']['is_open'], 1);
	}

	public function testReopenSystemAdmin() {
		$this->_fakeLogin(5);

		$this->testAction('/project/private/milestones/reopen/5', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the new project page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'index',
			'project' => 'private',
			'named' => array(),
			'pass' => array('private'),
			'plugin' => null
		));

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
		$this->assertContains('<form action="/project/private/milestones/delete/5"', $this->view);
	}

	public function testDeleteProjectUser() {
		$this->_fakeLogin(1);

		$this->testAction('/project/private/milestones/delete/5', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the new project page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'index',
			'project' => 'private',
			'named' => array(),
			'pass' => array('private'),
			'plugin' => null
		));

		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 5), 'recursive' => -1));
		$this->assertEquals($milestone, array());
	}

	public function testDeleteProjectAdmin() {
		$this->_fakeLogin(1);

		$this->testAction('/project/private/milestones/delete/5', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the new project page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'index',
			'project' => 'private',
			'named' => array(),
			'pass' => array('private'),
			'plugin' => null
		));

		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 5), 'recursive' => -1));
		$this->assertEquals($milestone, array());
	}

	public function testDeleteSystemAdmin() {
		$this->_fakeLogin(5);

		$this->testAction('/project/private/milestones/delete/5', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

		// We should be redirected to the new project page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);

		// PHP can parse the http:// url and Router can work out where it goes...
		$url = parse_url($this->headers['Location']);
		$url = Router::parse($url['path']);
		$this->assertEquals($url, array(
			'controller' => 'milestones',
			'action' => 'index',
			'project' => 'private',
			'named' => array(),
			'pass' => array('private'),
			'plugin' => null
		));

		$milestone = $this->controller->Milestone->find('first', array('conditions' => array('id' => 5), 'recursive' => -1));
		$this->assertEquals($milestone, array());
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
