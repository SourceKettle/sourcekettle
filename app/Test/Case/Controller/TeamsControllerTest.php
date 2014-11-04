<?php
App::uses('TeamsController', 'Controller');

/**
 * TeamsController Test Case
 *
 */
class TeamsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.team',
		'app.collaborating_team',
		'app.project',
		'app.repo_type',
		'app.task',
		'app.user',
		'app.collaborator',
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
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
		'app.project_burndown_log',
		'app.project_group',
		'app.group_collaborating_team',
		'app.project_groups_project',
		'app.teams_user'
	);

}
