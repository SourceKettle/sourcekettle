<?php
App::uses('ProjectHistory', 'Model');

/**
 * ProjectHistory Test Case
 *
 */
class ProjectHistoryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.project_history',
		'app.project',
		'app.collaborator',
		'app.user',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ProjectHistory = ClassRegistry::init('ProjectHistory');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ProjectHistory);

		parent::tearDown();
	}

/**
 * testLogC method
 *
 * @return void
 */
	public function testLogC() {
	}

/**
 * testFetchHistory method
 *
 * @return void
 */
	public function testFetchHistory() {
	}

}
