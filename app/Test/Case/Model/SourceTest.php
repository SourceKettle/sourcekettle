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

	private $REPO_BASE;
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
		$this->assertEquals(array('master', 'some_new_thing'), $this->Source->getBranches());
	}

	public function testGetDefaultBranch() {
		$this->__loadRepo(1);
		$this->assertEquals('master', $this->Source->getDefaultBranch());
	}

	public function testGetRepositoryLocation() {
		$this->__loadRepo(1);
		$this->assertEquals($this->repoDir.'/private.git/', $this->Source->getRepositoryLocation());
	}


/**
 * testFetchHistory method
 *
 * @return void
 */
	public function testFetchHistory() {
		$this->__loadRepo(1);
		$history = $this->Source->fetchHistory();
		$this->assertEquals(array(
			array(
				'modified' => '2015-05-06 19:05:38',
				'Type' => 'Source',
				'Project' => array(
					'id' => null,
					'name' => null
				),
				'Actioner' => array(
					'id' => -1,
					'name' => 'Andy Newton',
					'email' => 'amn@ecs.soton.ac.uk',
					'exists' => false
				),
				'Subject' => array(
					'id' => '0b20ced61a6edb811ddbe3c502b931b0450f3a61',
					'title' => 'make a change on the new thing branch',
					'exists' => true
				),
				'Change' => array(
					'field' => '+',
					'field_old' => null,
					'field_new' => null
				),
				'url' => array(
					'api' => false,
					'project' => null,
					'controller' => 'source',
					'action' => 'commit',
					'0b20ced61a6edb811ddbe3c502b931b0450f3a61'
				)
			),
			array(
				'modified' => '2014-11-03 22:11:12',
				'Type' => 'Source',
				'Project' => array(
					'id' => null,
					'name' => null
				),
				'Actioner' => array(
					'id' => '2',
					'name' => 'Mrs Smith',
					'email' => 'Mrs.Smith@example.com',
					'exists' => true
				),
				'Subject' => array(
					'id' => '2325c49ec93a7164bbcbd4a3c0594170d4d9e121',
					'title' => 'stop overengineering',
					'exists' => true
				),
				'Change' => array(
					'field' => '+',
					'field_old' => null,
					'field_new' => null
				),
				'url' => array(
					'api' => false,
					'project' => null,
					'controller' => 'source',
					'action' => 'commit',
					'2325c49ec93a7164bbcbd4a3c0594170d4d9e121'
				)
			),
			array(
				'modified' => '2014-11-03 22:11:12',
				'Type' => 'Source',
				'Project' => array(
					'id' => null,
					'name' => null
				),
				'Actioner' => array(
					'id' => '2',
					'name' => 'Mrs Smith',
					'email' => 'Mrs.Smith@example.com',
					'exists' => true
				),
				'Subject' => array(
					'id' => '2325c49ec93a7164bbcbd4a3c0594170d4d9e121',
					'title' => 'stop overengineering',
					'exists' => true
				),
				'Change' => array(
					'field' => '+',
					'field_old' => null,
					'field_new' => null
				),
				'url' => array(
					'api' => false,
					'project' => null,
					'controller' => 'source',
					'action' => 'commit',
					'2325c49ec93a7164bbcbd4a3c0594170d4d9e121'
				)
			),
			array(
				'modified' => '2014-11-03 22:11:02',
				'Type' => 'Source',
				'Project' => array(
					'id' => null,
					'name' => null
				),
				'Actioner' => array(
					'id' => '1',
					'name' => 'Mr Smith',
					'email' => 'Mr.Smith@example.com',
					'exists' => true
				),
				'Subject' => array(
					'id' => '04022f5b0b7c9f635520f68a511cccfad4330da3',
					'title' => 'third checkin ermagerd',
					'exists' => true
				),
				'Change' => array(
					'field' => '+',
					'field_old' => null,
					'field_new' => null
				),
				'url' => array(
					'api' => false,
					'project' => null,
					'controller' => 'source',
					'action' => 'commit',
					'04022f5b0b7c9f635520f68a511cccfad4330da3'
				)
			),
			array(
				'modified' => '2014-11-03 22:11:02',
				'Type' => 'Source',
				'Project' => array(
					'id' => null,
					'name' => null
				),
				'Actioner' => array(
					'id' => '1',
					'name' => 'Mr Smith',
					'email' => 'Mr.Smith@example.com',
					'exists' => true
				),
				'Subject' => array(
					'id' => '04022f5b0b7c9f635520f68a511cccfad4330da3',
					'title' => 'third checkin ermagerd',
					'exists' => true
				),
				'Change' => array(
					'field' => '+',
					'field_old' => null,
					'field_new' => null
				),
				'url' => array(
					'api' => false,
					'project' => null,
					'controller' => 'source',
					'action' => 'commit',
					'04022f5b0b7c9f635520f68a511cccfad4330da3'
				)
			),
			array(
				'modified' => '2014-11-03 22:10:57',
				'Type' => 'Source',
				'Project' => array(
					'id' => null,
					'name' => null
				),
				'Actioner' => array(
					'id' => '1',
					'name' => 'Mr Smith',
					'email' => 'Mr.Smith@example.com',
					'exists' => true
				),
				'Subject' => array(
					'id' => '1436052fb1244a045981392e20efa03f39e0737a',
					'title' => 'second checkin',
					'exists' => true
				),
				'Change' => array(
					'field' => '+',
					'field_old' => null,
					'field_new' => null
				),
				'url' => array(
					'api' => false,
					'project' => null,
					'controller' => 'source',
					'action' => 'commit',
					'1436052fb1244a045981392e20efa03f39e0737a'
				)
			),
			array(
				'modified' => '2014-11-03 22:10:57',
				'Type' => 'Source',
				'Project' => array(
					'id' => null,
					'name' => null
				),
				'Actioner' => array(
					'id' => '1',
					'name' => 'Mr Smith',
					'email' => 'Mr.Smith@example.com',
					'exists' => true
				),
				'Subject' => array(
					'id' => '1436052fb1244a045981392e20efa03f39e0737a',
					'title' => 'second checkin',
					'exists' => true
				),
				'Change' => array(
					'field' => '+',
					'field_old' => null,
					'field_new' => null
				),
				'url' => array(
					'api' => false,
					'project' => null,
					'controller' => 'source',
					'action' => 'commit',
					'1436052fb1244a045981392e20efa03f39e0737a'
				)
			),
			array(
				'modified' => '2014-11-03 22:10:56',
				'Type' => 'Source',
				'Project' => array(
					'id' => null,
					'name' => null
				),
				'Actioner' => array(
					'id' => '1',
					'name' => 'Mr Smith',
					'email' => 'Mr.Smith@example.com',
					'exists' => true
				),
				'Subject' => array(
					'id' => '848f3fe7032a76b180e9831d53e4152fd4da85d9',
					'title' => 'first ever checkin',
					'exists' => true
				),
				'Change' => array(
					'field' => '+',
					'field_old' => null,
					'field_new' => null
				),
				'url' => array(
					'api' => false,
					'project' => null,
					'controller' => 'source',
					'action' => 'commit',
					'848f3fe7032a76b180e9831d53e4152fd4da85d9'
				)
			),
			array(
				'modified' => '2014-11-03 22:10:56',
				'Type' => 'Source',
				'Project' => array(
					'id' => null,
					'name' => null
				),
				'Actioner' => array(
					'id' => '1',
					'name' => 'Mr Smith',
					'email' => 'Mr.Smith@example.com',
					'exists' => true
				),
				'Subject' => array(
					'id' => '848f3fe7032a76b180e9831d53e4152fd4da85d9',
					'title' => 'first ever checkin',
					'exists' => true
				),
				'Change' => array(
					'field' => '+',
					'field_old' => null,
					'field_new' => null
				),
				'url' => array(
					'api' => false,
					'project' => null,
					'controller' => 'source',
					'action' => 'commit',
					'848f3fe7032a76b180e9831d53e4152fd4da85d9'
				)
			)
		), $history);
	}

	public function testHistoryForProject() {
		$this->Source->init();
		$history = array_map(function($a) {return $a['Subject']['id'];}, $this->Source->fetchHistory(1));

		// TODO change history function to return history for a single branch, remove duplicates from this list
		$this->assertEquals(array(
			'0b20ced61a6edb811ddbe3c502b931b0450f3a61',
			'2325c49ec93a7164bbcbd4a3c0594170d4d9e121',
			'2325c49ec93a7164bbcbd4a3c0594170d4d9e121',
			'04022f5b0b7c9f635520f68a511cccfad4330da3',
			'04022f5b0b7c9f635520f68a511cccfad4330da3',
			'1436052fb1244a045981392e20efa03f39e0737a',
			'1436052fb1244a045981392e20efa03f39e0737a',
			'848f3fe7032a76b180e9831d53e4152fd4da85d9',
			'848f3fe7032a76b180e9831d53e4152fd4da85d9',
		), $history);
>>>>>>> hotfix/1.6.4-bugs
	}

	public function testCreateRepository() {
		
		$this->Source->init();
		$this->Source->Project->id = 2;
		$this->Source->Project->saveField('repo_type', 2);
		$this->assertTrue($this->Source->create(array()));
		
	}

	public function testCloneRepository() {
		
		$this->Source->init();
		$this->Source->Project->id = 2;
		$this->Source->Project->saveField('repo_type', 2);
		$this->assertTrue($this->Source->create(array('cloneFrom' => 'private')));
	}
}
