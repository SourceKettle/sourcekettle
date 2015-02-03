<?php
App::uses('LoginController', 'Controller');
require_once(__DIR__ . DS . 'AppControllerTest.php');

/**
 * LoginController Test Case
 *
 */
class LoginControllerTest extends AppControllerTest {

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

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndexGetRedirect() {
		
		$ret = $this->testAction('/login', array('method' => 'get', 'return' => 'view'));
		$this->assertRedirect(array('controller' => 'pages', 'action' => 'home'));
	}

	public function testIndexInvalidUser() {
		
		$postData = array('User' => array('email' => 'moose@example.org', 'password' => 'SomePassword'));
		$ret = $this->testAction('/login', array('method' => 'post', 'return' => 'view', 'data' => $postData));
debug($ret);
		$this->assertRedirect(array('controller' => 'pages', 'action' => 'home'));
	}
/**
 * testLogout method
 *
 * @return void
 */
	public function testLogout() {
		$this->markTestIncomplete('testLogout not implemented.');
	}

}
