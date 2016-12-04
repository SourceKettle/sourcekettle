<?php
App::uses('SourceController', 'Controller');
require_once(__DIR__ . DS . 'AppControllerTest.php');

/**
 * TestSourceController *
 */
class TestSourceController extends SourceController {

/**
 * Auto render
 *
 * @var boolean
 */
	public $autoRender = false;

/**
 * Redirect action
 *
 * @param mixed $url
 * @param mixed $status
 * @param boolean $exit
 * @return void
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}

}

/**
 * SourceController Test Case
 *
 */
class SourceControllerTestCase extends AppControllerTest {

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
		'app.story',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp($controllerName = null) {
		parent::setUp("Source");
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
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Source);
		parent::tearDown();
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndexSourceControlDisabledOnSystem() {

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
					'source_enabled' => array('source' => 'System settings', 'locked' => 0, 'value' => false),
				),
			)));
		$this->_fakeLogin(5);
		try{
			$this->testAction('/project/private/source', array('method' => 'get', 'return' => 'vars'));
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

	public function testIndexSourceControlDisabledOnProject() {

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
					'source_enabled' => array('source' => 'Project-specific settings', 'locked' => 0, 'value' => false),
				),
			)));

		$this->_fakeLogin(5);
		try{
			$this->testAction('/project/private/source', array('method' => 'get', 'return' => 'vars'));
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



	public function testGettingStartedNotLoggedIn() {
		$this->testAction('/project/private/source/gettingStarted', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testGettingStartedNotCollaborator() {
		$this->_fakeLogin(23);
		$this->testAction('/project/private/source/gettingStarted', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testGettingStartedProjectGuest() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/source/gettingStarted', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testGettingStartedProjectUser() {
		$this->_fakeLogin(4);
		$this->testAction('/project/private/source/gettingStarted', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testGettingStartedProjectAdmin() {
		$this->_fakeLogin(1);
		$this->testAction('/project/private/source/gettingStarted', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testGettingStartedSystemAdmin() {
		$this->_fakeLogin(9);
		$this->testAction('/project/private/source/gettingStarted', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testGettingStartedInactiveSystemAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/private/source/gettingStarted', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testGettingStartedGit() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/source/gettingStarted', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertContains('git init', $this->view);
	}

	public function testIndexSystemAdmin() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
		$this->assertRedirect('/project/private/source/tree');

	}

	public function testIndexProjectAdmin() {

		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/private/source', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
        $this->assertRedirect('/project/private/source/tree');

	}

	public function testIndexProjectUser() {

		$this->_fakeLogin(4);
		$ret = $this->testAction('/project/private/source', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
        $this->assertRedirect('/project/private/source/tree');

	}

	public function testIndexProjectGuest() {

		$this->_fakeLogin(3);
		$ret = $this->testAction('/project/private/source', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
        $this->assertRedirect('/project/private/source/tree');

	}

	public function testTreeSourceControlDisabledOnSystem() {

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
					'source_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => false),
				),
			)));
		$this->_fakeLogin(5);
		try{
			$this->testAction('/project/private/source/tree?branch=master', array('method' => 'get', 'return' => 'vars'));
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

	public function testTreeSourceControlDisabledOnProject() {

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
					'source_enabled' => array('source' => 'Project-specific settings', 'locked' => 0, 'value' => false),
				),
			)));

		$this->_fakeLogin(5);
		try{
			$this->testAction('/project/private/source/tree?branch=master', array('method' => 'get', 'return' => 'vars'));
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

	public function testTreeNotLoggedIn() {

		$this->testAction('/project/private/source/tree?branch=master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testTreeNotUser() {

		$this->_fakeLogin(8);
		$this->testAction('/project/private/source/tree?branch=master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testTreeSystemAdmin() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/tree?branch=master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testTreeProjectAdmin() {

		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/private/source/tree?branch=master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testTreeProjectUser() {

		$this->_fakeLogin(4);
		$ret = $this->testAction('/project/private/source/tree?branch=master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testTreeProjectGuest() {

		$this->_fakeLogin(3);
		$ret = $this->testAction('/project/private/source/tree?branch=master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testTreeNoBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/tree', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testTreeMasterBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/tree?branch=master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testTreeSomeNewThingBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/tree?branch=some_new_thing', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitsSourceControlDisabledOnSystem() {

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
					'source_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => false),
				),
			)));
		$this->_fakeLogin(5);
		try{
			$this->testAction('/project/private/source/commits?branch=master', array('method' => 'get', 'return' => 'vars'));
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

	public function testCommitsSourceControlDisabledOnProject() {

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
					'source_enabled' => array('source' => 'Project-specific settings', 'locked' => 0, 'value' => false),
				),
			)));

		$this->_fakeLogin(5);
		try{
			$this->testAction('/project/private/source/commits?branch=master', array('method' => 'get', 'return' => 'vars'));
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

	public function testCommitsNotLoggedIn() {

		$this->testAction('/project/private/source/commits?branch=master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testCommitsNotUser() {

		$this->_fakeLogin(8);
		$this->testAction('/project/private/source/commits?branch=master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testCommitsSystemAdmin() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/commits?branch=master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitsProjectAdmin() {

		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/private/source/commits?branch=master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitsProjectUser() {

		$this->_fakeLogin(4);
		$ret = $this->testAction('/project/private/source/commits?branch=master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitsProjectGuest() {

		$this->_fakeLogin(3);
		$ret = $this->testAction('/project/private/source/commits?branch=master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitsNoBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/commits', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitsMasterBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/commits?branch=master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitsSomeNewThingBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/commits?branch=some_new_thing', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitSourceControlDisabledOnSystem() {

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
					'source_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => false),
				),
			)));
		$this->_fakeLogin(5);
		try{
			$this->testAction('/project/private/source/commit?branch=master', array('method' => 'get', 'return' => 'vars'));
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

	public function testCommitSourceControlDisabledOnProject() {

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
					'source_enabled' => array('source' => 'Project-specific settings', 'locked' => 0, 'value' => false),
				),
			)));

		$this->_fakeLogin(5);
		try{
			$this->testAction('/project/private/source/commit?branch=master', array('method' => 'get', 'return' => 'vars'));
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

	public function testCommitNotLoggedIn() {

		$this->testAction('/project/private/source/commit?branch=master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testCommitNotUser() {

		$this->_fakeLogin(8);
		$this->testAction('/project/private/source/commit?branch=master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testCommitSystemAdmin() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/commit?branch=master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitProjectAdmin() {

		$this->_fakeLogin(1);
		$ret = $this->testAction('/project/private/source/commit?branch=master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitProjectUser() {

		$this->_fakeLogin(4);
		$ret = $this->testAction('/project/private/source/commit?branch=master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitProjectGuest() {

		$this->_fakeLogin(3);
		$ret = $this->testAction('/project/private/source/commit?branch=master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitNoHash() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/commit', array('method' => 'get', 'return' => 'view'));
		$this->assertRedirect('/project/private/source/commits?branch=master');

	}

	public function testCommitMasterBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/commit/04022f5b0b7c9f635520f68a511cccfad4330da3', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitSomeNewThingBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/commit/0b20ced61a6edb811ddbe3c502b931b0450f3a61', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testRawSourceControlDisabledOnSystem() {

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
					'source_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => false),
				),
			)));
		$this->_fakeLogin(5);
		try{
			$this->testAction('/project/private/source/raw/hello.c?branch=04022f5b0b7c9f635520f68a511cccfad4330da3', array('method' => 'get', 'return' => 'vars'));
			$this->assertNotAuthorized();
		} catch (ForbiddenException $e){
			$this->assertTrue(true, "Correct exception thrown");
			return;
		}
		$this->assertTrue(false, "No exception thrown");
	}

	public function testRawSourceControlDisabledOnProject() {

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
					'source_enabled' => array('source' => 'Project-specific settings', 'locked' => 0, 'value' => false),
				),
			)));

		$this->_fakeLogin(5);
		try{
			$this->testAction('/project/private/source/raw/hello.c?branch=04022f5b0b7c9f635520f68a511cccfad4330da3', array('method' => 'get', 'return' => 'vars'));
			$this->assertNotAuthorized();
		} catch (ForbiddenException $e){
			$this->assertTrue(true, "Correct exception thrown");
			return;
		}
		$this->assertTrue(false, "No exception thrown");
	}

	public function testRawNotLoggedIn() {

		$this->testAction('/project/private/source/raw/hello.c?branch=04022f5b0b7c9f635520f68a511cccfad4330da3', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testRawNotUser() {

		$this->_fakeLogin(8);
		$this->testAction('/project/private/source/raw/hello.c?branch=04022f5b0b7c9f635520f68a511cccfad4330da3', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testRawSystemAdmin() {

		$this->_fakeLogin(5);
		$this->testAction('/project/private/source/raw/hello.c?branch=04022f5b0b7c9f635520f68a511cccfad4330da3', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();

	}

	public function testRawProjectAdmin() {

		$this->_fakeLogin(1);
		$this->testAction('/project/private/source/raw/hello.c?branch=04022f5b0b7c9f635520f68a511cccfad4330da3', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();

	}

	public function testRawProjectUser() {

		$this->_fakeLogin(4);
		$this->testAction('/project/private/source/raw/hello.c?branch=04022f5b0b7c9f635520f68a511cccfad4330da3', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();

	}

	public function testRawProjectGuest() {

		// Log in as a guest on one project
		$this->_fakeLogin(3);

		$this->testAction('/project/private/source/raw/hello.c?branch=04022f5b0b7c9f635520f68a511cccfad4330da3', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertEquals('text/x-c; charset=us-ascii', $this->vars['mimeType']);
		$this->assertEquals(
		"#include <stdio.h>\n" .
		"#include <stdlib.h>\n" .
		"int main(void) {\n" .
		"	printf(\"Hello, world!\\n\");\n" .
		"	\n" .
		"	int i;\n" .
		"	int *foo = (int *) malloc(sizeof(int) * 20);\n" .
		"	for (i = 0; i < 20; i++) {\n" .
		"		foo[i] = i;\n" .
		"	}\n" .
		"	for (i = 0; i < 20; i++) {\n" .
		"		printf(\"I: %d Foo: %d\\n\", i, foo[i]);\n" .
		"	}\n" .
		"}", $this->vars['sourceFile']);

	}

}
