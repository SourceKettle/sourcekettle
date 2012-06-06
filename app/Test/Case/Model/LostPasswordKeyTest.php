<?php
App::uses('LostPasswordKey', 'Model');

/**
 * LostPasswordKey Test Case
 *
 */
class LostPasswordKeyTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.lost_password_key', 'app.user', 'app.collaborator', 'app.project', 'app.repo_type', 'app.email_confirmation_key', 'app.ssh_key');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->LostPasswordKey = ClassRegistry::init('LostPasswordKey');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->LostPasswordKey);

		parent::tearDown();
	}

}
