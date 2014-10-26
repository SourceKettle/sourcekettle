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
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
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
 * testGetType method
 *
 * @return void
 */
	public function testGetType() {
	}

/**
 * testGetBranches method
 *
 * @return void
 */
	public function testGetBranches() {
	}

/**
 * testGetDefaultBranch method
 *
 * @return void
 */
	public function testGetDefaultBranch() {
	}

/**
 * testGetRepositoryLocation method
 *
 * @return void
 */
	public function testGetRepositoryLocation() {
	}

/**
 * testInit method
 *
 * @return void
 */
	public function testInit() {
	}

/**
 * testFetchHistory method
 *
 * @return void
 */
	public function testFetchHistory() {
	}

}
