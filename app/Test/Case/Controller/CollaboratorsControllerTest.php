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
		'app.milestone_burndown_log',
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
		'app.collaborating_team',
		'app.group_collaborating_team',
		'app.team',
		'app.teams_user',
		'app.project_burndown_log',
		'app.project_group',
		'app.project_groups_project',
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

	public function testAddNoEmailAddress() {
		$this->_fakeLogin(1);
		$postData = array(
			'Collaborator' => array(
				'name' => 'Just add some user will you',
			)
		);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("Failed to find an email address in your query. Please try again."));
		$ret = $this->testAction('/project/private/collaborators/add', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertAuthorized();
	}

	public function testAddNonexistentUser() {
		$this->_fakeLogin(1);
		$postData = array(
			'Collaborator' => array(
				'name' => 'Jimminy Cricket [fooble@example.com]',
			)
		);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("The user specified does not exist. Please try again."));
		$ret = $this->testAction('/project/private/collaborators/add', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertAuthorized();
	}

	public function testAddAlreadyHere() {
		$this->_fakeLogin(1);
		$postData = array(
			'Collaborator' => array(
				'name' => 'Mr Admin [mr.admin@example.com]',
			)
		);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("The user specified is already collaborating in this project.");
		$ret = $this->testAction('/project/private/collaborators/add', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertAuthorized();
	}

	public function testAddFail() {
		$this->_fakeLogin(1);
		$postData = array(
			'Collaborator' => array(
				'name' => 'Fake Name [admin-no-projects@example.com]',
			)
		);
		$this->controller->Collaborator = $this->getMockForModel('Collaborator', array('save'));
		$this->controller->Collaborator
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("An admin with no projects could not be added to the project. Please try again.");
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
		$ret = $this->testAction('/project/private/collaborators/delete/10', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testDeleteProjectUser() {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/project/private/collaborators/delete/3', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testDeleteProjectGuest() {
		$this->_fakeLogin(3);
		$ret = $this->testAction('/project/private/collaborators/delete/10', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testDeleteProjectAdmin() {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/private/collaborators/delete/10', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
	}

	public function testDeleteForm() {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/private/collaborators/delete/10', array('method' => 'get', 'return' => 'view'));
		$this->assertRegexp('/<form action=".*\/project\/private\/collaborators\/delete\/10"/', $this->view);
	}

	public function testDeleteSystemAdmin() {
		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/collaborators/delete/10', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
	}

	public function testDeleteNotCollaborating() {
		$this->_fakeLogin(1);
		try{
			$ret = $this->testAction('/project/private/collaborators/delete/2', array('method' => 'get', 'return' => 'view'));
		} catch (NotFoundException $e) {
			$this->assertEquals(__("User with ID %d is not a collaborator on the project", 2), $e->getMessage());
			return;
		} catch (Exception $e) {
			$this->assertFalse(true, "Incorrect exception thrown: ".$e->getMessage());
			return;
		}
		$this->assertFalse(true, "No exception thrown");
	}

	public function testDeleteNonexistentCollaborator() {
		$this->_fakeLogin(1);
		try{
			$ret = $this->testAction('/project/public/collaborators/delete/999', array('method' => 'get', 'return' => 'view'));
		} catch (NotFoundException $e) {
			$this->assertEquals(__("User with ID %d does not exist", 999), $e->getMessage());
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
		$ret = $this->testAction('/project/public/collaborators/delete/2', array('method' => 'post', 'return' => 'view'));
		$this->assertAuthorized();
		$collab = $this->controller->Collaborator->findById(9);
		$this->assertEquals(9, $collab['Collaborator']['id']);
	}

	public function testDeleteSuccess() {
		$this->_fakeLogin(9);
		$ret = $this->testAction('/project/public/collaborators/delete/1', array('method' => 'post', 'return' => 'view'));
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
		$ret = $this->testAction('/project/public/collaborators/makeadmin/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeAdminNotCollaborator () {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/project/public/collaborators/makeadmin/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeAdminProjectGuest () {
		$this->_fakeLogin(8);
		$ret = $this->testAction('/project/public/collaborators/makeadmin/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeAdminProjectUser () {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/public/collaborators/makeadmin/2', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeAdminProjectAdmin () {
		$ret = $this->__testChangeAccessLevel(2, '/project/public/collaborators/makeadmin/1', 6, 2);
	}
	public function testMakeAdminSystemAdmin () {
		$ret = $this->__testChangeAccessLevel(5, '/project/public/collaborators/makeadmin/1', 6, 2);
	}

	public function testMakeAdminFail () {
		$this->_fakeLogin(5);
		$this->controller->Collaborator = $this->getMockForModel('Collaborator', array('save'));
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("Permissions level for 'Mrs Smith' could not be updated. Please try again.");
		$this->controller->Collaborator
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));
		$ret = $this->testAction('/project/public/collaborators/makeadmin/2', array('method' => 'post', 'return' => 'view'));

	}

	public function testMakeUserNotLoggedIn () {
		$ret = $this->testAction('/project/public/collaborators/makeuser/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeUserNotCollaborator () {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/project/public/collaborators/makeuser/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeUserProjectGuest () {
		$this->_fakeLogin(8);
		$ret = $this->testAction('/project/public/collaborators/makeuser/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeUserProjectUser () {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/public/collaborators/makeuser/1', array('method' => 'post', 'return' => 'view'));
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

	public function testMakeUserNotTooFewAdmins () {
		$this->_fakeLogin(5);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("Permissions level successfully changed for 'Mr Smith'");
		$ret = $this->testAction('/project/private/collaborators/makeuser/1', array('method' => 'post', 'return' => 'view'));
		$this->assertAuthorized();
		$this->assertRedirect('/project/private/collaborators');

	}

	public function testMakeUserProjectAdmin () {
		$ret = $this->__testChangeAccessLevel(2, '/project/public/collaborators/makeuser/1', 6, 1);
	}

	public function testMakeUserSystemAdmin () {
		$ret = $this->__testChangeAccessLevel(5, '/project/public/collaborators/makeuser/1', 6, 1);
	}


	public function testMakeGuestNotLoggedIn () {
		$ret = $this->testAction('/project/public/collaborators/makeguest/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testMakeGuestNotCollaborator () {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/project/public/collaborators/makeguest/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testMakeGuestProjectGuest () {
		$this->_fakeLogin(8);
		$ret = $this->testAction('/project/public/collaborators/makeguest/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testMakeGuestProjectUser () {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/public/collaborators/makeguest/1', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testMakeGuestInvalidCollaborator () {
		$this->_fakeLogin(2);
		try{
			$ret = $this->testAction('/project/private/collaborators/makeguest/2', array('method' => 'post', 'return' => 'view'));
		} catch(NotFoundException $e) {
			$this->assertEquals(__("User with ID %d is not a collaborator on the project", 2), $e->getMessage());
		} catch(Exception $e) {
			$this->assertTrue(false, "Incorrect exception thrown: ".$e->getMessage());
		}
	}

	public function testMakeGuestInvalidUser () {
		$this->_fakeLogin(2);

		try {
			$ret = $this->testAction('/project/public/collaborators/makeguest/999', array('method' => 'post', 'return' => 'view'));
		} catch(NotFoundException $e) {
			$this->assertEquals(__("User with ID %d does not exist", 999), $e->getMessage());
		} catch(Exception $e) {
			$this->assertTrue(false, "Incorrect exception thrown: ".$e->getMessage());
		}
	}

	public function testMakeGuestProjectAdmin () {
		$ret = $this->__testChangeAccessLevel(2, '/project/public/collaborators/makeguest/1', 6, 0);
	}

	public function testMakeGuestSystemAdmin () {
		$ret = $this->__testChangeAccessLevel(5, '/project/public/collaborators/makeguest/1', 6, 0);
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

	private function __testChangeTeamAccessLevel($userId, $url, $teamCollabId, $level) {
		$this->_fakeLogin($userId);
		$ret = $this->testAction($url, array('method' => 'post', 'return' => 'view'));
		$this->assertAuthorized();
		$collab = $this->controller->CollaboratingTeam->findById($teamCollabId);
		$this->assertEquals($level, $collab['CollaboratingTeam']['access_level']);
		$this->assertRedirect('/project/perl-1/collaborators');
	}

	public function testMakeTeamAdminNotLoggedIn () {
		$ret = $this->testAction('/project/perl-1/collaborators/team_makeadmin/4', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeTeamAdminNotCollaboratingTeam () {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/project/perl-1/collaborators/team_makeadmin/4', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeTeamAdminProjectGuest () {
		$this->_fakeLogin(8);
		$ret = $this->testAction('/project/perl-1/collaborators/team_makeadmin/4', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeTeamAdminProjectUser () {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/perl-1/collaborators/team_makeadmin/4', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeTeamAdminProjectAdmin () {
		$ret = $this->__testChangeTeamAccessLevel(2, '/project/perl-1/collaborators/team_makeadmin/4', 1, 2);
	}
	public function testMakeTeamAdminSystemAdmin () {
		$ret = $this->__testChangeTeamAccessLevel(5, '/project/perl-1/collaborators/team_makeadmin/4', 1, 2);
	}

	public function testMakeTeamAdminFail () {
		$this->_fakeLogin(5);
		$this->controller->CollaboratingTeam = $this->getMockForModel('CollaboratingTeam', array('save'));
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("Permissions level for 'perl_developers' could not be updated. Please try again.");
		$this->controller->CollaboratingTeam
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));
		$ret = $this->testAction('/project/perl-1/collaborators/team_makeadmin/4', array('method' => 'post', 'return' => 'view'));

	}

	public function testMakeTeamUserNotLoggedIn () {
		$ret = $this->testAction('/project/perl-1/collaborators/team_makeuser/4', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeTeamUserNotCollaboratingTeam () {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/project/perl-1/collaborators/team_makeuser/4', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeTeamUserProjectGuest () {
		$this->_fakeLogin(8);
		$ret = $this->testAction('/project/perl-1/collaborators/team_makeuser/4', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}
	public function testMakeTeamUserProjectUser () {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/perl-1/collaborators/team_makeuser/4', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testMakeTeamUserProjectAdmin () {
		$ret = $this->__testChangeTeamAccessLevel(2, '/project/perl-1/collaborators/team_makeuser/4', 1, 1);
	}

	public function testMakeTeamUserSystemAdmin () {
		$ret = $this->__testChangeTeamAccessLevel(5, '/project/perl-1/collaborators/team_makeuser/4', 1, 1);
	}


	public function testMakeTeamGuestNotLoggedIn () {
		$ret = $this->testAction('/project/perl-1/collaborators/team_makeguest/4', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testMakeTeamGuestNotCollaboratingTeam () {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/project/perl-1/collaborators/team_makeguest/4', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testMakeTeamGuestProjectGuest () {
		$this->_fakeLogin(8);
		$ret = $this->testAction('/project/perl-1/collaborators/team_makeguest/4', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testMakeTeamGuestProjectUser () {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/perl-1/collaborators/team_makeguest/4', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testMakeTeamGuestProjectAdmin () {
		$ret = $this->__testChangeTeamAccessLevel(2, '/project/perl-1/collaborators/team_makeguest/4', 1, 0);
	}

	public function testMakeTeamGuestSystemAdmin () {
		$ret = $this->__testChangeTeamAccessLevel(5, '/project/perl-1/collaborators/team_makeguest/4', 1, 0);
	}

	public function testMakeTeamGuestInvalidCollaborator () {
		$this->_fakeLogin(2);
		try{
			$ret = $this->testAction('/project/perl-1/collaborators/team_makeguest/3', array('method' => 'post', 'return' => 'view'));
		} catch(NotFoundException $e) {
			$this->assertEquals(__("The team with ID %d is not collaborating on the project", 3), $e->getMessage());
			return;
		} catch(Exception $e) {
			$this->assertTrue(false, "Incorrect exception thrown: ".$e->getMessage());
		}
		$this->assertTrue(false, "No exception thrown");
	}

	public function testMakeTeamGuestInvalidTeam () {
		$this->_fakeLogin(2);

		try {
			$ret = $this->testAction('/project/perl-1/collaborators/team_makeguest/999', array('method' => 'post', 'return' => 'view'));
		} catch(NotFoundException $e) {
			$this->assertEquals(__("The team with ID %d does not exist", 999), $e->getMessage());
			return;
		} catch(Exception $e) {
			$this->assertTrue(false, "Incorrect exception thrown: ".$e->getMessage());
		}
		$this->assertTrue(false, "No exception thrown");
	}


	public function testAddTeamNotLoggedIn() {
		$ret = $this->testAction('/project/private/collaborators/team_add', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAddTeamProjectUser() {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/project/private/collaborators/team_add', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAddTeamProjectGuest() {
		$this->_fakeLogin(3);
		$ret = $this->testAction('/project/private/collaborators/team_add', array('method' => 'post', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAddTeamProjectAdmin() {
		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/private/collaborators/team_add', array('method' => 'post', 'return' => 'view'));
		$this->assertAuthorized();
	}

	public function testAddTeamSystemAdmin() {
		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/collaborators/team_add', array('method' => 'post', 'return' => 'view'));
		$this->assertAuthorized();
	}

	public function testAddTeamGetFail() {
		$this->_fakeLogin(1);
		try{
			$ret = $this->testAction('/project/private/collaborators/team_add', array('method' => 'get', 'return' => 'view'));
		} catch (MethodNotAllowedException $e) {
			$this->assertTrue(true);
			return;
		} catch (Exception $e) {
			$this->assertFalse(true, "Incorrect exception thrown");
			return;
		}
		$this->assertFalse(true, "No exception thrown");

	}

	public function testAddTeamNonexistentTeam() {
		$this->_fakeLogin(1);
		$postData = array(
			'Collaborator' => array(
				'name' => 'FakeTeamThatDoesNotExist',
			)
		);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("The team specified does not exist. Please try again."));
		$ret = $this->testAction('/project/private/collaborators/team_add', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertAuthorized();
	}

	public function testAddTeamAlreadyHere() {
		$this->_fakeLogin(2);
		$postData = array(
			'Collaborator' => array(
				'name' => 'perl_developers',
			)
		);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("The team specified is already collaborating in this project."));
		$ret = $this->testAction('/project/perl-1/collaborators/team_add', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertAuthorized();
	}

	public function testAddTeamFail() {
		$this->_fakeLogin(1);
		$postData = array(
			'Collaborator' => array(
				'name' => 'php_developers',
			)
		);
		$this->controller->CollaboratingTeam = $this->getMockForModel('CollaboratingTeam', array('save'));
		$this->controller->CollaboratingTeam
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("%s could not be added to the project. Please try again.", "php_developers"));
		$ret = $this->testAction('/project/private/collaborators/team_add', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertAuthorized();
	}

	public function testAddTeamSuccess() {
		$this->_fakeLogin(1);
		$postData = array(
			'Collaborator' => array(
				'name' => 'php_developers',
			)
		);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("%s has been added to the project", "php_developers"));

		$ret = $this->testAction('/project/private/collaborators/team_add', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertAuthorized();
		$collab = $this->controller->CollaboratingTeam->findById($this->controller->CollaboratingTeam->getLastInsertId());
		$this->assertEquals(1, $collab['CollaboratingTeam']['team_id']);
		$this->assertEquals(1, $collab['CollaboratingTeam']['project_id']);
	}

/**
 * testDeleteTeam method
 *
 * @return void
 */
	public function testDeleteTeamNotLoggedIn() {
		$ret = $this->testAction('/project/perl-1/collaborators/team_delete/4', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testDeleteTeamProjectUser() {
		$this->_fakeLogin(10);
		$ret = $this->testAction('/project/perl-1/collaborators/team_delete/4', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testDeleteTeamProjectGuest() {
		$this->_fakeLogin(3);
		$ret = $this->testAction('/project/perl-1/collaborators/team_delete/4', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testDeleteTeamProjectAdmin() {
		$this->_fakeLogin(2);
		$ret = $this->testAction('/project/perl-1/collaborators/team_delete/4', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
	}

	public function testDeleteTeamSystemAdmin() {
		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/perl-1/collaborators/team_delete/4', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
	}

	public function testDeleteTeamNotCollaborating() {
		$this->_fakeLogin(2);
		try{
			$ret = $this->testAction('/project/perl-1/collaborators/team_delete/1', array('method' => 'get', 'return' => 'view'));
		} catch (NotFoundException $e) {
			$this->assertEquals(__("Team with ID %d is not collaborating on the project", 1), $e->getMessage());
			return;
		} catch (Exception $e) {
			$this->assertFalse(true, "Incorrect exception thrown: ".$e->getMessage());
			return;
		}
		$this->assertFalse(true, "No exception thrown");
	}

	public function testDeleteTeamNonexistentTeam() {
		$this->_fakeLogin(2);
		try{
			$ret = $this->testAction('/project/perl-1/collaborators/team_delete/999', array('method' => 'get', 'return' => 'view'));
		} catch (NotFoundException $e) {
			$this->assertEquals(__("Team with ID %d does not exist", 999), $e->getMessage());
			return;
		} catch (Exception $e) {
			$this->assertFalse(true, "Incorrect exception thrown: ".$e->getMessage());
			return;
		}
		$this->assertFalse(true, "No exception thrown");
	}

	public function testDeleteTeamSuccess() {
		$this->_fakeLogin(2);
		$ret = $this->testAction('/project/perl-1/collaborators/team_delete/4', array('method' => 'post', 'return' => 'view'));
		$this->assertAuthorized();
		$collab = $this->controller->CollaboratingTeam->findById(1);
		$this->assertEquals(array(), $collab);
		$this->assertRedirect('/project/perl-1/collaborators');
	}
}
