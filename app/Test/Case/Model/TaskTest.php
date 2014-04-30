<?php
App::uses('Task', 'Model');

/**
 * Task Test Case
 *
 */
class TaskTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.task',
		'app.project',
		//'app.repo_type',
		//'app.collaborator',
		'app.user',
		//'app.email_confirmation_key',
		//'app.ssh_key',
		//'app.api_key',
		//'app.lost_password_key',
		'app.milestone',
		//'app.source',
		//'app.blob',
		//'app.commit',
		'app.time',
		//'app.project_history',
		//'app.attachment',
		'app.task_type',
		'app.task_status',
		'app.task_priority',
		'app.task_comment',
		'app.task_dependency'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Task = ClassRegistry::init('Task');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Task);

		parent::tearDown();
	}

/**
 * testIsAssignee method
 *
 * @return void
 */
	public function testIsAssignee() {
	}

/**
 * testIsOpen method
 *
 * @return void
 */
	public function testIsOpen() {
	}

/**
 * testIsInProgress method
 *
 * @return void
 */
	public function testIsInProgress() {
	}

/**
 * testFetchHistory method
 *
 * @return void
 */
	/*public function testFetchHistory() {
	}*/

/**
 * testGetTitleForHistory method
 *
 * @return void
 */
	public function testGetTitleForHistory() {
		$this->Task->id = 1;
		$this->Task->read();
		$this->assertEquals('#1', $this->Task->getTitleForHistory());
		$this->assertEquals('#2', $this->Task->getTitleForHistory(2));
	}

/**
 * testFetchLoggableTasks method
 *
 * @return void
 */
	public function testFetchLoggableTasks() {
	}

}
