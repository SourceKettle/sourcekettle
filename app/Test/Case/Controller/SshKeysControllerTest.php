<?php
App::uses('SshKeysController', 'Controller');

/**
 * SshKeysController Test Case
 *
 */
class SshKeysControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.ssh_key',
		'app.user',
		'app.collaborator',
		'app.project',
		'app.repo_type',
		'app.task',
		'app.task_type',
		'app.task_status',
		'app.task_priority',
		'app.milestone',
		'app.task_comment',
		'app.time',
		'app.task_dependency',
		'app.source',
		'app.blob',
		'app.commit',
		'app.project_history',
		'app.attachment',
		'app.email_confirmation_key',
		'app.api_key',
		'app.lost_password_key',
		'app.setting'
	);

/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {
	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {
	}

/**
 * testView method
 *
 * @return void
 */
	public function testView() {
	}

}
