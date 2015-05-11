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
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
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
					'source_enabled' => array('source' => 'defaults', 'locked' => 0, 'value' => false),
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

	public function testIndexNotLoggedIn() {

		// Cannot see the page when not logged in
		$this->testAction('/project/private/source', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testIndexNotUser() {

		$this->_fakeLogin(8);
		$this->testAction('/project/private/source', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testIndexSystemAdmin() {

		// Log in as a system administrator - we should still only see "my" projects, not everyone's
		$this->_fakeLogin(5);

		// Perform the action, and check the user was authorized
		$ret = $this->testAction('/project/private/source', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
		$this->assertRedirect(array('controller' => 'source', 'action' => 'tree', 'project' => 'private'));

	}

	public function testIndexProjectAdmin() {

		// Log in as a guest on one project
		$this->_fakeLogin(1);

		$ret = $this->testAction('/project/private/source', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
		$this->assertRedirect(array('controller' => 'source', 'action' => 'tree', 'project' => 'private'));

	}

	public function testIndexProjectUser() {

		// Log in as a guest on one project
		$this->_fakeLogin(4);

		$ret = $this->testAction('/project/private/source', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
		$this->assertRedirect(array('controller' => 'source', 'action' => 'tree', 'project' => 'private'));

	}

	public function testIndexProjectGuest() {

		// Log in as a guest on one project
		$this->_fakeLogin(3);

		$ret = $this->testAction('/project/private/source', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
		$this->assertRedirect(array('controller' => 'source', 'action' => 'tree', 'project' => 'private'));

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
			$this->testAction('/project/private/source/tree/master', array('method' => 'get', 'return' => 'vars'));
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
			$this->testAction('/project/private/source/tree/master', array('method' => 'get', 'return' => 'vars'));
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

		// Cannot see the page when not logged in
		$this->testAction('/project/private/source/tree/master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testTreeNotUser() {

		$this->_fakeLogin(8);
		$this->testAction('/project/private/source/tree/master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testTreeSystemAdmin() {

		// Log in as a system administrator - we should still only see "my" projects, not everyone's
		$this->_fakeLogin(5);

		// Perform the action, and check the user was authorized
		$ret = $this->testAction('/project/private/source/tree/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testTreeProjectAdmin() {

		// Log in as a guest on one project
		$this->_fakeLogin(1);

		$ret = $this->testAction('/project/private/source/tree/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testTreeProjectUser() {

		// Log in as a guest on one project
		$this->_fakeLogin(4);

		$ret = $this->testAction('/project/private/source/tree/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testTreeProjectGuest() {

		// Log in as a guest on one project
		$this->_fakeLogin(3);

		$ret = $this->testAction('/project/private/source/tree/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testTreeNoBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/tree', array('method' => 'get', 'return' => 'view'));
		$this->assertRedirect(array('controller' => 'source', 'action' => 'tree', 'project' => 'private', 'branch' => 'master'));

	}

	public function testTreeMasterBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/tree/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testTreeSomeNewThingBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/tree/some_new_thing', array('method' => 'get', 'return' => 'view'));
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
			$this->testAction('/project/private/source/commits/master', array('method' => 'get', 'return' => 'vars'));
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
			$this->testAction('/project/private/source/commits/master', array('method' => 'get', 'return' => 'vars'));
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

		// Cannot see the page when not logged in
		$this->testAction('/project/private/source/commits/master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testCommitsNotUser() {

		$this->_fakeLogin(8);
		$this->testAction('/project/private/source/commits/master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testCommitsSystemAdmin() {

		// Log in as a system administrator - we should still only see "my" projects, not everyone's
		$this->_fakeLogin(5);

		// Perform the action, and check the user was authorized
		$ret = $this->testAction('/project/private/source/commits/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitsProjectAdmin() {

		// Log in as a guest on one project
		$this->_fakeLogin(1);

		$ret = $this->testAction('/project/private/source/commits/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitsProjectUser() {

		// Log in as a guest on one project
		$this->_fakeLogin(4);

		$ret = $this->testAction('/project/private/source/commits/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitsProjectGuest() {

		// Log in as a guest on one project
		$this->_fakeLogin(3);

		$ret = $this->testAction('/project/private/source/commits/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitsNoBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/commits', array('method' => 'get', 'return' => 'view'));
		$this->assertRedirect(array('controller' => 'source', 'action' => 'commits', 'project' => 'private', 'branch' => 'master'));

	}

	public function testCommitsMasterBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/commits/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitsSomeNewThingBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/commits/some_new_thing', array('method' => 'get', 'return' => 'view'));
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
			$this->testAction('/project/private/source/commit/master', array('method' => 'get', 'return' => 'vars'));
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
			$this->testAction('/project/private/source/commit/master', array('method' => 'get', 'return' => 'vars'));
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

		// Cannot see the page when not logged in
		$this->testAction('/project/private/source/commit/master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testCommitNotUser() {

		$this->_fakeLogin(8);
		$this->testAction('/project/private/source/commit/master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testCommitSystemAdmin() {

		// Log in as a system administrator - we should still only see "my" projects, not everyone's
		$this->_fakeLogin(5);

		// Perform the action, and check the user was authorized
		$ret = $this->testAction('/project/private/source/commit/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitProjectAdmin() {

		// Log in as a guest on one project
		$this->_fakeLogin(1);

		$ret = $this->testAction('/project/private/source/commit/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitProjectUser() {

		// Log in as a guest on one project
		$this->_fakeLogin(4);

		$ret = $this->testAction('/project/private/source/commit/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitProjectGuest() {

		// Log in as a guest on one project
		$this->_fakeLogin(3);

		$ret = $this->testAction('/project/private/source/commit/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testCommitNoHash() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/commit', array('method' => 'get', 'return' => 'view'));
		$this->assertRedirect(array('controller' => 'source', 'action' => 'commits', 'project' => 'private', 'branch' => 'master'));

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

	public function testAjaxDiffSourceControlDisabledOnSystem() {

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
			$this->testAction('/project/private/source/ajax_diff/master', array('method' => 'get', 'return' => 'vars'));
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

	public function testAjaxDiffSourceControlDisabledOnProject() {

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
			$this->testAction('/project/private/source/ajax_diff/master', array('method' => 'get', 'return' => 'vars'));
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

	public function testAjaxDiffNotLoggedIn() {

		// Cannot see the page when not logged in
		$this->testAction('/project/private/source/ajax_diff/master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAjaxDiffNotUser() {

		$this->_fakeLogin(8);
		$this->testAction('/project/private/source/ajax_diff/master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testAjaxDiffSystemAdmin() {

		// Log in as a system administrator - we should still only see "my" projects, not everyone's
		$this->_fakeLogin(5);

		// Perform the action, and check the user was authorized
		$ret = $this->testAction('/project/private/source/ajax_diff/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testAjaxDiffProjectAdmin() {

		// Log in as a guest on one project
		$this->_fakeLogin(1);

		$ret = $this->testAction('/project/private/source/ajax_diff/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testAjaxDiffProjectUser() {

		// Log in as a guest on one project
		$this->_fakeLogin(4);

		$ret = $this->testAction('/project/private/source/ajax_diff/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testAjaxDiffProjectGuest() {

		// Log in as a guest on one project
		$this->_fakeLogin(3);

		$ret = $this->testAction('/project/private/source/ajax_diff/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testAjaxDiffNoBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/ajax_diff', array('method' => 'get', 'return' => 'view'));
		$this->assertRedirect(array('controller' => 'source', 'action' => 'ajax_diff', 'project' => 'private', 'branch' => 'master'));

	}

	public function testAjaxDiffMasterBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/ajax_diff/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testAjaxDiffSomeNewThingBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/ajax_diff/some_new_thing', array('method' => 'get', 'return' => 'view'));
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
			$this->testAction('/project/private/source/raw/master', array('method' => 'get', 'return' => 'vars'));
			$this->assertNotAuthorized();
		} catch (ForbiddenException $e){
			$this->assertTrue(true, "Correct exception thrown");
			return;
		//} catch (Exception $e){
		//	$this->assertTrue(false, "Incorrect exception thrown: ".$e->getMessage());
		//	return;
		}
		//$this->assertTrue(false, "No exception thrown");
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
			$this->testAction('/project/private/source/raw/master', array('method' => 'get', 'return' => 'vars'));
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

	public function testRawNotLoggedIn() {

		// Cannot see the page when not logged in
		$this->testAction('/project/private/source/raw/master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testRawNotUser() {

		$this->_fakeLogin(8);
		$this->testAction('/project/private/source/raw/master', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testRawSystemAdmin() {

		// Log in as a system administrator - we should still only see "my" projects, not everyone's
		$this->_fakeLogin(5);

		// Perform the action, and check the user was authorized
		$ret = $this->testAction('/project/private/source/raw/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testRawProjectAdmin() {

		// Log in as a guest on one project
		$this->_fakeLogin(1);

		$ret = $this->testAction('/project/private/source/raw/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testRawProjectUser() {

		// Log in as a guest on one project
		$this->_fakeLogin(4);

		$ret = $this->testAction('/project/private/source/raw/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testRawProjectGuest() {

		// Log in as a guest on one project
		$this->_fakeLogin(3);

		$ret = $this->testAction('/project/private/source/raw/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testRawNoBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/raw', array('method' => 'get', 'return' => 'view'));
		$this->assertRedirect(array('controller' => 'source', 'action' => 'raw', 'project' => 'private', 'branch' => 'master'));

	}

	public function testRawMasterBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/raw/master', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();

	}

	public function testRawSomeNewThingBranch() {

		$this->_fakeLogin(5);
		$ret = $this->testAction('/project/private/source/raw/some_new_thing', array('method' => 'get', 'return' => 'view'));
		$this->assertAuthorized();
	}
}
