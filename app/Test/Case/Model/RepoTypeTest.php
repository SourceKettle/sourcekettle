<?php
App::uses('RepoType', 'Model');

/**
 * RepoType Test Case
 *
 */
class RepoTypeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->RepoType = ClassRegistry::init('RepoType');
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

	public function testNameToId() {
		$types = array(
			$this->RepoType->nameToId('fail'),
			$this->RepoType->nameToId('none'),
			$this->RepoType->nameToId('git'),
			$this->RepoType->nameToId('svn')
		);

		$this->assertEquals(array(0, 1, 2, 3), $types, "Incorrect ID(s) returned");
	}

	public function testIdToName() {
		$types = array(
			$this->RepoType->idToName(0),
			$this->RepoType->idToName(1),
			$this->RepoType->idToName(2),
			$this->RepoType->idToName(3)
		);

		$this->assertEquals(array(null, 'none', 'git', 'svn'), $types, "Incorrect ID(s) returned");
	}

}
