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
		parent::setUp('Source');
		ClassRegistry::init("Setting")->saveSettingsTree(array('Setting' => array('Features' => array('source_enabled' => true)), false));
		$this->Source = new TestSourceController();
		$this->Source->constructClasses();
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

	public function testIndexFeatureDisabledOnSystem() {

		ClassRegistry::init("Setting")->saveSettingsTree(array('Setting' => array('Features' => array('source_enabled' => false))));

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

	public function testIndexFeatureDisabledOnProject() {

		ClassRegistry::init("Setting")->saveSettingsTree(array('Setting' => array('Features' => array('source_enabled' => true))), false);
		ClassRegistry::init("Setting")->saveSettingsTree(array('Setting' => array('Features' => array('source_enabled' => false))), true);
		ClassRegistry::init("ProjectSetting")->saveSettingsTree('private', array('ProjectSetting' => array('Features' => array('source_enabled' => false))));
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
		$this->testAction('/project/private/source', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testIndexNotCollaborator() {
		$this->_fakeLogin(23);
		$this->testAction('/project/private/source', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testIndexProjectGuest() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/source', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertRedirect('/project/private/source/tree');
	}

	public function testIndexProjectUser() {
		$this->_fakeLogin(4);
		$this->testAction('/project/private/source', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertRedirect('/project/private/source/tree');
	}

	public function testIndexProjectAdmin() {
		$this->_fakeLogin(1);
		$this->testAction('/project/private/source', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertRedirect('/project/private/source/tree');
	}

	public function testIndexSystemAdmin() {
		$this->_fakeLogin(9);
		$this->testAction('/project/private/source', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertRedirect('/project/private/source/tree');
	}

	public function testIndexInactiveSystemAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/private/source', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}


	public function testTreeNotLoggedIn() {
		$this->testAction('/project/private/source/tree', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testTreeNotCollaborator() {
		$this->_fakeLogin(23);
		$this->testAction('/project/private/source/tree', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testTreeProjectGuest() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/source/tree', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testTreeProjectUser() {
		$this->_fakeLogin(4);
		$this->testAction('/project/private/source/tree', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testTreeProjectAdmin() {
		$this->_fakeLogin(1);
		$this->testAction('/project/private/source/tree', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testTreeSystemAdmin() {
		$this->_fakeLogin(9);
		$this->testAction('/project/private/source/tree', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testTreeInactiveSystemAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/private/source/tree', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testTreeTopLevel() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/source/tree', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();

		$this->assertEquals('master', $this->vars['branch']);
		$this->assertEquals('tree', $this->vars['tree']['type']);

		$this->assertEquals('100644', $this->vars['tree']['content'][0]['permissions']);
		$this->assertEquals('blob', $this->vars['tree']['content'][0]['type']);
		$this->assertEquals('9e6756d0bd5a99fc856850e88220a6329c410360', $this->vars['tree']['content'][0]['hash']);
		$this->assertEquals('asciitext.txt', $this->vars['tree']['content'][0]['name']);
		$this->assertEquals('1436052fb1244a045981392e20efa03f39e0737a', $this->vars['tree']['content'][0]['updated']['hash']);

		$this->assertEquals('100644', $this->vars['tree']['content'][1]['permissions']);
		$this->assertEquals('blob', $this->vars['tree']['content'][1]['type']);
		$this->assertEquals('5a18ee9511038b416881f9dd8926f2d171f93e0e', $this->vars['tree']['content'][1]['hash']);
		$this->assertEquals('hello.c', $this->vars['tree']['content'][1]['name']);
		$this->assertEquals('2325c49ec93a7164bbcbd4a3c0594170d4d9e121', $this->vars['tree']['content'][1]['updated']['hash']);

		$this->assertEquals('100644', $this->vars['tree']['content'][2]['permissions']);
		$this->assertEquals('blob', $this->vars['tree']['content'][2]['type']);
		$this->assertEquals('c718ae6944917bc9272b15df31856f9f237a8085', $this->vars['tree']['content'][2]['hash']);
		$this->assertEquals('sometext.txt', $this->vars['tree']['content'][2]['name']);
		$this->assertEquals('1436052fb1244a045981392e20efa03f39e0737a', $this->vars['tree']['content'][2]['updated']['hash']);
	}

	public function testTreeSpecificFile() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/source/tree/hello.c', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertEquals("#include <stdio.h>\nint main(void) {\n	printf(\"Hello, world!\\n\");\n	\n	// Much less overengineered than the previous version\n	int i;\n	for (i = 0; i < 20; i++) {\n		printf(\"I: %d Foo: %d\\n\", i, i);\n	}\n}", $this->vars['tree']['content']);
		
	}

	

	public function testCommitsNotLoggedIn() {
		$this->testAction('/project/private/source/commits', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testCommitsNotCollaborator() {
		$this->_fakeLogin(23);
		$this->testAction('/project/private/source/commits', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testCommitsProjectGuest() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/source/commits', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testCommitsProjectUser() {
		$this->_fakeLogin(4);
		$this->testAction('/project/private/source/commits', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testCommitsProjectAdmin() {
		$this->_fakeLogin(1);
		$this->testAction('/project/private/source/commits', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testCommitsSystemAdmin() {
		$this->_fakeLogin(9);
		$this->testAction('/project/private/source/commits', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testCommitsInactiveSystemAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/private/source/commits', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testCommitsTopLevel() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/source/commits', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertEquals('master', $this->vars['branch']);
		
		$this->assertEquals('2325c49ec93a7164bbcbd4a3c0594170d4d9e121', $this->vars['commits'][0]['hash']);
		$this->assertEquals('stop overengineering', $this->vars['commits'][0]['subject']);
		
		$this->assertEquals('04022f5b0b7c9f635520f68a511cccfad4330da3', $this->vars['commits'][1]['hash']);
		$this->assertEquals('third checkin ermagerd', $this->vars['commits'][1]['subject']);
		
		$this->assertEquals('1436052fb1244a045981392e20efa03f39e0737a', $this->vars['commits'][2]['hash']);
		$this->assertEquals('second checkin', $this->vars['commits'][2]['subject']);
		
		$this->assertEquals('848f3fe7032a76b180e9831d53e4152fd4da85d9', $this->vars['commits'][3]['hash']);
		$this->assertEquals('first ever checkin', $this->vars['commits'][3]['subject']);
	}


	public function testCommitNotLoggedIn() {
		$this->testAction('/project/private/source/commit/1436052fb1244a045981392e20efa03f39e0737a', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testCommitNotCollaborator() {
		$this->_fakeLogin(23);
		$this->testAction('/project/private/source/commit/1436052fb1244a045981392e20efa03f39e0737a', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testCommitProjectGuest() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/source/commit/1436052fb1244a045981392e20efa03f39e0737a', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testCommitProjectUser() {
		$this->_fakeLogin(4);
		$this->testAction('/project/private/source/commit/1436052fb1244a045981392e20efa03f39e0737a', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testCommitProjectAdmin() {
		$this->_fakeLogin(1);
		$this->testAction('/project/private/source/commit/1436052fb1244a045981392e20efa03f39e0737a', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testCommitSystemAdmin() {
		$this->_fakeLogin(9);
		$this->testAction('/project/private/source/commit/1436052fb1244a045981392e20efa03f39e0737a', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
	}

	public function testCommitInactiveSystemAdmin() {
		$this->_fakeLogin(22);
		$this->testAction('/project/private/source/commit/1436052fb1244a045981392e20efa03f39e0737a', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
	}

	public function testCommitDetails() {
		$this->_fakeLogin(3);
		$this->testAction('/project/private/source/commit/1436052fb1244a045981392e20efa03f39e0737a', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertEquals("1436052fb1244a045981392e20efa03f39e0737a", $this->vars['commit']['hash']);
		$this->assertEquals("second checkin", $this->vars['commit']['subject']);
		$this->assertEquals(array("asciitext.txt", "hello.c", "sometext.txt"), array_keys($this->vars['commit']['diff']));
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

}
