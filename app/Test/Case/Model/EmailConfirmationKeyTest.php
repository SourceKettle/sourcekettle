<?php
App::uses('EmailConfirmationKey', 'Model');

/**
 * EmailConfirmationKey Test Case
 *
 */
class EmailConfirmationKeyTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.email_confirmation_key', 'app.user');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->EmailConfirmationKey = ClassRegistry::init('EmailConfirmationKey');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->EmailConfirmationKey);

		parent::tearDown();
	}

}
