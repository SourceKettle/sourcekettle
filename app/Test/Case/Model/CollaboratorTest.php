<?php
App::uses('Collaborator', 'Model');

/**
 * Collaborator Test Case
 *
 */
class CollaboratorTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.collaborator', 'app.project', 'app.user');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Collaborator = ClassRegistry::init('Collaborator');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Collaborator);

		parent::tearDown();
	}

}
