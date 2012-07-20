<?php
App::uses('Time', 'Model');

/**
 * Time Test Case
 *
 */
class TimeTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.time', 'app.project', 'app.repo_type', 'app.collaborator', 'app.user', 'app.email_confirmation_key', 'app.ssh_key', 'app.api_key', 'app.lost_password_key', 'app.source', 'plugin.git_cake.git_cake', 'plugin.s_v_n_cake.s_v_n_cake');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Time = ClassRegistry::init('Time');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Time);

		parent::tearDown();
	}

}
