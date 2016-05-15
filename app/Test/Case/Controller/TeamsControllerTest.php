<?php
App::uses('TeamsController', 'Controller');
require_once(__DIR__ . DS . 'AppControllerTest.php');

/**
 * TeamsController Test Case
 *
 */
class TeamsControllerTest extends AppControllerTest {

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
		'app.story',
	);

	public function setUp($controllerName = null) {
		parent::setUp("Teams");
	}

	public function testViewNotLoggedIn() {
		$this->testAction('/teams/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testViewInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/teams/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testViewInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/teams/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testViewNormalUser() {
		$this->_fakeLogin(3);
		$this->testAction('/teams/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		$this->assertEquals('php_developers', $this->vars['team']['Team']['name']);

		$users = array_map(function($a){return $a['email'];}, $this->vars['team']['User']);
		$groups = array_map(function($a){return $a['project_group_name'];}, $this->vars['team']['GroupCollaboratingTeam']);
		$projects = array_map(function($a){return $a['project_name'];}, $this->vars['team']['CollaboratingTeam']);
		sort($users); sort($groups); sort($projects);

		$this->assertEquals(array(
			'php-and-java-dev@example.com',
			'php-and-python-dev@example.com',
			'php-dev@example.com',
			'php-python-and-java-dev@example.com',
		), $users);

		$this->assertEquals(array(
			'java_projects',
			'php_projects',
		), $groups);

		$this->assertEquals(array(), $projects);
	}

	public function testViewSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/teams/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		$this->assertEquals('php_developers', $this->vars['team']['Team']['name']);

		$users = array_map(function($a){return $a['email'];}, $this->vars['team']['User']);
		$groups = array_map(function($a){return $a['project_group_name'];}, $this->vars['team']['GroupCollaboratingTeam']);
		$projects = array_map(function($a){return $a['project_name'];}, $this->vars['team']['CollaboratingTeam']);
		sort($users); sort($groups); sort($projects);

		$this->assertEquals(array(
			'php-and-java-dev@example.com',
			'php-and-python-dev@example.com',
			'php-dev@example.com',
			'php-python-and-java-dev@example.com',
		), $users);

		$this->assertEquals(array(
			'java_projects',
			'php_projects',
		), $groups);
		
		$this->assertEquals(array(), $projects);
	}

	public function testViewNonExistant() {
		$this->_fakeLogin(5);
		try{
			$this->testAction('/teams/not_a_real_team', array('return' => 'view', 'method' => 'post'));
		} catch (NotFoundException $e) {
			$this->assertTrue(true, "Correct exception thrown");
			return;
		}
		$this->assertFalse(true, "Should have thrown an exception");
	}

	public function testViewByName() {
		$this->_fakeLogin(3);
		$this->testAction('/teams/view/php_developers', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		$this->assertEquals('php_developers', $this->vars['team']['Team']['name']);
	}

	public function testViewWithCollaboratingTeams() {
		$this->_fakeLogin(3);
		$this->testAction('/teams/view/perl_developers', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();

		$this->assertEquals('perl_developers', $this->vars['team']['Team']['name']);

		$users = array_map(function($a){return $a['email'];}, $this->vars['team']['User']);
		$groups = array_map(function($a){return $a['project_group_name'];}, $this->vars['team']['GroupCollaboratingTeam']);
		$projects = array_map(function($a){return $a['project_name'];}, $this->vars['team']['CollaboratingTeam']);
		sort($users); sort($groups); sort($projects);

		$this->assertEquals(array(
			'another-perl-dev@example.com',
			'perl-dev@example.com',
		), $users);

		$this->assertEquals(array(), $groups);
		$this->assertEquals(array(
			'perl-1',
			'perl-2'
		), $projects);
	}

	public function testAdminViewNotLoggedIn() {
		$this->testAction('/admin/teams/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
		
	}

	public function testAdminViewNormalUser() {
		$this->_fakeLogin(3);
		$this->testAction('/admin/teams/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
		
	}

	public function testAdminViewSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/teams/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRedirect('/teams/1');
		
	}

	public function testAdminIndexNotLoggedIn() {
		$this->testAction('/admin/teams', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminIndexInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/admin/teams', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminIndexInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/admin/teams', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminIndexNormalUser() {
		$this->_fakeLogin(3);
		$this->testAction('/admin/teams', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminIndexSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/teams', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$teams = array_map(function($a) {return $a['Team']['id'];}, $this->vars['teams']);
		sort($teams);
		$this->assertEquals(array(1, 2, 3, 4, 5), $teams);
	}

	public function testAdminAddNotLoggedIn() {
		$this->testAction('/admin/teams/add', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminAddInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/admin/teams/add', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminAddInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/admin/teams/add', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminAddNormalUser() {
		$this->_fakeLogin(3);
		$this->testAction('/admin/teams/add', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminAddForm() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/teams/add', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/admin/teams/add').'"|', $this->view);
	}

	public function testAdminAddSystemAdmin() {
		$this->_fakeLogin(5);
		$postData = array(
			'Team' => array(
				'name' => 'new_team_for_cool_people',
			),
			'User' => array('User' => array(
				1, 2
			)),
		);
		$this->testAction('/admin/teams/add', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		$team = $this->controller->Team->findById($this->controller->Team->getLastInsertID());

		$this->assertEquals('new_team_for_cool_people', $team['Team']['name']);

		$users = array_map(function($a){return $a['email'];}, $team['User']);
		$groups = array_map(function($a){return $a['project_group_name'];}, $team['GroupCollaboratingTeam']);
		$projects = array_map(function($a){return $a['project_name'];}, $team['CollaboratingTeam']);
		sort($users); sort($groups); sort($projects);

		$this->assertEquals(array(
			'Mr.Smith@example.com',
			'mrs.smith@example.com',
		), $users);

		$this->assertEquals(array(), $groups);
		$this->assertEquals(array(), $projects);
	}

	public function testAdminAddFail() {
		$this->_fakeLogin(5);
		$postData = array(
			'Team' => array(
				'name' => 'new_team_for_cool_people',
			),
			'User' => array('User' => array(
				1, 2
			)),
		);

		$this->controller->Team = $this->getMockForModel('Team', array('save'));
		$this->controller->Team
			->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$this->testAction('/admin/teams/add', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

	}

	public function testAdminEditNotLoggedIn() {
		$this->testAction('/admin/teams/edit/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminEditInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/admin/teams/edit/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminEditNormalUser() {
		$this->_fakeLogin(3);
		$this->testAction('/admin/teams/edit/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAdminEditForm() {
		$this->_fakeLogin(5);
		$this->testAction('/admin/teams/edit/1', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/admin/teams/edit/1').'"|', $this->view);
	}

	public function testEditNonExistant() {
		$this->_fakeLogin(5);
		try{
			$this->testAction('/admin/teams/edit/999', array('return' => 'view', 'method' => 'post'));
		} catch (NotFoundException $e) {
			$this->assertTrue(true, "Correct exception thrown");
			return;
		}
		$this->assertFalse(true, "Should have thrown an exception");
	}

	public function testAdminEditSystemAdmin() {
		$this->_fakeLogin(5);
		$postData = array(
			'Team' => array(
				'id' => 1,
				'name' => 'new_team_for_cool_people',
			),
			'User' => array('User' => array(
				1, 2
			)),
		);
		$this->testAction('/admin/teams/edit/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		$team = $this->controller->Team->findById(1);

		$this->assertEquals('new_team_for_cool_people', $team['Team']['name']);

		// TODO use names instead when the model pulls them in
		$users = array_map(function($a){return $a['email'];}, $team['User']);
		$groups = array_map(function($a){return $a['project_group_id'];}, $team['GroupCollaboratingTeam']);
		$projects = array_map(function($a){return $a['project_id'];}, $team['CollaboratingTeam']);
		sort($users); sort($groups); sort($projects);

		$this->assertEquals(array(
			'Mr.Smith@example.com',
			'mrs.smith@example.com',
		), $users);

		$this->assertEquals(array(1, 2), $groups);
		$this->assertEquals(array(), $projects);
	}

	public function testAdminEditFail() {
		$this->_fakeLogin(5);
		$postData = array(
			'Team' => array(
				'id' => 1,
				'name' => 'new_team_for_cool_people',
			),
			'User' => array('User' => array(
				1, 2
			)),
		);

		$this->controller->Team = $this->getMockForModel('Team', array('save'));
		$this->controller->Team
			->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$this->testAction('/admin/teams/edit/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

	}

	public function testAdminDeleteNotLoggedIn() {
		$this->testAction('/admin/teams/delete/1', array('return' => 'view', 'method' => 'post'));
		$this->assertNotAuthorized();
	}

	public function testAdminDeleteInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/admin/teams/delete/1', array('return' => 'view', 'method' => 'post'));
		$this->assertNotAuthorized();
	}

	public function testAdminDeleteNormalUser() {
		$this->_fakeLogin(3);
		$this->testAction('/admin/teams/delete/1', array('return' => 'view', 'method' => 'post'));
		$this->assertNotAuthorized();
	}

	public function testDeleteNonExistant() {
		$this->_fakeLogin(5);
		try{
			$this->testAction('/admin/teams/delete/999', array('return' => 'view', 'method' => 'post'));
		} catch (NotFoundException $e) {
			$this->assertTrue(true, "Correct exception thrown");
			return;
		}
		$this->assertFalse(true, "Should have thrown an exception");
	}

	public function testAdminDeleteSystemAdmin() {
		$this->_fakeLogin(5);
		$postData = array();
		$this->testAction('/admin/teams/delete/1', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		$team = $this->controller->Team->findById(1);

		$this->assertEmpty($team);
	}

	public function testAdminDeleteFail() {
		$this->_fakeLogin(5);

		$this->controller->Team = $this->getMockForModel('Team', array('delete'));
		$this->controller->Team
			->expects($this->any())
			->method('delete')
			->will($this->returnValue(false));

		$this->testAction('/admin/teams/delete/1', array('return' => 'view', 'method' => 'post'));
		$this->assertAuthorized();

	}

	public function testApiAutocompleteNotLoggedIn() {
		$this->testAction('/api/teams/autocomplete');
		$this->assertNotAuthorized();
	}

	public function testApiAutocompleteNormalUser() {
		$this->_fakeLogin(3);
		$this->testAction('/api/teams/autocomplete');
		$this->assertAuthorized();
	}

	public function testApiAutocompleteSystemAdmin() {
		$this->_fakeLogin(5);
		$this->testAction('/api/teams/autocomplete');
		$this->assertAuthorized();
	}

	public function testApiAutocompleteTwoCharsAndBelow() {
		$this->_fakeLogin(3);
		$this->testAction('/api/teams/autocomplete?query=p');
		$this->assertAuthorized();
		$this->assertEquals(array('php_developers [Devs who code in PHP]', 'python_developers [Devs who code in Python]', 'perl_developers [Devs who code in Perl]'), $this->vars['data']['teams']);

		$this->testAction('/api/teams/autocomplete?query=py');
		$this->assertAuthorized();
		$this->assertEquals(array('python_developers [Devs who code in Python]'), $this->vars['data']['teams']);

		$this->testAction('/api/teams/autocomplete?query=er');
		$this->assertAuthorized();
		$this->assertEquals(array(), $this->vars['data']['teams']);
	}

	public function testApiAutocompleteThreeCharsAndAbove() {
		$this->_fakeLogin(3);
		$this->testAction('/api/teams/autocomplete?query=ers');
		$this->assertAuthorized();
		$this->assertEquals(array('php_developers [Devs who code in PHP]', 'java_developers [Devs who code in Java]', 'python_developers [Devs who code in Python]', 'perl_developers [Devs who code in Perl]', 'other_developers [Other devs who code in erlang or something]'), $this->vars['data']['teams']);

		$this->testAction('/api/teams/autocomplete?query=devel');
		$this->assertAuthorized();
		$this->assertEquals(array('php_developers [Devs who code in PHP]', 'java_developers [Devs who code in Java]', 'python_developers [Devs who code in Python]', 'perl_developers [Devs who code in Perl]', 'other_developers [Other devs who code in erlang or something]'), $this->vars['data']['teams']);


	}

}
