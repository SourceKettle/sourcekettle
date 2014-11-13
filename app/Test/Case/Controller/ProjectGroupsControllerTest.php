<?php
App::uses('ProjectGroupsController', 'Controller');
require_once(__DIR__ . DS . 'AppControllerTest.php');

/**
 * ProjectGroupsController Test Case
 *
 */
class ProjectGroupsControllerTest extends AppControllerTest {

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
		parent::setUp("ProjectGroups");
	}

	public function testViewNotLoggedIn() {
		$this->testAction('/project_groups/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testViewInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project_groups/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testViewNormalUser() {
		$this->_fakeLogin(3);
		$this->testAction('/project_groups/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		$this->assertEquals('php_projects', $this->vars['projectGroup']['ProjectGroup']['name']);

		$projects = array_map(function($a){return $a['name'];}, $this->vars['projectGroup']['Project']);
		$teams = array_map(function($a){return $a['team_name'];}, $this->vars['projectGroup']['GroupCollaboratingTeam']);

		$this->assertEquals(array(
			'php-1',
			'php-2',
		), $projects);

		$this->assertEquals(array(
			'php_developers',
			'python_developers',
		), $teams);
	}

	public function testViewSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/project_groups/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		$this->assertEquals('php_projects', $this->vars['projectGroup']['ProjectGroup']['name']);

		$projects = array_map(function($a){return $a['name'];}, $this->vars['projectGroup']['Project']);
		$teams = array_map(function($a){return $a['team_name'];}, $this->vars['projectGroup']['GroupCollaboratingTeam']);
		sort($projects); sort($teams);

		$this->assertEquals(array(
			'php-1',
			'php-2',
		), $projects);

		$this->assertEquals(array(
			'php_developers',
			'python_developers',
		), $teams);
		
	}

	public function testViewNonExistant() {
		$this->_fakeLogin(5);
		try{
			$this->testAction('/project_groups/not_a_real_project_group', array('return' => 'view', 'method' => 'post'));
		} catch (NotFoundException $e) {
			$this->assertTrue(true, "Correct exception thrown");
			return;
		}
		$this->assertFalse(true, "Should have thrown an exception");
	}

	public function testViewByName() {
		$this->_fakeLogin(3);
		$this->testAction('/project_groups/view/php_projects', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		$this->assertEquals('php_projects', $this->vars['projectGroup']['ProjectGroup']['name']);
	}

	public function testAdminViewNotLoggedIn() {
		$this->testAction('/admin/project_groups/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
		
	}

	public function testAdminViewNormalUser() {
		$this->_fakeLogin(3);
		$this->testAction('/admin/project_groups/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
		
	}

	public function testAdminViewSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/project_groups/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRedirect('/project_groups/1');
		
	}

	public function testAdminIndexNotLoggedIn() {
		$this->testAction('/admin/project_groups', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminIndexInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/admin/project_groups', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminIndexNormalUser() {
		$this->_fakeLogin(3);
		$this->testAction('/admin/project_groups', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminIndexSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/project_groups', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$project_groups = array_map(function($a) {return $a['ProjectGroup']['id'];}, $this->vars['projectGroups']);
		sort($project_groups);
		$this->assertEquals(array(1, 2, 3), $project_groups);
	}

	public function testAdminAddNotLoggedIn() {
		$this->testAction('/admin/project_groups/add', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminAddInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/admin/project_groups/add', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminAddNormalUser() {
		$this->_fakeLogin(3);
		$this->testAction('/admin/project_groups/add', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminAddForm() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/project_groups/add', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/admin/project_groups/add').'"|', $this->view);
	}

	public function testAdminAddSystemAdmin() {
		$this->_fakeLogin(5);
		$postData = array(
			'ProjectGroup' => array(
				'name' => 'new_project_group_for_cool_projects',
			),
			'Project' => array('Project' => array(
				1, 2
			)),
		);
		$this->testAction('/admin/project_groups/add', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		$projectGroup = $this->controller->ProjectGroup->findById($this->controller->ProjectGroup->getLastInsertID());

		$this->assertEquals('new_project_group_for_cool_projects', $projectGroup['ProjectGroup']['name']);

		// TODO use names instead when the model pulls them in
		$projects = array_map(function($a){return $a['id'];}, $projectGroup['Project']);
		$teams = array_map(function($a){return $a['team_id'];}, $projectGroup['GroupCollaboratingTeam']);
		sort($projects); sort($teams);

		$this->assertEquals(array(
			1,
			2,
		), $projects);

		$this->assertEquals(array(), $teams);

	}

	public function testAdminAddFail() {
		$this->_fakeLogin(5);
		$postData = array(
			'ProjectGroup' => array(
				'name' => 'new_project_group_for_cool_projects',
			),
			'Project' => array('Project' => array(
				1, 2
			)),
		);

		$this->controller->ProjectGroup = $this->getMockForModel('ProjectGroup', array('save'));
		$this->controller->ProjectGroup
			->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$this->testAction('/admin/project_groups/add', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

	}

	public function testAdminEditNotLoggedIn() {
		$this->testAction('/admin/project_groups/edit/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminEditInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/admin/project_groups/edit/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminEditNormalUser() {
		$this->_fakeLogin(3);
		$this->testAction('/admin/project_groups/edit/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminEditForm() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/project_groups/edit/1', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/admin/project_groups/edit/1').'"|', $this->view);
	}

	public function testEditNonExistant() {
		$this->_fakeLogin(5);
		try{
			$this->testAction('/admin/project_groups/edit/999', array('return' => 'view', 'method' => 'post'));
		} catch (NotFoundException $e) {
			$this->assertTrue(true, "Correct exception thrown");
			return;
		}
		$this->assertFalse(true, "Should have thrown an exception");
	}

	public function testAdminEditSystemAdmin() {
		$this->_fakeLogin(5);
		$postData = array(
			'ProjectGroup' => array(
				'id' => 1,
				'name' => 'new_project_group_for_cool_projects',
			),
			'Project' => array('Project' => array(
				1, 2
			)),
		);
		$this->testAction('/admin/project_groups/edit/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		$projectGroup = $this->controller->ProjectGroup->findById(1);
		
		$this->assertEquals('new_project_group_for_cool_projects', $projectGroup['ProjectGroup']['name']);

		// TODO use names instead when the model pulls them in
		$projects = array_map(function($a){return $a['id'];}, $projectGroup['Project']);
		$teams = array_map(function($a){return $a['team_id'];}, $projectGroup['GroupCollaboratingTeam']);
		sort($projects); sort($teams);

		$this->assertEquals(array(
			1,
			2,
		), $projects);

		$this->assertEquals(array(
			1,
			3,
		), $teams);
	}

	public function testAdminEditFail() {
		$this->_fakeLogin(5);
		$postData = array(
			'ProjectGroup' => array(
				'id' => 1,
				'name' => 'new_project_group_for_cool_projects',
			),
			'Project' => array('Project' => array(
				1, 2
			)),
		);

		$this->controller->ProjectGroup = $this->getMockForModel('ProjectGroup', array('save'));
		$this->controller->ProjectGroup
			->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$this->testAction('/admin/project_groups/edit/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

	}

	public function testAdminDeleteNotLoggedIn() {
		$this->testAction('/admin/project_groups/delete/1', array('return' => 'view', 'method' => 'post'));
		$this->assertNotAuthorized();
	}

	public function testAdminDeleteInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/admin/project_groups/delete/1', array('return' => 'view', 'method' => 'post'));
		$this->assertNotAuthorized();
	}

	public function testAdminDeleteNormalUser() {
		$this->_fakeLogin(3);
		$this->testAction('/admin/project_groups/delete/1', array('return' => 'view', 'method' => 'post'));
		$this->assertNotAuthorized();
	}

	public function testDeleteNonExistant() {
		$this->_fakeLogin(5);
		try{
			$this->testAction('/admin/project_groups/delete/999', array('return' => 'view', 'method' => 'post'));
		} catch (NotFoundException $e) {
			$this->assertTrue(true, "Correct exception thrown");
			return;
		}
		$this->assertFalse(true, "Should have thrown an exception");
	}

	public function testAdminDeleteSystemAdmin() {
		$this->_fakeLogin(5);
		$postData = array();
		$this->testAction('/admin/project_groups/delete/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		$project_group = $this->controller->ProjectGroup->findById(1);

		$this->assertEmpty($project_group);
	}

	public function testAdminDeleteFail() {
		$this->_fakeLogin(5);

		$this->controller->ProjectGroup = $this->getMockForModel('ProjectGroup', array('delete'));
		$this->controller->ProjectGroup
			->expects($this->any())
			->method('delete')
			->will($this->returnValue(false));

		$this->testAction('/admin/project_groups/delete/1', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

	}

	public function testApiAutocompleteNotLoggedIn() {
		$this->testAction('/api/project_groups/autocomplete');
		$this->assertNotAuthorized();
	}

	public function testApiAutocompleteNormalUser() {
		$this->_fakeLogin(3);
		$this->testAction('/api/project_groups/autocomplete');
		$this->assertAuthorized();
	}

	public function testApiAutocompleteSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/api/project_groups/autocomplete');
		$this->assertAuthorized();
	}

	public function testApiAutocompleteTwoCharsAndBelow() {
		$this->_fakeLogin(3);
		$this->testAction('/api/project_groups/autocomplete?query=p');
		$this->assertAuthorized();
		$this->assertEquals(array('php_projects [Some projects written in PHP]', 'python_projects [Some projects written in Python]'), $this->vars['data']['projectGroups']);

		$this->testAction('/api/project_groups/autocomplete?query=py');
		$this->assertAuthorized();
		$this->assertEquals(array('python_projects [Some projects written in Python]'), $this->vars['data']['projectGroups']);

		$this->testAction('/api/project_groups/autocomplete?query=pr');
		$this->assertAuthorized();
		$this->assertEquals(array(), $this->vars['data']['projectGroups']);
	}

	public function testApiAutocompleteThreeCharsAndAbove() {
		$this->_fakeLogin(3);
		$this->testAction('/api/project_groups/autocomplete?query=ect');
		$this->assertAuthorized();
		$this->assertEquals(array('php_projects [Some projects written in PHP]', 'java_projects [Some projects written in Java]', 'python_projects [Some projects written in Python]'), $this->vars['data']['projectGroups']);

		$this->testAction('/api/project_groups/autocomplete?query=projects');
		$this->assertAuthorized();
		$this->assertEquals(array('php_projects [Some projects written in PHP]', 'java_projects [Some projects written in Java]', 'python_projects [Some projects written in Python]'), $this->vars['data']['projectGroups']);


	}

}
