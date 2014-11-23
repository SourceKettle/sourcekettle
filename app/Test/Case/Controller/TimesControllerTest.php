<?php
App::uses('TimesController', 'Controller');
require_once(__DIR__ . DS . 'AppControllerTest.php');

/**
 * TimesController Test Case
 *
 */
class TimesControllerTestCase extends AppControllerTest {

    public $fixtures = array(
		'core.cake_session',
		'app.setting',
		'app.user_setting',
		'app.project_setting',
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
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
		'app.milestone_burndown_log',
		'app.project_burndown_log',
		'app.collaborating_team',
		'app.group_collaborating_team',
		'app.team',
		'app.teams_user',
		'app.project_group',
		'app.project_groups_project',
	);

	public function setUp() {
		parent::setUp("Times");
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Times);

		parent::tearDown();
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndexNotLoggedIn() {
		$this->testAction('/project/private/time', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
    }

	public function testIndexLoggedIn() {
		$this->_fakeLogin(1);
		$this->testAction('/project/private/time', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();

		// We should be redirected to the times/users page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/project/private/time/users', true), $this->headers['Location']);

	}

	public function testUsersNotLoggedIn() {
		$this->testAction('/project/private/time/users', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
    }

	public function testUsersLoggedIn() {
		$this->_fakeLogin(1);
		$this->testAction('/project/private/time/users', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		
		$this->assertEqual(array(
			array(
				'User' => array(
					'id' => '1',
					'name' => 'Mr Smith',
					'email' => 'Mr.Smith@example.com'
				),
				0 => array(
					'total_mins' => '990'
				),
				'Time' => array(
					'time' => array(
						'w' => 0,
						'd' => 0,
						'h' => 16,
						'm' => 30,
						't' => 990,
						's' => '16h 30m'
					)
				)
			),
			array(
				'User' => array(
					'id' => '2',
					'name' => 'Mrs Smith',
					'email' => 'mrs.smith@example.com'
				),
				0 => array(
					'total_mins' => '14'
				),
				'Time' => array(
					'time' => array(
						'w' => 0,
						'd' => 0,
						'h' => 0,
						'm' => 14,
						't' => 14,
						's' => '0h 14m'
					)
				)
			),
			array(
				'User' => array(
					'id' => '3',
					'name' => 'Mrs Guest',
					'email' => 'mrs.guest@example.com'
				),
				0 => array(
					'total_mins' => '19'
				),
				'Time' => array(
					'time' => array(
						'w' => 0,
						'd' => 0,
						'h' => 0,
						'm' => 19,
						't' => 19,
						's' => '0h 19m'
					)
				)
			)
		), $this->vars['users']);

		$this->assertEqual(array(
			'w' => 0,
			'd' => 0,
			'h' => 17,
			'm' => 3,
			't' => 1023,
			's' => '17h 3m'
		), $this->vars['total_time']);

	}

	public function testHistoryNotLoggedIn() {
		$this->testAction('/project/private/time/history', array('method' => 'get', 'return' => 'vars'));
		$this->assertNotAuthorized();
    }

	public function testHistoryLoggedIn() {
		$this->_fakeLogin(1);
		$this->testAction('/project/private/time/history/2012/46', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();

		$this->assertEqual(array(
			1 => 900,
			2 => 14,
			3 => 0,
			4 => 0,
			5 => 0,
			6 => 0,
			7 => 0
		), $this->vars['weekTimes']['totals']);

		$this->assertEqual(array(
			2 => array(
				'Task' => array(
					'id' => '2',
					'subject' => 'Open Minor Task 2 for milestone 1',
					'public_id' => '2',
				),
				'users' => array(
					1 => array(
						'User' => array(
							'id' => '1',
							'name' => 'Mr Smith',
							'email' => 'Mr.Smith@example.com'
						),
						'times_by_day' => array(
							1 => array(
								0 => array(
									'Time' => array(
										'id' => '2',
										'date' => '2012-11-12',
										'description' => 'A description of the second <b>time</b>.',
										'mins' => '900',
										'minutes' => array(
											'w' => 0,
											'd' => 0,
											'h' => 15,
											'm' => 0,
											't' => 900,
											's' => '15h 0m'
										)
									)
								)
							)
						)
					),
					2 => array(
						'User' => array(
							'id' => '2',
							'name' => 'Mrs Smith',
							'email' => 'mrs.smith@example.com'
						),
						'times_by_day' => array(
							2 => array(
								0 => array(
									'Time' => array(
										'id' => '3',
										'date' => '2012-11-13',
										'description' => 'A description of the third <b>time</b>.',
										'mins' => '14',
										'minutes' => array(
											'w' => 0,
											'd' => 0,
											'h' => 0,
											'm' => 14,
											't' => 14,
											's' => '0h 14m'
										)
									)
								)
							)
						)
					)
				)
			)
		), $this->vars['weekTimes']['tasks']);
	

		// Check that the dates are correct by formatting them consistently
		$this->assertEqual(array(
			1 => '2012-11-12T00:00:00+0000',
			2 => '2012-11-13T00:00:00+0000',
			3 => '2012-11-14T00:00:00+0000',
			4 => '2012-11-15T00:00:00+0000',
			5 => '2012-11-16T00:00:00+0000',
			6 => '2012-11-17T00:00:00+0000',
			7 => '2012-11-18T00:00:00+0000',
		), array_map(function($date) {return $date->format(DateTime::ISO8601);}, $this->vars['weekTimes']['dates']));

		$this->assertEqual(array(
			0 => 'No Assigned Task',
			'Your Tasks' => array(),
			'Others Tasks' => array(
				1 => 'Task 1 for private project',
				2 => 'Task 2 for private project',
			)
		), $this->vars['tasks']);
	}


/**
 * testView method
 *
 * @return void
 */
	public function testView() {
	}
/**
 * testAdd method
 *
 * @return void
 */
	public function testAddTimeNotLoggedIn() {
		$ret = $this->testAction('/project/public/time/add', array('method' => 'get', 'return' => 'view'));
		$this->assertNotAuthorized();
	}

	public function testAddTimeForm() {
		$this->_fakeLogin(2);
		$this->testAction('/project/public/time/add', array('return' => 'view', 'method' => 'get'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/project/public/time/add').'"|', $this->view);
	}

	public function testAddTime() {
		$this->_fakeLogin(2);
		$postData = array(
			'Time' => array(
				'project_id' => '2',
				'task_id' => '1',
				'mins' => '9',
		   	 	'description' => 'Did a small amount of work',
				'date' => '2014-08-11',
			)
		);

		$this->testAction('/project/public/time/add', array('return' => 'view', 'method' => 'post', 'data' => $postData));
		$this->assertAuthorized();

		// We should be redirected to the time index page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/project/public/tasks/view/1', true), $this->headers['Location']);

		// Check that the time was saved OK
		$id = $this->controller->Time->getLastInsertID();
		$time = $this->controller->Time->find('first', array('conditions' => array('id' => $id), 'fields' => array('project_id', 'task_id', 'mins', 'description', 'date'), 'recursive' => -1));

		$this->assertEquals(array(
			'w' => 0,
			'd' => 0,
			'h' => 0,
			'm' => 9,
			't' => 9,
			's' => '0h 9m',
		), $time['Time']['minutes']);
		unset($time['Time']['minutes']);
		$this->assertEquals($postData['Time'], $time['Time']);
	}
/**
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {
	}
/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {
	}

}
