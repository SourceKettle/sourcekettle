<?php
App::uses('RepoType', 'Model');
App::uses('ArraySource', 'Model/Datasource');
App::uses('ConnectionManager', 'Model');

// Add new db config and wrap in test class
ConnectionManager::create('test_array', array('datasource' => 'ArraySource'));

/**
 * RepoType Test Case
 *
 */
class RepoTypeTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.project');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->RepoType = ClassRegistry::init('RepoType');
		$this->RepoType->setSource('test_array');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->RepoType);

		parent::tearDown();
	}

	public function testFindAll() {
		$expected = array(
			array('RepoType' => array('id' => 1, 'name' => 'n/a')),
			array('RepoType' => array('id' => 2, 'name' => 'Git')),
			array('RepoType' => array('id' => 3, 'name' => 'Subversion')),
		);
		$this->assertEquals($expected, $this->RepoType->find('all'));
	}

	public function testFindById() {
		$expected = array('RepoType' => array('id' => 2, 'name' => 'Git'));
		$actualA = $this->RepoType->find('first', array('conditions' => array('id' => 2)));
		$this->assertEquals($expected, $actualA);
	}

	public function testGitPassProjectId() {
		$this->assertTrue($this->RepoType->isGit(3));
	}

	public function testGitAlreadySetProjectId() {
		$this->RepoType->Project->id = 3;
		$this->assertTrue($this->RepoType->isGit());
	}

	public function testGitNoProjectId() {
		$this->expectError('NotFoundException', 'Could not find project');

		$this->RepoType->Project->id = null;
		$this->RepoType->isGit();
	}

	public function testGitNotGit() {
		$this->assertFalse($this->RepoType->isGit(4));
	}

	public function testSvnPassProjectId() {
		$this->assertTrue($this->RepoType->isSVN(4));
	}

	public function testSvnAlreadySetProjectId() {
		$this->RepoType->Project->id = 4;
		$this->assertTrue($this->RepoType->isSVN());
	}

	public function testSvnNoProjectId() {
		$this->expectError('NotFoundException', 'Could not find project');

		$this->RepoType->Project->id = null;
		$this->RepoType->isSVN();
	}

	public function testSvnNotSvn() {
		$this->assertFalse($this->RepoType->isSvn(3));
	}

	public function testFetchNoneDetails() {
		$expected = array('RepoType' => array('id' => 1, 'name' => 'n/a'));
		$this->assertEquals($expected, $this->RepoType->getTypeDetails(1));
	}

	public function testFetchGitDetails() {
		$expected = array('RepoType' => array('id' => 2, 'name' => 'Git'));
		$this->assertEquals($expected, $this->RepoType->getTypeDetails(3));
	}

	public function testFetchSvnDetails() {
		$expected = array('RepoType' => array('id' => 3, 'name' => 'Subversion'));
		$this->assertEquals($expected, $this->RepoType->getTypeDetails(4));
	}

	public function testFetchUnsupportedDetails() {
		$this->expectError('UnsupportedRepositoryType');
		$this->RepoType->getTypeDetails(5);
	}
}
