<?php
App::uses('TaskComment', 'Model');

/**
 * TaskComment Test Case
 *
 */
class TaskCommentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.task_comment',
		'app.task',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TaskComment = ClassRegistry::init('TaskComment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TaskComment);

		parent::tearDown();
	}

/**
 * testOpen method
 *
 * @return void
 */
	public function testOpen() {
	}

}
