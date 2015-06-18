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

	public function testFindByPublicId() {
		$story = $this->Story->findByProjectIdAndPublicId(2, 3);
		$this->assertEquals('3', $story['Story']['id']);
		$this->assertEquals('3', $story['Story']['public_id']);

		$story = $this->Story->findByProjectIdAndPublicId(3, 1);
		$this->assertEquals('4', $story['Story']['id']);
		$this->assertEquals('1', $story['Story']['public_id']);
	}

	public function testListStoryOptions() {
		$this->Story->Project->id = 3;
		$options = $this->Story->listStoryOptions();
		$this->assertEquals(array(
			0 => __('No assigned story'),
			1 => 'First story',
			2 => 'Last story',
		), $options);
	}

}
