<?php
App::uses('ProjectGroupMember', 'Model');

/**
 * ProjectGroupMember Test Case
 *
 */
class ProjectGroupMemberTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.project_group_member',
		'app.project_group',
		'app.project_group_permission',
		'app.project',
		'app.repo_type',
		'app.collaborator',
		'app.user',
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
		'app.task',
		'app.task_type',
		'app.task_status',
		'app.task_priority',
		'app.milestone',
		'app.milestone_burndown_log',
		'app.task_comment',
		'app.time',
		'app.task_dependency',
		'app.source',
		'app.blob',
		'app.commit',
		'app.project_history',
		'app.attachment',
		'app.project_burndown_log'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ProjectGroupMember = ClassRegistry::init('ProjectGroupMember');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ProjectGroupMember);

		parent::tearDown();
	}

}
