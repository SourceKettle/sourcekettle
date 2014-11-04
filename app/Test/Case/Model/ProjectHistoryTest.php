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
		'app.project',
		'app.project_history',
		'app.repo_type',
		'app.collaborator',
		'app.user',
		'app.task',
		'app.task_type',
		'app.task_dependency',
		'app.task_comment',
		'app.task_status',
		'app.task_priority',
		'app.time',
		'app.attachment',
		'app.source',
		'app.milestone',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ProjectHistory = ClassRegistry::init('ProjectHistory');
		$this->ProjectHistory->Project->id = 2;
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
	public function testLogCCreate() {
		$saved = $this->ProjectHistory->logC(
			'collaborator',
			'5',
			'Mr Smith',
			'+',
			null,
			null,
			'2',
			'Mrs Smith'
		);

		$this->assertRegExp("/^\d+$/", $saved['ProjectHistory']['id'], "Failed to save with a numeric ID");
		unset($saved['ProjectHistory']['id']);

		$this->assertEquals($saved['ProjectHistory']['modified'], $saved['ProjectHistory']['created'], "Create/modify timestamps do not match");
		unset($saved['ProjectHistory']['modified']);
		unset($saved['ProjectHistory']['created']);

		$this->assertEquals($saved, array('ProjectHistory' => array(
			'project_id' => 2,
	        'model' => 'collaborator',
	        'row_id' => '5',
	        'row_title' => 'Mr Smith',
	        'row_field' => '+',
	        'row_field_old' => null,
	        'row_field_new' => null,
	        'user_id' => '2',
	        'user_name' => 'Mrs Smith',
		)), "Failed to save");

	}

	public function testLogCDelete() {
		$saved = $this->ProjectHistory->logC(
			'collaborator',
			'5',
			'Mr Smith',
			'-',
			null,
			null,
			'2',
			'Mrs Smith'
		);

		$this->assertRegExp("/^\d+$/", $saved['ProjectHistory']['id'], "Failed to save with a numeric ID");
		unset($saved['ProjectHistory']['id']);

		$this->assertEquals($saved['ProjectHistory']['modified'], $saved['ProjectHistory']['created'], "Create/modify timestamps do not match");
		unset($saved['ProjectHistory']['modified']);
		unset($saved['ProjectHistory']['created']);

		$this->assertEquals($saved, array('ProjectHistory' => array(
			'project_id' => 2,
	        'model' => 'collaborator',
	        'row_id' => '5',
	        'row_title' => 'Mr Smith',
	        'row_field' => '-',
	        'row_field_old' => null,
	        'row_field_new' => null,
	        'user_id' => '2',
	        'user_name' => 'Mrs Smith',
		)), "Failed to save");

	}

	public function testLogCUpdate() {
		$saved = $this->ProjectHistory->logC(
			'collaborator',
			'5',
			'Mr Smith',
			'access_level',
			'0',
			'1',
			'2',
			'Mrs Smith'
		);

		$this->assertRegExp("/^\d+$/", $saved['ProjectHistory']['id'], "Failed to save with a numeric ID");
		unset($saved['ProjectHistory']['id']);

		$this->assertEquals($saved['ProjectHistory']['modified'], $saved['ProjectHistory']['created'], "Create/modify timestamps do not match");
		unset($saved['ProjectHistory']['modified']);
		unset($saved['ProjectHistory']['created']);

		$this->assertEquals($saved, array('ProjectHistory' => array(
			'project_id' => 2,
	        'model' => 'collaborator',
	        'row_id' => '5',
	        'row_title' => 'Mr Smith',
	        'row_field' => 'access_level',
	        'row_field_old' => '0',
	        'row_field_new' => '1',
	        'user_id' => '2',
	        'user_name' => 'Mrs Smith',
		)), "Failed to save");

	}


/**
 * testFetchHistory method
 *
 * @return void
 */
	public function testFetchHistory() {

		$history = $this->ProjectHistory->fetchHistory(1, 10, 0, 0, 'collaborator');
		$this->assertEquals(array(
			array(
				'modified' => '2014-07-23 15:02:12',
				'Type' => 'Collaborator',
				'Project' => array(
					'id' => '1',
					'name' => 'private'
				),
				'Actioner' => array(
					'id' => '1',
					'name' => 'Mr Smith',
					'email' => 'Mr.Smith@example.com',
					'exists' => true
				),
				'Subject' => array(
					'id' => '2',
					'title' => 'Mr Admin',
					'exists' => true
				),
				'Change' => array(
					'field' => 'access_level',
					'field_old' => '2',
					'field_new' => '1'
				),
				'url' => array(
					'api' => false,
					'admin' => false,
					'controller' => 'users',
					'action' => 'view',
					(int) 0 => '5'
				)
			),

			array(
				'modified' => '2014-07-23 15:01:12',
				'Type' => 'Collaborator',
				'Project' => array(
					'id' => '1',
					'name' => 'private'
				),
				'Actioner' => array(
					'id' => '1',
					'name' => 'Mr Smith',
					'email' => 'Mr.Smith@example.com',
					'exists' => true
				),
				'Subject' => array(
					'id' => '2',
					'title' => 'Mr Admin',
					'exists' => true
				),
				'Change' => array(
					'field' => 'access_level',
					'field_old' => '1',
					'field_new' => '2'
				),
				'url' => array(
					'api' => false,
					'admin' => false,
					'controller' => 'users',
					'action' => 'view',
					(int) 0 => '5'
				)
			),
		), $history, "Incorrect history data returned");


		// NB task ID should be the public ID, 1, not the internal primary key, 11
		$history = $this->ProjectHistory->fetchHistory(1, 10, 0, 0, 'task');

		$this->assertEquals(array(
			array(
				'modified' => '2014-07-23 15:10:34',
				'Type' => 'Task',
				'Project' => array(
					'id' => '1',
					'name' => 'private'
				),
				'Actioner' => array(
					'id' => '1',
					'name' => 'Mr Smith',
					'email' => 'Mr.Smith@example.com',
					'exists' => true
				),
				'Subject' => array(
					'id' => '1',
					'title' => '#1',
					'exists' => true
				),
				'Change' => array(
					'field' => 'task_status_id',
					'field_old' => '1',
					'field_new' => '2'
				),
			),
			array(
				'modified' => '2014-07-23 15:01:12',
				'Type' => 'Task',
				'Project' => array(
					'id' => '1',
					'name' => 'private'
				),
				'Actioner' => array(
					'id' => '1',
					'name' => 'Mr Smith',
					'email' => 'Mr.Smith@example.com',
					'exists' => true
				),
				'Subject' => array(
					'id' => '1',
					'title' => '#1',
					'exists' => true
				),
				'Change' => array(
					'field' => '+',
					'field_old' => '',
					'field_new' => ''
				),
			),
		
		), $history, "Incorrect history data returned");

		$history = $this->ProjectHistory->fetchHistory(3, 10, 0, 0, 'time');
		$this->assertEquals(array(
			array(
				'modified' => '2014-07-23 15:01:12',
				'Type' => 'Time',
				'Project' => array(
					'id' => '3',
					'name' => 'personal'
				),
				'Actioner' => array(
					'id' => '2',
					'name' => 'Mrs Smith',
					'email' => 'mrs.smith@example.com',
					'exists' => true
				),
				'Subject' => array(
					'id' => '4',
					'title' => '0h 19m',
					'exists' => true
				),
				'Change' => array(
					'field' => '+',
					'field_old' => null,
					'field_new' => null
				)
			),
			array(
				'modified' => '2014-07-23 15:01:12',
				'Type' => 'Time',
				'Project' => array(
					'id' => '3',
					'name' => 'personal'
				),
				'Actioner' => array(
					'id' => '2',
					'name' => 'Mrs Smith',
					'email' => 'mrs.smith@example.com',
					'exists' => true
				),
				'Subject' => array(
					'id' => '4',
					'title' => 'logged time',
					'exists' => true
				),
				'Change' => array(
					'field' => 'description',
					'field_old' => 'foo',
					'field_new' => 'bar'
				)
			)
		), $history, "Incorrect history data returned");

		$this->ProjectHistory->Project->id = null;
		$history = $this->ProjectHistory->fetchHistory(null, 10, 0, 2, 'milestone');

		$this->assertEquals(array(array(
			'modified' => '2014-07-23 15:01:12',
			'Type' => 'Milestone',
			'Project' => array(
				'id' => '2',
				'name' => 'public'
			),
			'Actioner' => array(
				'id' => '2',
				'name' => 'Mrs Smith',
				'email' => 'mrs.smith@example.com',
				'exists' => true
			),
			'Subject' => array(
				'id' => '3',
				'title' => 'Longer <i>subject</i>',
				'exists' => true
			),
			'Change' => array(
				'field' => 'is_open',
				'field_old' => '1',
				'field_new' => '0'
			)
		)), $history, "Incorrect history data returned");
	}

}
