<?php
App::uses('ProjectsController', 'Controller');
require_once(__DIR__ . DS . 'AppControllerTest.php');


/**
 * ProjectsController Test Case
 *
 */
class ProjectsControllerTestCase extends AppControllerTest {

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
		parent::setUp("Projects");
	}


	public function testIndexNotLoggedIn() {

		// Cannot see the page when not logged in
		$this->testAction('/projects', array('method' => 'get', 'return' => 'vars'));
		$this->assertEquals($this->vars['projects'], array());
	}

	public function testIndexSystemAdmin() {

		// Log in as a system administrator - we should still only see "my" projects, not everyone's
		$this->_fakeLogin(5);
		// Perform the action, and check the user was authorized
		$ret = $this->testAction('/projects', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

		// Check the page content looks roughly OK
		$this->assertContains('<h1>My Projects', $this->view);
		$this->assertRegexp('/<a href=".*\/project\/public\/." class="project-link">public<\/a>/', $this->view);
		$this->assertRegexp('/<a href=".*\/project\/private\/." class="project-link">private<\/a>/', $this->view);

		// Check the project list looks sane and has only the right entries/access levels
		$this->assertNotNull($this->vars['projects']);
		$this->assertEqual(count($this->vars['projects']), 2, "Incorrect number of projects returned");

		// Check each project 
		foreach ($this->vars['projects'] as $project) {

			// We should be a collaborator
			if (!isset($project['User']) || $project['User']['id'] != 5) {
				$this->assertTrue(false, "A project for another collaborator was found");
			}

			// We should only get these two, and with the correct access levels
			if ($project['Project']['id'] == 1 && $project['Collaborator']['access_level'] == 2) {
				$this->assertTrue(true, "Impossible to fail");
			} elseif ($project['Project']['id'] == 2 && $project['Collaborator']['access_level'] == 1) {
				$this->assertTrue(true, "Impossible to fail");
			} else {
				$this->assertTrue(false, "An unexpected project ID (".$project['Project']['id'].") or access level (".$project['Collaborator']['access_level'].") was retrieved");
			}
		}
	}

	public function testIndexProjectGuest() {

		// Log in as a guest on one project
		$this->_fakeLogin(3);

		$this->testAction('/projects', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		
		$this->assertContains('<h1>My Projects', $this->view);
		$this->assertRegexp('/<a href=".*\/project\/private\/." class="project-link">private<\/a>/', $this->view);

		// Check the project list looks sane and has only the right entries/access levels
		$this->assertNotNull($this->vars['projects']);
		$this->assertEqual(count($this->vars['projects']), 2, "Incorrect number of projects returned");

		// Crunch to get the project ID, user ID and access level only
		$ids = array_map(function($a) {return array('p' => $a['Project']['id'], 'u' => $a['User']['id'], 'l' => $a['Collaborator']['access_level']);}, $this->vars['projects']);

		// Should have two projects, guest on both
		$this->assertEquals(array(
			array('p' => 1, 'u' => 3, 'l' => 0),
			array('p' => 12, 'u' => 3, 'l' => 0),
		), $ids);

	}

	public function testPublicIndexSystemAdmin() {

		// Log in as a system administrator - we should still only see public projects, not everything
		$this->_fakeLogin(5);

		$this->testAction('/projects/public_projects', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertContains('<h1>Public Projects', $this->view);

		$this->assertRegexp('/<a href=".*\/project\/public\/." class="project-link">public<\/a>/', $this->view);
		$this->assertRegexp('/<a href=".*\/project\/personal_public\/." class="project-link">personal_public<\/a>/', $this->view);

		// Check the project list looks sane and has only the right entries/access levels
		$this->assertNotNull($this->vars['projects']);
		$this->assertEqual(count($this->vars['projects']), 3, "Incorrect number of projects returned");

		// Crunch down to just the project IDs
		$ids = array_map(function($a){return $a['Project']['id'];}, $this->vars['projects']);
		$this->assertEquals(array(2, 4, 5), $ids);
	}

	public function testPublicIndexProjectGuest() {

		// Log in as a guest - we should see all public projects, not just our own ones
		$this->_fakeLogin(3);

		$this->testAction('/projects/public_projects', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertContains('<h1>Public Projects', $this->view);

		$this->assertRegexp('/<a href=".*\/project\/public\/." class="project-link">public<\/a>/', $this->view);
		$this->assertRegexp('/<a href=".*\/project\/personal_public\/." class="project-link">personal_public<\/a>/', $this->view);

		// Check the project list looks sane and has only the right entries/access levels
		$this->assertNotNull($this->vars['projects']);
		$this->assertEqual(count($this->vars['projects']), 3, "Incorrect number of projects returned");

		// Crunch down to just the project IDs
		$ids = array_map(function($a){return $a['Project']['id'];}, $this->vars['projects']);
		$this->assertEquals(array(2, 4, 5), $ids);
		
	}

	public function testCollaboratingTeamProjectsNoTeams() {

		// Log in as a non-team member
		$this->_fakeLogin(1);

		$this->testAction('/projects/team_projects', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertContains('<h1>Team Projects', $this->view);

		// Check the project list looks sane and has only the right entries/access levels
		$this->assertEmpty($this->vars['projects']);
	}

	public function testCollaboratingTeamProjects() {

		// Log in as a team member
		$this->_fakeLogin(20);

		$this->testAction('/projects/team_projects', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertContains('<h1>Team Projects', $this->view);

		$this->assertRegexp('/<a href=".*\/project\/perl-1\/." class="project-link">perl-1<\/a>/', $this->view);

		// Check the project list looks sane and has only the right entries/access levels
		$this->assertNotNull($this->vars['projects']);
		$this->assertEqual(count($this->vars['projects']), 1, "Incorrect number of projects returned");

		// Crunch down to just the project IDs
		$ids = array_map(function($a){return $a['Project']['id'];}, $this->vars['projects']);
		$this->assertEquals(array(12), $ids);
	}

	public function testCollaboratingTeamGroupProjects() {

		// Log in as a team member who has access via a project group
		// Member of team 2 (java devs), access to groups 2 and 3 (java and python projects)
		$this->_fakeLogin(14);

		$this->testAction('/projects/team_projects', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertContains('<h1>Team Projects', $this->view);

		$this->assertRegexp('/<a href=".*\/project\/python-1\/." class="project-link">python-1<\/a>/', $this->view);
		$this->assertRegexp('/<a href=".*\/project\/python-2\/." class="project-link">python-2<\/a>/', $this->view);
		$this->assertRegexp('/<a href=".*\/project\/java-1\/." class="project-link">java-1<\/a>/', $this->view);
		$this->assertRegexp('/<a href=".*\/project\/java-2\/." class="project-link">java-2<\/a>/', $this->view);

		// Check the project list looks sane and has only the right entries/access levels
		$this->assertNotNull($this->vars['projects']);
		$this->assertEqual(count($this->vars['projects']), 4, "Incorrect number of projects returned");

		// Crunch down to just the project IDs
		$ids = array_map(function($a){return $a['Project']['id'];}, $this->vars['projects']);
		$this->assertEquals(array(8, 9, 10, 11), $ids);
	}

/**
 * testView method
 *
 * @return void
 */
 	public function testViewLinks() {
		$this->_fakeLogin(5);
		$this->testAction('/project/private', array('return' => 'view', 'method' => 'get'));

		// Check all links are correct
		$this->assertRegexp('|<a href=".*'.Router::url('/project/private/tasks/index').'\?statuses=in\+progress">|', $this->view);
		$this->assertRegexp('|<a href=".*'.Router::url('/project/private/tasks/index').'\?statuses=open">|', $this->view);
		$this->assertRegexp('|<a href=".*'.Router::url('/project/private/tasks/index').'\?statuses=closed%2Cresolved">|', $this->view);
		$this->assertRegexp('|<a href=".*'.Router::url('/project/private/tasks/index').'\?statuses=dropped">|', $this->view);
		$this->assertRegexp('|<a href=".*'.Router::url('/project/private/tasks/index').'">|', $this->view);
		$this->assertRegexp('|<a href=".*'.Router::url('/project/private/tasks/add').'">|', $this->view);
		$this->assertRegexp('|<a href=".*'.Router::url('/project/private/milestones/view/').'\d+">|', $this->view);
		$this->assertRegexp('|<a href=".*'.Router::url('/project/private/milestones/add').'">|', $this->view);
		$this->assertRegexp('|<a href=".*'.Router::url('/project/private/collaborators').'">|', $this->view);
		$this->assertRegexp('|<a href=".*'.Router::url('/project/private/time/add').'">|', $this->view);
		
	}

	public function testViewSystemAdminOwner() {

		// System admin can see everything
		$this->_fakeLogin(5);

		$this->testAction('/project/private', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		$this->assertContains('<h1>private <small>Project overview</small></h1>', $this->view);
		$this->assertNotNull($this->vars['project']);
		
	}

	public function testViewSystemAdminNotOwner() {

		// System admin can see everything
		$this->_fakeLogin(5);

		$this->testAction('/project/personal', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		$this->assertContains('<h1>personal <small>Project overview</small></h1>', $this->view);

		$this->assertNotNull($this->vars['project']);
		
	}

	public function testViewUser() {

		$this->_fakeLogin(1);

		$this->testAction('/project/public', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		$this->assertContains('<h1>public <small>Project overview</small></h1>', $this->view);

		$this->assertNotNull($this->vars['project']);
		
	}

	public function testViewNotUser() {

		$this->_fakeLogin(1);

		$this->testAction('/project/personal', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
		
	}

	public function testViewGuest() {

		$this->_fakeLogin(3);

		$this->testAction('/project/private', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		$this->assertContains('<h1>private <small>Project overview</small></h1>', $this->view);

		$this->assertNotNull($this->vars['project']);
		
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAddProjectFormNotLoggedIn() {
		$this->testAction('/projects/add', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAddProjectForm() {
		$this->_fakeLogin(3);
		$this->testAction('/projects/add', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/projects/add').'"|', $this->view);
		
	}

	public function testAddProjectWithNameClash() {
		$this->_fakeLogin(3);
		$postData = array(
			'Project' => array(
				'name' => 'private',
				'description' => 'A clashing project name',
				'repo_type' => 2,
				'public' => 1,
			)
		);

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("Project '<strong>private</strong>' could not be created. Please try again.");

		$this->testAction('/projects/add', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/projects/add').'"|', $this->view);
	}
	
	public function testAddSourceFail() {
		$this->_fakeLogin(3);
		$postData = array(
			'Project' => array(
				'name' => 'new_PROJECT',
				'description' => 'A new project',
				'repo_type' => 2,
				'public' => 1,
			)
		);

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("Project '<strong>new_PROJECT</strong>' could not be created. Please try again.");

		$this->controller->Project = $this->getMockForModel('Project', array('delete'));
		$this->controller->Project->Source = $this->getMockForModel('Source', array('create'));
		$this->controller->Project->Source
			->expects($this->once())
			->method('create')
			->will($this->returnValue(false));

		$this->controller->Project
			->expects($this->once())
			->method('delete')
			->will($this->returnValue(true));

		$this->testAction('/projects/add', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
	}

	public function testAddProject() {
		$this->_fakeLogin(3);
		$postData = array(
			'Project' => array(
				'name' => 'newproject',
				'description' => 'A non-clashing project name',
				'repo_type' => 1,
				'public' => 1,
			)
		);

		$this->testAction('/projects/add', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the project page
		$this->assertRedirect('/project/newproject/view');
	}

	public function testAddGitProject() {
		$this->_fakeLogin(3);
		$postData = array(
			'Project' => array(
				'name' => 'newproject_withgit',
				'description' => 'A non-clashing git project name',
				'repo_type' => 2,
				'public' => 1,
			)
		);

		$this->testAction('/projects/add', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the project page
		$this->assertRedirect('/project/newproject_withgit/view');
	}

	public function testAddDefaultRepoType() {
		$this->controller->Setting = $this->getMockForModel('Setting', array('loadConfigSettings'));
		$this->controller->Setting
			->expects($this->any())
			->method('loadConfigSettings')
			->will($this->returnValue(array(
				'UserInterface' => array(
					'alias' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'SourceKettle'),
					'theme' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'default'),
				),
				'SourceRepository' => array(
					'default' => array('source' => 'defaults', 'locked' => 0, 'value' => 'Git'),
				),
				'Ldap' => array(
					'enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => false),
				),
			)));
		$this->_fakeLogin(3);
		$this->testAction('/projects/add', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/projects/add').'"|', $this->view);
		$this->assertRegexp('|<option value="2" selected="selected">Git</option>|', $this->view);
	}

	public function testAddBadDefaultRepoType() {
		$this->controller->Setting = $this->getMockForModel('Setting', array('loadConfigSettings'));
		$this->controller->Setting
			->expects($this->any())
			->method('loadConfigSettings')
			->will($this->returnValue(array(
				'UserInterface' => array(
					'alias' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'SourceKettle'),
					'theme' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'default'),
				),
				'SourceRepository' => array(
					'default' => array('source' => 'defaults', 'locked' => 0, 'value' => 'Shoes'),
				),
				'Ldap' => array(
					'enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => false),
				),
			)));
		$this->_fakeLogin(3);
		$this->testAction('/projects/add', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/projects/add').'"|', $this->view);
		$this->assertRegexp('|<option value="1" selected="selected">None</option>|', $this->view);
	}

	public function testAddNoDefaultRepoType() {
		$this->controller->Setting = $this->getMockForModel('Setting', array('loadConfigSettings'));
		$this->controller->Setting
			->expects($this->any())
			->method('loadConfigSettings')
			->will($this->returnValue(array(
				'UserInterface' => array(
					'alias' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'SourceKettle'),
					'theme' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'default'),
				),
				'SourceRepository' => array(
				),
				'Ldap' => array(
					'enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => false),
				),
			)));
		$this->_fakeLogin(3);
		$this->testAction('/projects/add', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/projects/add').'"|', $this->view);
		$this->assertRegexp('|<option value="1" selected="selected">None</option>|', $this->view);
	}

/**
 * testEdit method
 *
 * @return void
 */
	public function testEditNonExistant() {
		$this->_fakeLogin(5);
		try{
			$this->testAction('/project/newproject/edit', array('return' => 'view', 'method' => 'post'));
		} catch (NotFoundException $e) {
			$this->assertTrue(true, "Correct exception thrown");
			return;
		}
		$this->assertFalse(true, "Should have thrown an exception");

	}

	public function testEditProjectForm() {
		$this->_fakeLogin(5);
		$this->testAction('/project/personal/edit', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/project/personal/edit').'"|', $this->view);
		
	}

	public function testEditSystemAdminNotOwner() {
		$this->_fakeLogin(5);

		// We will attempt to change the project name. This should fail.
		$postData = array(
			'Project' => array(
				'id' => '3',
				'name' => 'newproject',
				'description' => 'Updated description of a project',
				'repo_type' => '2',
				'public' => false,
				'deleted' => 0,
				'deleted_date' => null,
			)
		);

		$this->testAction('/project/personal/edit', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the project page
		$this->assertRedirect('/project/personal');

		// Check it saved correctly
		$saved = $this->controller->Project->findById(3);
		$postData['Project']['name'] = 'personal';
		unset($saved['Project']['created']);
		unset($saved['Project']['modified']);
		$this->assertEquals($saved['Project'], $postData['Project'], 'Failed to change project data');

	}

	public function testEditProjectAdminOwner() {
		$this->_fakeLogin(7);
		$postData = array(
			'Project' => array(
				'id' => '3',
				'name' => 'newproject',
				'description' => 'Updated description of a project',
				'repo_type' => '2',
				'public' => false,
				'deleted' => 0,
				'deleted_date' => null,
			)
		);

		$this->testAction('/project/personal/edit', array('return' => 'view', 'method' => 'post', 'data' => $postData));

		// We should be redirected to the project page
		$this->assertRedirect('/project/personal');

		// Check it saved correctly
		$saved = $this->controller->Project->findById(3);
		$postData['Project']['name'] = 'personal';
		unset($saved['Project']['created']);
		unset($saved['Project']['modified']);
		$this->assertEquals($saved['Project'], $postData['Project'], 'Failed to change project data');

	}

	public function testEditProjectUser() {
		$this->_fakeLogin(4);
		$postData = array(
			'Project' => array(
				'description' => 'Updated description of a project',
			)
		);
		
		$this->testAction('/project/perl-1/edit', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertNotAuthorized();

	}

	public function testEditProjectGuest() {
		$this->_fakeLogin(3);
		$postData = array(
			'Project' => array(
				'id' => '12',
				'description' => 'Updated description of a project',
			)
		);
		
		$this->testAction('/project/perl-1/edit', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertNotAuthorized();

	}

	public function testEditFail() {
		$this->_fakeLogin(7);
		$postData = array(
			'Project' => array(
				'id' => '3',
				'name' => 'personal',
				'description' => 'Updated description of a project',
				'repo_type' => '2',
				'public' => false,
			)
		);

		$this->controller->Project = $this->getMockForModel('Project', array('save'));
		$this->controller->Project
			->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("Project '<strong>personal</strong>' could not be updated. Please try again.");

		$this->testAction('/project/personal/edit', array('return' => 'view', 'method' => 'post', 'data' => $postData));
	}
/**
 * testDelete method
 *
 * @return void
 */
	public function testDeleteForm() {
		$this->_fakeLogin(5);
		$saved = $this->controller->Project->findById(3);
		$this->testAction('/project/personal/delete', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertContains('<h1>Are you sure you want to delete?</h1>', $this->view);
	}

	public function testDeleteSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/project/personal/delete', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();
		$saved = $this->controller->Project->findById(3);
		$this->assertEquals($saved, array(), "Failed to delete");
	}

	public function testDeleteProjectAdmin() {
		$this->_fakeLogin(7);
		$this->testAction('/project/personal/delete', array('return' => 'view', 'method' => 'post'));
		$saved = $this->controller->Project->findById(3);
		$this->assertEquals($saved, array(), "Failed to delete");
	}

	public function testDeleteFail() {
		$this->_fakeLogin(7);
		$this->controller->Project = $this->getMockForModel('Project', array('delete'));
		$this->controller->Project
			->expects($this->once())
			->method('delete')
			->will($this->returnValue(false));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("Project '<strong>personal</strong>' could not be deleted. Please try again.");

		$this->testAction('/project/personal/delete', array('return' => 'view', 'method' => 'post'));
	}

	// TODO awaiting better authorization checks
	/*public function testDeleteNotAdmin() {
		$this->_fakeLogin(1);
		$this->testAction('/project/personal/delete', array('return' => 'view', 'method' => 'post'));
		$this->assertNotAuthorized();
	}*/

	public function testAdminDeleteForm() {
		$this->_fakeLogin(5);
		$saved = $this->controller->Project->findById(3);
		$this->testAction('/admin/projects/personal/delete', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertContains('<h1>Are you sure you want to delete?</h1>', $this->view);
	}

	public function testAdminDeleteSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/projects/personal/delete', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();
		$saved = $this->controller->Project->findById(3);
		$this->assertEquals($saved, array(), "Failed to delete");
	}

	/* TODO awaiting better authorisation checks
	public function testAdminDeleteNotSystemAdmin() {
		$this->_fakeLogin(3);
		$this->testAction('/admin/projects/private/delete', array('return' => 'view', 'method' => 'post'));
		$this->assertNotAuthorized();
	}*/

	public function testAdminDeleteFail() {
		$this->_fakeLogin(5);
		$this->controller->Project = $this->getMockForModel('Project', array('delete'));
		$this->controller->Project
			->expects($this->once())
			->method('delete')
			->will($this->returnValue(false));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("Project '<strong>personal</strong>' could not be deleted. Please try again.");

		$this->testAction('/admin/projects/personal/delete', array('return' => 'view', 'method' => 'post'));
	}

	public function testAdminIndexNotLoggedIn() {
		$this->testAction('/admin/projects', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminIndexNonAdmin() {
		$this->_fakeLogin(1);
		$this->testAction('/admin/projects', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminIndexSystemAdmin() {

		// Log in as a system administrator - we should still only see "my" projects, not everyone's
		$this->_fakeLogin(5);

		$this->testAction('/admin/projects', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		// Check the page content looks roughly OK
		$this->assertContains('<h1>Administration <small>da vinci code locator</small>', $this->view);
		$this->assertRegexp('/<a href=".*\/project\/private\/view">private<\/a>/', $this->view);
		$this->assertRegexp('/<a href=".*\/project\/public\/view">public<\/a>/', $this->view);
		$this->assertRegexp('/<a href=".*\/project\/personal\/view">personal<\/a>/', $this->view);
		$this->assertRegexp('/<a href=".*\/project\/personal_public\/view">personal_public<\/a>/', $this->view);

		// Check the project list looks sane and has only the right entries/access levels
		$this->assertNotNull($this->vars['projects']);
		$this->assertEqual(12, count($this->vars['projects']), "Incorrect number of projects returned");

		$projects = array_map(function($a){return array('id' => $a['Project']['id'], 'repo_type' => $a['RepoType']['name']);}, $this->vars['projects']);

		$this->assertEquals(array(
			array('id' => 1, 'repo_type' => 'Git'),
			array('id' => 2, 'repo_type' => 'None'),
			array('id' => 3, 'repo_type' => 'None'),
			array('id' => 4, 'repo_type' => 'None'),
			array('id' => 5, 'repo_type' => 'None'),
			array('id' => 6, 'repo_type' => 'None'),
			array('id' => 7, 'repo_type' => 'None'),
			array('id' => 8, 'repo_type' => 'None'),
			array('id' => 9, 'repo_type' => 'None'),
			array('id' => 10, 'repo_type' => 'None'),
			array('id' => 11, 'repo_type' => 'None'),
			array('id' => 12, 'repo_type' => 'None'),
		), $projects);

	}

	public function testAdminIndexPostInvalidProject() {

		// Log in as a system administrator - we should still only see "my" projects, not everyone's
		$this->_fakeLogin(5);
		$postData = array(
			'Project' => array(
				'name' => 'not_a_real_project_lolz',
			),
		);
		$this->testAction('/admin/projects', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
	}
	public function testAdminIndexPostRedirect() {

		// Log in as a system administrator - we should still only see "my" projects, not everyone's
		$this->_fakeLogin(5);
		$postData = array(
			'Project' => array(
				'name' => 'personal',
			),
		);
		$this->testAction('/admin/projects', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
		$this->assertRedirect('/project/personal/view');
	}

	public function testHistory() {
		$this->_fakeLogin(7);
		$this->testAction('/project/personal/history', array('return' => 'view', 'method' => 'get'));
		$this->assertRegexp('/<a href=".*\/project\/personal\/view">/', $this->view);
		$this->assertContains('<div id="histId', $this->view);
	}
	
	public function testRedirectIdToName() {
		
		$this->_fakeLogin(7);
		$this->testAction('/project/2', array('return' => 'view', 'method' => 'get'));
		$this->assertRedirect('/project/public/view');
	}

/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminRenameNotSystemAdmin() {
		$this->_fakeLogin(2);
		$this->testAction('/admin/projects/public/rename', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminRenameForm() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/projects/public/rename', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/admin/projects/public/rename').'"|', $this->view);
		$this->assertRegexp('/<input name="data\[Project\]\[name\]".*value="public"/', $this->view);
	}

	public function testAdminRenameFail() {
		$this->_fakeLogin(5);
		$postData = array(
			'Project' => array(
				'name' => 'NotAtAllPublic',
			),
		);

		$this->controller->Project = $this->getMockForModel('Project', array('save'));
		$this->controller->Project
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with("Project '<strong>public</strong>' could not be updated. Please try again.");

		$this->testAction('/admin/projects/public/rename', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
		$retrieved = $this->controller->Project->findById(2);
		$this->assertEquals('public', $retrieved['Project']['name']);
	}
	public function testAdminRenameOK() {
		$this->_fakeLogin(5);
		$postData = array(
			'Project' => array(
				'name' => 'NotAtAllPublic',
			),
		);
		$this->testAction('/admin/projects/public/rename', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
		$retrieved = $this->controller->Project->findById(2);
		$this->assertEquals('NotAtAllPublic', $retrieved['Project']['name']);
	}


	public function testAddRepoNotProjectAdmin() {
		$this->_fakeLogin(6);
		$this->testAction('/project/public/add_repo', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAddRepoForm() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/add_repo', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/project/public/add_repo').'"|', $this->view);
	}

	public function testAddRepoAlreadyGotOne() {
		$this->_fakeLogin(5);
		$postData = array(
			'Project' => array(
				'repo_type' => 2,
			),
		);
		try{
			$this->testAction('/project/private/add_repo', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		} catch (NotFoundException $e) {
			$this->assertTrue(true);
		}

		$this->assertAuthorized();
		$retrieved = $this->controller->Project->findById(1);
		$this->assertEquals(2, $retrieved['Project']['repo_type']);
	}

	public function testAddRepoFail() {
		$this->_fakeLogin(2);
		$postData = array(
			'Project' => array(
				'repo_type' => 2,
			),
		);

		$this->controller->Project = $this->getMockForModel('Project', array('save'));
		$this->controller->Project
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("Project '<strong>%s</strong>' could not be updated. Please try again.", "repoless"));

		$this->testAction('/project/repoless/add_repo', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
		$retrieved = $this->controller->Project->findById(5);
		$this->assertEquals(1, $retrieved['Project']['repo_type']);
	}

	public function testAddRepoOK() {
		$this->_fakeLogin(2);
		$postData = array(
			'Project' => array(
				'repo_type' => 2,
			),
		);
		$this->testAction('/project/repoless/add_repo', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
		$retrieved = $this->controller->Project->findById(5);
		$this->assertEquals(2, $retrieved['Project']['repo_type']);
	}

	// Crunch a set of history entries down into just a few fields for easy comparison
	private function __crunchHistory($events){
		return array_map(function($a){
			return array(
				'project' => $a['Project']['id'],
				'actioner' => $a['Actioner']['id'],
				'subject' => $a['Subject']['title'],
				'field' => $a['Change']['field'],
				'old' => $a['Change']['field_old'],
				'new' => $a['Change']['field_new'],
			);
		}, $events);
	}
	
	public function testApiHistoryNotLoggedIn() {
		$this->testAction('/api/project/perl-1/history/8');
		$this->assertNotAuthorized();
	}

	public function testApiHistoryNotCollaborator() {
		$this->_fakeLogin(1);
		$this->testAction('/api/project/perl-1/history/8');
		$this->assertNotAuthorized();
	}

	public function testApiHistoryNoProject() {
		$this->_fakeLogin(5);
		$this->testAction('/api/projects/history');
		$this->assertAuthorized();
		$this->assertEquals(array('error' => 400, 'message' => __('Bad request, no project specified.')), json_decode($this->view, true));
	}

	public function testApiHistoryNoLimit() {
		$this->_fakeLogin(3);
		$this->testAction('/api/project/perl-1/history');
		$this->assertAuthorized();
		$events = $this->__crunchHistory($this->vars['events']);
		$this->assertEqual(array(
			array('project' => 12, 'actioner' => 2, 'subject' => 'stop overengineering', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'third checkin ermagerd', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'second checkin', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'first ever checkin', 'field' => '+', 'old' => null, 'new' => null),
		), $events);
	}

	public function testApiHistoryLimit2() {
		$this->_fakeLogin(3);
		$this->testAction('/api/project/perl-1/history/2');
		$this->assertAuthorized();
		$events = $this->__crunchHistory($this->vars['events']);
		$this->assertEqual(array(
			array('project' => 12, 'actioner' => 2, 'subject' => 'stop overengineering', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'third checkin ermagerd', 'field' => '+', 'old' => null, 'new' => null),
		), $events);
	}

	public function testApiHistoryProjectGuest() {
		$this->_fakeLogin(3);
		$this->testAction('/api/project/perl-1/history/8');
		$this->assertAuthorized();
		$events = $this->__crunchHistory($this->vars['events']);
		$this->assertEqual(array(
			array('project' => 12, 'actioner' => 2, 'subject' => 'stop overengineering', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'third checkin ermagerd', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'second checkin', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'first ever checkin', 'field' => '+', 'old' => null, 'new' => null),
		), $events);
	}

	public function testApiHistoryProjectUser() {
		$this->_fakeLogin(4);
		$this->testAction('/api/project/perl-1/history/8');
		$this->assertAuthorized();
		$events = $this->__crunchHistory($this->vars['events']);
		$this->assertEqual(array(
			array('project' => 12, 'actioner' => 2, 'subject' => 'stop overengineering', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'third checkin ermagerd', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'second checkin', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'first ever checkin', 'field' => '+', 'old' => null, 'new' => null),
		), $events);
	}

	public function testApiHistoryProjectAdmin() {
		$this->_fakeLogin(2);
		$this->testAction('/api/project/perl-1/history/8');
		$this->assertAuthorized();
		$events = $this->__crunchHistory($this->vars['events']);
		$this->assertEqual(array(
			array('project' => 12, 'actioner' => 2, 'subject' => 'stop overengineering', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'third checkin ermagerd', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'second checkin', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'first ever checkin', 'field' => '+', 'old' => null, 'new' => null),
		), $events);
	}

	public function testApiHistorySystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/api/project/perl-1/history/8');
		$this->assertAuthorized();
		$events = $this->__crunchHistory($this->vars['events']);
		$this->assertEqual(array(
			array('project' => 12, 'actioner' => 2, 'subject' => 'stop overengineering', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'third checkin ermagerd', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'second checkin', 'field' => '+', 'old' => null, 'new' => null),
			array('project' => 12, 'actioner' => 1, 'subject' => 'first ever checkin', 'field' => '+', 'old' => null, 'new' => null),
		), $events);
	}

	public function testSchedule() {
		
		$this->_fakeLogin(3);
		$this->testAction('/project/public/schedule', array('method' => 'get', 'return' => 'contents'));
		$this->assertAuthorized();
		$this->assertEquals(array(2, 1, 3), array_map(function($a){return $a['Milestone']['id'];}, $this->vars['milestones']));
	}

	public function testApiAutocompleteNotLoggedIn() {
		$this->testAction('/api/projects/autocomplete');
		$this->assertNotAuthorized();
	}

	public function testApiAutocompleteSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/api/projects/autocomplete');
		$this->assertAuthorized();
	}

	public function testApiAutocompleteNotSystemAdmin() {
		$this->_fakeLogin(3);
		$this->testAction('/api/projects/autocomplete');
		$this->assertAuthorized();
	}

	public function testApiAutocompleteInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/api/projects/autocomplete');
		$this->assertNotAuthorized();
	}

	public function testApiAutocompleteInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/api/projects/autocomplete');
		$this->assertNotAuthorized();
	}

	public function testApiAutocompleteTwoCharsAndBelow() {
		$this->_fakeLogin(3);
		$this->testAction('/api/projects/autocomplete?query=p');
		$this->assertAuthorized();
		$this->assertEquals(array('private', 'public', 'personal', 'personal_public', 'php-1', 'php-2', 'python-1', 'python-2', 'perl-1'), $this->vars['data']['projects']);

		$this->testAction('/api/projects/autocomplete?query=py');
		$this->assertAuthorized();
		$this->assertEquals(array('python-1', 'python-2'), $this->vars['data']['projects']);

		$this->testAction('/api/projects/autocomplete?query=er');
		$this->assertAuthorized();
		$this->assertEquals(array(), $this->vars['data']['projects']);
	}

	public function testApiAutocompleteThreeCharsAndAbove() {
		$this->_fakeLogin(3);
		$this->testAction('/api/projects/autocomplete?query=ers');
		$this->assertAuthorized();
		$this->assertEquals(array('personal', 'personal_public'), $this->vars['data']['projects']);

		$this->testAction('/api/projects/autocomplete?query=ersonal');
		$this->assertAuthorized();
		$this->assertEquals(array('personal', 'personal_public'), $this->vars['data']['projects']);

	}

	public function testChangeSettingNotLoggedIn() {
		$this->testAction('/project/public/changeSetting');
		$this->assertNotAuthorized();
	}

	public function testChangeSettingSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/project/public/changeSetting');
		$this->assertAuthorized();
	}

	public function testChangeSettingProjectGuest() {
		$this->_fakeLogin(8);
		$this->testAction('/project/public/changeSetting');
		$this->assertNotAuthorized();
	}

	public function testChangeSettingProjectUser() {
		$this->_fakeLogin(4);
		$this->testAction('/project/private/changeSetting');
		$this->assertNotAuthorized();
	}

	public function testChangeSettingNotCollaborator() {
		$this->_fakeLogin(1);
		$this->testAction('/project/personal/changeSetting');
		$this->assertNotAuthorized();
	}

	public function testChangeSettingProjectAdmin() {
		$this->_fakeLogin(9);
		$this->testAction('/project/public/changeSetting');
		$this->assertAuthorized();
	}

	public function testChangeSettingInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/public/changeSetting');
		$this->assertNotAuthorized();
	}

	public function testChangeSettingInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/public/changeSetting');
		$this->assertNotAuthorized();
	}

	public function testChangeSettingGetRedirect() {
		$this->_fakeLogin(5);
		$this->testAction('/project/public/changeSetting', array('method' => 'get'));
		$this->assertRedirect("/project/public/edit");
	}

	public function testChangeSettingPostOK() {
		$this->_fakeLogin(5);
		$postData = array('ProjectSetting' => array(
			'Features' => array('time_enabled' => false, 'task_enabled' => false),
			'UserInterface' => array('alias' => 'BaconIpsum'),
		));
		$this->testAction('/project/public/changeSetting', array('method' => 'post', 'data' => $postData));

		$found = ClassRegistry::init('Setting')->loadConfigSettings(null, 2);
		$this->assertNotEquals('BaconIpsum', $found['UserInterface']['alias']['value']);
		$this->assertEquals(0, $found['Features']['task_enabled']['value']);
		$this->assertEquals(0, $found['Features']['time_enabled']['value']);
	}

	public function testChangeSettingAjaxPostOK() {
		$this->_fakeLogin(5);
		$postData = array('ProjectSetting' => array(
			'Features' => array('time_enabled' => false, 'task_enabled' => false),
			'UserInterface' => array('alias' => 'BaconIpsum'),
		));

		$_ENV['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction('/project/public/changeSetting', array('method' => 'post', 'data' => $postData));
		$this->assertAuthorized();
		unset($_ENV['HTTP_X_REQUESTED_WITH']);

		$json = json_decode($this->view, true);
		$this->assertEquals(array('code' => 200, 'message' => __('Settings updated.')), $json);

		$found = ClassRegistry::init('Setting')->loadConfigSettings(null, 2);
		$this->assertNotEquals('BaconIpsum', $found['UserInterface']['alias']['value']);
		$this->assertEquals(0, $found['Features']['task_enabled']['value']);
		$this->assertEquals(0, $found['Features']['time_enabled']['value']);
	}

}
