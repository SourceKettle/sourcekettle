<?php
App::uses('Story', 'Model');

/**
 * Story Test Case
 *
 */
class StoryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.story',
		'app.creator',
		'app.task',
		'app.project',
		'app.repo_type',
		'app.collaborator',
		'app.user',
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
		'app.milestone',
		'app.milestone_burndown_log',
		'app.source',
		'app.blob',
		'app.commit',
		'app.time',
		'app.project_history',
		'app.attachment',
		'app.project_burndown_log',
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
		$this->Story = ClassRegistry::init('Story');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Story);

		parent::tearDown();
	}

}
