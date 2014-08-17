<?php
App::uses('CollaboratorsController', 'Controller');
require_once(__DIR__ . DS . 'AppControllerTest.php');


/**
 * CollaboratorsController Test Case
 *
 */
class CollaboratorsControllerTestCase extends AppControllerTest {

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
		parent::setUp("Collaborators");
	}


/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Collaborators);

		parent::tearDown();
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndexNotLoggedIn() {

		// Cannot see the page when not logged in
		$this->testAction('/project/private/collaborators', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function __testIndex($userId, $url, $expected, $expectAdmin = false) {

		$this->_fakeLogin($userId);
		$ret = $this->testAction($url, array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

		// Check the page content looks roughly OK
		$this->assertContains('<h1>private <small>Collaborators working on the project</small></h1>', $this->view);
		$this->assertNotNull($this->vars['collaborators']);
		$this->assertNotNull($this->vars['collaborators'][0]);
		$this->assertNotNull($this->vars['collaborators'][1]);
		$this->assertNotNull($this->vars['collaborators'][2]);

		// Check that they do or do not have links to change collaborators
		if($expectAdmin) {
			$this->assertContains('title="'.__('Demote user').'"', $this->view);	
		} else {
			$this->assertNotContains('title="'.__('Demote user').'"', $this->view);	
		}

		// Check the collaborator list is correct
		$collabIds = array_map(function($a){return $a['Collaborator']['user_id'];}, $this->vars['collaborators'][0]);
		$this->assertEqual($expected[0], $collabIds);

		$collabIds = array_map(function($a){return $a['Collaborator']['user_id'];}, $this->vars['collaborators'][1]);
		$this->assertEqual($expected[1], $collabIds);

		$collabIds = array_map(function($a){return $a['Collaborator']['user_id'];}, $this->vars['collaborators'][2]);
		$this->assertEqual($expected[2], $collabIds);

	}

	public function testIndexSystemAdmin() {
		$this->__testIndex(5, '/project/private/collaborators', array(array(3), array(4, 10), array(1, 5)), true);
	}
	public function testIndexProjectAdmin() {
		$this->__testIndex(1, '/project/private/collaborators', array(array(3), array(4, 10), array(1, 5)), true);
	}
	public function testIndexProjectUser() {
		$this->__testIndex(10, '/project/private/collaborators', array(array(3), array(4, 10), array(1, 5)), false);
	}
	public function testIndexProjectGuest() {
		$this->__testIndex(3, '/project/private/collaborators', array(array(3), array(4, 10), array(1, 5)), false);
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAddNotLoggedIn() {
		$ret = $this->testAction('/project/private/collaborators/add', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAddProjectUser() {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/project/private/collaborators/add', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAddProjectGuest() {
		$this->_fakeLogin(3);
		$ret = $this->testAction('/project/private/collaborators/add', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAddProjectAdmin() {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/private/collaborators/add', array('method' => 'post', 'return' => 'view'));
		$this->assertAuthorized();
	}

	public function testAddSystemAdmin() {
		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/collaborators/add', array('method' => 'post', 'return' => 'view'));
		$this->assertAuthorized();
	}

	public function testAddGetFail() {
		$this->_fakeLogin(1);
		try{
			$ret = $this->testAction('/project/private/collaborators/add', array('method' => 'get', 'return' => 'view'));
		} catch (MethodNotAllowedException $e) {
			$this->assertTrue(true);
			return;
		} catch (Exception $e) {
			$this->assertFalse(true, "Incorrect exception thrown");
			return;
		}
		$this->assertFalse(true, "No exception thrown");

	}

	public function testAddNonexistentUser() {
		$this->_fakeLogin(1);
		$postData = array(
			'Collaborator' => array(
				'name' => 'Jimminy Cricket (fooble@example.com)',
			)
		);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("The user specified does not exist. Please try again.");
		$ret = $this->testAction('/project/private/collaborators/add', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertAuthorized();
	}

	public function testAddAlreadyHere() {
		$this->_fakeLogin(1);
		$postData = array(
			'Collaborator' => array(
				'name' => 'Mr Admin (mr.admin@example.com)',
			)
		);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("The user specified is already collaborating in this project.");
		$ret = $this->testAction('/project/private/collaborators/add', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertAuthorized();
	}

	public function testAddSuccess() {
		$this->_fakeLogin(1);
		$postData = array(
			'Collaborator' => array(
				'name' => 'Fake Name [admin-no-projects@example.com]',
			)
		);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("An admin with no projects has been added to the project");
		$ret = $this->testAction('/project/private/collaborators/add', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertAuthorized();
		$collab = $this->controller->Collaborator->findById($this->controller->Collaborator->getLastInsertId());
		$this->assertEquals(9, $collab['Collaborator']['user_id']);
		$this->assertEquals(1, $collab['Collaborator']['project_id']);
	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDeleteNotLoggedIn() {
		$ret = $this->testAction('/project/private/collaborators/delete/11', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testDeleteProjectUser() {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/project/private/collaborators/delete/11', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testDeleteProjectGuest() {
		$this->_fakeLogin(3);
		$ret = $this->testAction('/project/private/collaborators/delete/11', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testDeleteProjectAdmin() {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/private/collaborators/delete/11', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
	}

	public function testDeleteSystemAdmin() {
		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/collaborators/delete/11', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
	}

	public function testDeleteNonexistentCollaborator() {
		$this->_fakeLogin(1);
		try{
			$ret = $this->testAction('/project/public/collaborators/delete/999', array('method' => 'get', 'return' => 'view'));
		} catch (NotFoundException $e) {
			$this->assertTrue(true);
			return;
		} catch (Exception $e) {
			$this->assertFalse(true, "Incorrect exception thrown");
			return;
		}
		$this->assertFalse(true, "No exception thrown");
	}

	public function testDeleteNoMoreAdmins() {
		$this->_fakeLogin(5);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("<h4 class='alert-heading'>The Request could not be completed:</h4>There must be at least one admin in the project.");
		$ret = $this->testAction('/project/public/collaborators/delete/9', array('method' => 'post', 'return' => 'view'));
		$this->assertAuthorized();
		$collab = $this->controller->Collaborator->findById(6);
		$this->assertEquals(6, $collab['Collaborator']['id']);
	}

	public function testDeleteSuccess() {
		$this->_fakeLogin(9);
		$ret = $this->testAction('/project/public/collaborators/delete/6', array('method' => 'post', 'return' => 'view'));
		$this->assertAuthorized();
		$collab = $this->controller->Collaborator->findById(6);
		$this->assertEquals(array(), $collab);
		$this->assertRedirect('/project/public/collaborators');
	}


	private function __testChangeAccessLevel($userId, $url, $collabId, $level) {
		$this->_fakeLogin($userId);
		$ret = $this->testAction($url, array('method' => 'post', 'return' => 'view'));
		$this->assertAuthorized();
		$collab = $this->controller->Collaborator->findById($collabId);
		$this->assertEquals($level, $collab['Collaborator']['access_level']);
		$this->assertRedirect('/project/public/collaborators');
	}

	public function testMakeAdminNotLoggedIn () {
		$ret = $this->testAction('/project/public/collaborators/makeadmin/6', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeAdminNotCollaborator () {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/project/public/collaborators/makeadmin/6', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeAdminProjectGuest () {
		$this->_fakeLogin(8);
		$ret = $this->testAction('/project/public/collaborators/makeadmin/6', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeAdminProjectUser () {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/public/collaborators/makeadmin/9', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeAdminProjectAdmin () {
		$ret = $this->__testChangeAccessLevel(2, '/project/public/collaborators/makeadmin/6', 6, 2);
	}
	public function testMakeAdminSystemAdmin () {
		$ret = $this->__testChangeAccessLevel(5, '/project/public/collaborators/makeadmin/6', 6, 2);
	}

	public function testMakeUserNotLoggedIn () {
		$ret = $this->testAction('/project/public/collaborators/makeuser/6', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeUserNotCollaborator () {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/project/public/collaborators/makeuser/6', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeUserProjectGuest () {
		$this->_fakeLogin(8);
		$ret = $this->testAction('/project/public/collaborators/makeuser/6', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeUserProjectUser () {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/public/collaborators/makeuser/6', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeUserTooFewAdmins () {
		$this->_fakeLogin(5);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("<h4 class='alert-heading'>The Request could not be completed:</h4>There must be at least one admin in the project.");
		$ret = $this->testAction('/project/personal/collaborators/makeuser/7', array('method' => 'post', 'return' => 'view'));
		$this->assertAuthorized();
		$this->assertRedirect('/project/personal/collaborators');

	}
	public function testMakeUserProjectAdmin () {
		$ret = $this->__testChangeAccessLevel(2, '/project/public/collaborators/makeuser/6', 6, 1);
	}
	public function testMakeUserSystemAdmin () {
		$ret = $this->__testChangeAccessLevel(5, '/project/public/collaborators/makeuser/6', 6, 1);
	}


	public function testMakeGuestNotLoggedIn () {
		$ret = $this->testAction('/project/public/collaborators/makeguest/6', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeGuestNotCollaborator () {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/project/public/collaborators/makeguest/6', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeGuestProjectGuest () {
		$this->_fakeLogin(8);
		$ret = $this->testAction('/project/public/collaborators/makeguest/6', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeGuestProjectUser () {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/public/collaborators/makeguest/6', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeGuestProjectAdmin () {
		$ret = $this->__testChangeAccessLevel(2, '/project/public/collaborators/makeguest/6', 6, 0);
	}
	public function testMakeGuestSystemAdmin () {
		$ret = $this->__testChangeAccessLevel(5, '/project/public/collaborators/makeguest/6', 6, 0);
	}
	public function testMakeGuestTooFewAdmins () {
		$this->_fakeLogin(5);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("<h4 class='alert-heading'>The Request could not be completed:</h4>There must be at least one admin in the project.");
		$ret = $this->testAction('/project/personal/collaborators/makeguest/7', array('method' => 'post', 'return' => 'view'));
		$this->assertAuthorized();
		$this->assertRedirect('/project/personal/collaborators');
	}
}
