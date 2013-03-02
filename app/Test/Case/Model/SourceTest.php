<?php
App::uses('Source', 'Model');
App::uses('RepoTypes', 'GitCake.Model');

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
	public $fixtures = array('app.project');

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

	public function testGetType() {
		$this->Source->Project->id = 3;
		$this->assertEquals(RepoTypes::GIT, $this->Source->getType());
	}

}
