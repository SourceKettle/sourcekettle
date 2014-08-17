<?php
App::uses('DashboardController', 'Controller');

/**
 * DashboardController Test Case
 *
 */
class DashboardControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
		'app.task_comment',
		'app.time',
		'app.task_dependency',
		'app.source',
		'app.project_history',
		'app.attachment'
	);

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
	}

/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndex() {
	}

}
