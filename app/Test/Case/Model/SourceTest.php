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
		'app.source',
		'app.project',
		'app.repo_type',
		'app.setting',
		'app.user',
		'app.collaborator',
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
		'app.teams_user',
		'app.team',
	);

	private $REPO_BASE;
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
	 	$this->REPO_BASE = dirname(dirname(dirname(__DIR__))).'/Test/Fixture/repositories';
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

/**
 * testInit method
 *
 * @return void
 */
	public function testInit() {
		$this->Source->init();
	}
/**
 * testGetType method
 *
 * @return void
 */
	public function testGetType() {
		$this->Source->init();
		$this->Source->Project->id = 1;
		$this->assertEquals(RepoTypes::GIT, $this->Source->getType());
	}

/**
 * testGetBranches method
 *
 * @return void
 */
	public function testGetBranches() {
		$this->Source->init();
		$this->Source->Project->id = 1;
		$this->assertEquals(array('master', 'some_new_thing'), $this->Source->getBranches());
	}

/**
 * testGetDefaultBranch method
 *
 * @return void
 */
	public function testGetDefaultBranch() {
		$this->Source->init();
		$this->Source->Project->id = 1;
		$this->assertEquals('master', $this->Source->getDefaultBranch());
	}

/**
 * testGetRepositoryLocation method
 *
 * @return void
 */
	public function testGetRepositoryLocation() {
		$this->Source->init();
		$this->Source->Project->id = 1;
		$this->assertEquals($this->REPO_BASE.'/private.git/', $this->Source->getRepositoryLocation());
	}


/**
 * testFetchHistory method
 *
 * @return void
 */
	public function testFetchHistory() {
		$this->Source->init();
		$this->Source->Project->id = 1;
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
