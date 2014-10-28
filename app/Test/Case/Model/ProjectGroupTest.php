<?php
App::uses('ProjectGroup', 'Model');

/**
 * ProjectGroup Test Case
 *
 */
class ProjectGroupTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.project_group',
		'app.project_group_member',
		'app.project_group_permission'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ProjectGroup = ClassRegistry::init('ProjectGroup');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ProjectGroup);

		parent::tearDown();
	}

}
