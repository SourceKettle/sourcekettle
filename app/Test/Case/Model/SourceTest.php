<?php
App::uses('Source', 'Model');

/**
 * Source Test Case
 *
 */
class SourceTest extends CakeTestCase {

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
		parent::setUp();
		$this->repoDir = realpath(__DIR__.'/../../Fixture').'/repositories';
		$this->Source = ClassRegistry::init('Source');
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

	private function __loadRepo($projectId) {
		$this->Source->Project->id = $projectId;
		$this->Source->init();
	}

	public function testGetType() {
		$this->__loadRepo(1);
		$this->assertEquals(RepoTypes::GIT, $this->Source->getType());
	}

	public function testGetBranches() {
		$this->__loadRepo(1);
		$this->assertEquals(array('master'), $this->Source->getBranches());
	}

	public function testGetDefaultBranch() {
		$this->__loadRepo(1);
		$this->assertEquals('master', $this->Source->getDefaultBranch());
	}

	public function testGetRepositoryLocation() {
		$this->__loadRepo(1);
		$this->assertEquals($this->repoDir.'/private.git/', $this->Source->getRepositoryLocation());
	}

	public function testFetchHistory() {
		$this->__loadRepo(1);
		$history = $this->Source->fetchHistory("private");
		$url = array('api' => false, 'project' => 'private', 'controller' => 'source', 'action' => 'commit');
		$commits = array(
			'2325c49ec93a7164bbcbd4a3c0594170d4d9e121' => 'Mrs.Smith@example.com',
			'04022f5b0b7c9f635520f68a511cccfad4330da3' => 'Mr.Smith@example.com',
			'1436052fb1244a045981392e20efa03f39e0737a' => 'Mr.Smith@example.com',
			'848f3fe7032a76b180e9831d53e4152fd4da85d9' => 'Mr.Smith@example.com',
		);
		$hashes = array_keys($commits);
		for ($i = 0; $i < count($history); $i++) {
			$hash = $hashes[$i];
			$email = $commits[$hash];
			$this->assertEquals($hash, $history[$i]['Subject']['id']);
			$this->assertEquals($email, $history[$i]['Actioner']['email']);
			$url[0] = $hash;
			$this->assertEquals($url, $history[$i]['url']);
		}
	}

}
