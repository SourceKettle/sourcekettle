<?php
App::uses('RepoType', 'Model');

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
	public $fixtures = array('app.repo_type');

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

}
