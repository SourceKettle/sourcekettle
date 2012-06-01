<?php
App::uses('SshKey', 'Model');

/**
 * SshKey Test Case
 *
 */
class SshKeyTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.ssh_key', 'app.user');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SshKey = ClassRegistry::init('SshKey');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SshKey);

		parent::tearDown();
	}

}
