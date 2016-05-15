<?php
App::uses('StoriesController', 'Controller');
require_once(__DIR__ . DS . 'AppControllerTest.php');

/**
 * StoriesController Test Case
 *
 */
class StoriesControllerTest extends AppControllerTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'core.cake_session',
		'app.story',
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

	public function setUp($controllerName = null) {
		parent::setUp("Stories");
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
				'Features' => array(
					'source_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'story_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'time_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'task_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'attachment_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => false),
				),
				'SourceRepository' => array(
					'base' => array('source' => 'defaults', 'locked' => 0, 'value' => dirname(dirname(dirname(__DIR__))).'/Test/Fixture/repositories'),
					'user' => array('source' => 'defaults', 'locked' => 0, 'value' => 'gituser'), // Non-standard username
				),
			)));
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndexFeatureDisabledOnSystem() {

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
				'Features' => array(
					'source_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'story_enabled' => array('source' => 'System settings', 'locked' => 0, 'value' => false),
					'time_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'task_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'attachment_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => false),
				),
				'SourceRepository' => array(
					'base' => array('source' => 'defaults', 'locked' => 0, 'value' => dirname(dirname(dirname(__DIR__))).'/Test/Fixture/repositories'),
					'user' => array('source' => 'defaults', 'locked' => 0, 'value' => 'gituser'), // Non-standard username
				),
			)));

		// Cannot see the page when not logged in
		try{
			$this->testAction('/project/private/stories', array('method' => 'get', 'return' => 'vars'));
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
				'Features' => array(
					'source_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'story_enabled' => array('source' => 'Project-specific settings', 'locked' => 0, 'value' => false),
					'time_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'task_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'attachment_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => false),
				),
				'SourceRepository' => array(
					'base' => array('source' => 'defaults', 'locked' => 0, 'value' => dirname(dirname(dirname(__DIR__))).'/Test/Fixture/repositories'),
					'user' => array('source' => 'defaults', 'locked' => 0, 'value' => 'gituser'), // Non-standard username
				),
			)));

		// Cannot see the page when not logged in
		try{
			$this->testAction('/project/private/stories', array('method' => 'get', 'return' => 'vars'));
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

	public function testIndexTasksDisabledOnSystem() {

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
				'Features' => array(
					'source_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'story_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'time_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'task_enabled' => array('source' => 'System settings', 'locked' => 0, 'value' => false),
					'attachment_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => false),
				),
				'SourceRepository' => array(
					'base' => array('source' => 'defaults', 'locked' => 0, 'value' => dirname(dirname(dirname(__DIR__))).'/Test/Fixture/repositories'),
					'user' => array('source' => 'defaults', 'locked' => 0, 'value' => 'gituser'), // Non-standard username
				),
			)));

		// Cannot see the page when not logged in
		try{
			$this->testAction('/project/private/stories', array('method' => 'get', 'return' => 'vars'));
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

	public function testIndexTasksDisabledOnProject() {

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
				'Features' => array(
					'source_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'story_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'time_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => true),
					'task_enabled' => array('source' => 'Project-specific settings', 'locked' => 0, 'value' => false),
					'attachment_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => false),
				),
				'SourceRepository' => array(
					'base' => array('source' => 'defaults', 'locked' => 0, 'value' => dirname(dirname(dirname(__DIR__))).'/Test/Fixture/repositories'),
					'user' => array('source' => 'defaults', 'locked' => 0, 'value' => 'gituser'), // Non-standard username
				),
			)));

		// Cannot see the page when not logged in
		try{
			$this->testAction('/project/private/stories', array('method' => 'get', 'return' => 'vars'));
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
		$this->testAction('/project/private/stories', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testIndexInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/private/stories', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testIndexInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/private/stories', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

/**
 * testView method
 *
 * @return void
 */
	public function testViewNotLoggedIn() {

		// Cannot see the page when not logged in
		$this->testAction('/project/public/stories/view/1', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testViewInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/public/stories/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testViewInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/public/stories/view/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAddNotLoggedIn() {

		// Cannot see the page when not logged in
		$this->testAction('/project/private/stories/add', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAddInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/private/stories/add', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testAddInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/private/stories/add', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

/**
 * testEdit method
 *
 * @return void
 */
	public function testEditNotLoggedIn() {

		// Cannot see the page when not logged in
		$this->testAction('/project/public/stories/edit/1', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testEditInactiveUser() {
		$this->_fakeLogin(6);
		$this->testAction('/project/public/stories/edit/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

	public function testEditInactiveAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/public/stories/edit/1', array('return' => 'view', 'method' => 'get'));
		$this->assertNotAuthorized();
	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {
		$this->markTestIncomplete('testDelete not implemented.');
	}


}
