<?php
App::uses('Source', 'Model');

/**
 * Source Test Case
 *
 */
class SourceTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.source');

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

}
