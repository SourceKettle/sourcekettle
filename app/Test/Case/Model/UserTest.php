<?php
/**
*
* User Unit Tests for the SourceKettle system
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright     SourceKettle Development Team 2012
* @link          http://github.com/SourceKettle/sourcekettle
* @package       SourceKettle.Test.Case.Model
* @since         SourceKettle v 1.0
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
App::uses('User', 'Model');

class UserTestCase extends CakeTestCase {

    /**
     * fixtures - Populate the database with data of the following models
     */
    public $fixtures = array('app.user',
        'app.collaborator',
        'app.project',
        'app.project_group',
        'app.project_groups_project',
        'app.project_history',
        'app.task',
        'app.task_type',
        'app.task_status',
        'app.task_priority',
        'app.task_dependency',
        'app.task_comment',
        'app.team',
        'app.teams_user',
        'app.time',
        'app.milestone',
		'app.attachment',
        'app.repo_type',
        'app.email_confirmation_key',
        'app.lost_password_key',
        'app.ssh_key',
        'app.api_key',
		'app.milestone_burndown_log',
		'app.project_burndown_log',
    );

    /**
     * setUp function.
     * Run before each unit test.
     * Corrrecly sets up the test environment.
     *
     * @access public
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->User = ClassRegistry::init('User');

        $this->User->recursive = -1;
    }

    /**
     * tearDown function.
     * Tear down all created data after the tests.
     *
     * @access public
     * @return void
     */
    public function tearDown() {
        unset($this->User);

        parent::tearDown();
    }

    /**
     * test User->findByEmail function.
     * Tests that the correct user is returned if an upper case email is specified.
     *
     * @access public
     * @return void
     */
    public function testFindByEmailUpperCase() {
        $userA = $this->User->findById(1);
        $userB = $this->User->findByEmail('MR.SMITH@EXAMPLE.COM');
        $this->assertEquals($userA, $userB, "returned users are not equal");
    }

    /**
     * test User->afterFind function.
     * Tests that the password of a user is returned if this is not an API call.
     *
     * @access public
     * @return void
     */
    public function testAfterFindNotAPI() {
        $userA = $this->User->findById(1);
        $userB = array(
            'User' => array(
                'id' => '1',
                'name' => 'Mr Smith',
                'email' => 'Mr.Smith@example.com',
                'password' => 'Lorem ipsum dolor sit amet',
                'is_admin' => false,
                'is_active' => true,
				'theme' => 'default',
                'created' => '2012-06-01 12:50:08',
                'modified' => '2012-06-01 12:50:08',
				'deleted' => '0',
				'deleted_date' => null,
				'is_internal' => true,
            )
        );
        $this->assertEquals($userA, $userB, "returned users are not equal");
    }

    /**
     * test User->afterFind function.
     * Tests that the password of a user is not returned if this is an API call.
     *
     * @access public
     * @return void
     */
    public function testAfterFindAPI() {
        $this->User->_is_api = true;
        $userA = $this->User->findById(1);
        $userB = array(
            'User' => array(
                'id' => '1',
                'name' => 'Mr Smith',
                'email' => 'Mr.Smith@example.com',
                'is_admin' => false,
                'is_active' => true,
				'theme' => 'default',
                'created' => '2012-06-01 12:50:08',
                'modified' => '2012-06-01 12:50:08',
				'deleted' => '0',
				'deleted_date' => null,
				'is_internal' => true,
            )
        );
        $this->assertEquals($userA, $userB, "returned users are not equal");
    }

	public function testAddUser() {
		$data = array('User' => array(
				'name'      => 'Eve Nchance',
				'email'     => 'eve@example.com',
				'is_admin'  => true,
				'password'  => 'wibbleSOCKS',
				'is_active' => 1,
				'deleted'   => 0,
				'theme'     => 'spacelab',
		));
		$this->User->create();
		$saved = $this->User->save($data);

		// Add the ID and hashed password for comparison
		$data['User']['id'] = $this->User->getLastInsertID();
		$data['User']['password'] = Security::hash($data['User']['password'], null, true);

		// Check the create/modify date are sane, then add to array for comparison
		$this->assertNotNull($saved['User']['created'], "Create date was null");
		$this->assertNotNull($saved['User']['modified'], "Modify date was null");

		$data['User']['created']  = $saved['User']['created'];
		$data['User']['modified'] = $saved['User']['modified'];

		$this->assertEqual($saved, $data, "Save failed");
	}

	public function testChangeName() {
		$this->User->id = 1;
		$user = $this->User->read();
		$this->assertEquals($user['User']['name'], "Mr Smith", "Incorrect name found before change");

		$this->User->saveField('name', 'Dr Festus Obi');

        $user = $this->User->findById(1);
		$this->assertEquals($user['User']['name'], "Dr Festus Obi", "Incorrect name found after change");
	}

	public function testChangeEmail() {
		$this->User->id = 1;
		$user = $this->User->read();
		$this->assertEquals($user['User']['email'], "Mr.Smith@example.com", "Incorrect email found before change");

		$this->User->saveField('email', 'festus@spam.example.com');

        $user = $this->User->findById(1);
		$this->assertEquals($user['User']['email'], "festus@spam.example.com", "Incorrect email found after change");
	}

	public function testFailToChangeEmailExternal() {
		// Try a non-sourcekettle-managed user account, should fail
		$this->User->id = 23;
		$user = $this->User->read();
		$this->assertEquals($user['User']['email'], "ldap-user@example.com", "Incorrect email found before change");

		$this->User->saveField('email', 'blah@example.com');

        	$user = $this->User->findById(6);
		$this->assertEquals($user['User']['email'], "ldap-user@example.com", "Incorrect email found after change");
		
	}

	public function testFailToChangePasswordExternal() {
		$this->User->id = 23;
		$user = $this->User->read();
		$this->assertEquals($user['User']['password'], "", "Incorrect password found before change");

		$this->User->saveField('password', 'testTESTtest');

        	$user = $this->User->findById(23);
		$this->assertEquals($user['User']['password'], "", "Incorrect password found after change");
	}


	public function testChangeTheme() {
		$this->User->id = 1;
		$user = $this->User->read();
		$this->assertEquals($user['User']['theme'], "default", "Incorrect theme found before change");

		$this->User->saveField('theme', 'spruce');

        $user = $this->User->findById(1);
		$this->assertEquals($user['User']['theme'], "spruce", "Incorrect theme found after change");
		
	}

	public function testPromoteToAdmin() {
		$this->User->id = 1;
		$user = $this->User->read();
		$this->assertEquals($user['User']['is_admin'], false, "Incorrect admin status found before change");

		$this->User->saveField('is_admin', true);

        $user = $this->User->findById(1);
		$this->assertEquals($user['User']['is_admin'], true, "Incorrect admin status found after change");
		
	}

	public function testDemoteFromAdmin() {
		$this->User->id = 5;
		$user = $this->User->read();
		$this->assertEquals($user['User']['is_admin'], true, "Incorrect admin status found before change");

		$this->User->saveField('is_admin', false);

        $user = $this->User->findById(1);
		$this->assertEquals($user['User']['is_admin'], false, "Incorrect admin status found after change");
	}

	public function testActivate() {
		$this->User->id = 6;
		$user = $this->User->read();
		$this->assertEquals($user['User']['is_active'], false, "Incorrect is_active status found before change");

		$this->User->saveField('is_active', true);

        $user = $this->User->findById(1);
		$this->assertEquals($user['User']['is_active'], true, "Incorrect is_active status found after change");
		
	}

	public function testDeactivate() {
		$this->User->id = 1;
		$user = $this->User->read();
		$this->assertEquals($user['User']['is_active'], true, "Incorrect is_active status found before change");

		$this->User->saveField('is_active', false);

        $user = $this->User->findById(1);
		$this->assertEquals($user['User']['is_active'], false, "Incorrect is_active status found after change");
	}

	public function testDelete() {
		$this->User->id = 7;
		$ok = $this->User->delete();
		$this->assertTrue($ok, 'Failed to delete user');

		$this->User->id = 7;
		$user = $this->User->read();
		$this->assertEquals($user, array(), "User data retrieved after delete");
	}

	public function testDeleteApi() {
		$this->User->id = 7;
		$this->User->_is_api = true;
		$ok = $this->User->delete();
		$this->assertTrue($ok, 'Failed to delete user');

		$this->User->id = 7;
		$user = $this->User->read();
		$this->assertEquals($user, array(), "User data retrieved after delete");
	}

	public function testFailToDeleteExternal() {
		$this->User->id = 23;
		$ok = $this->User->delete();
		$this->assertFalse($ok, 'Erroneously deleted external user account');
	}

	public function testFailToDeleteOnlyAdmin() {
		$this->User->id = 8;
		$ok = $this->User->delete();
		$this->assertFalse($ok, 'Erroneously deleted the only admin of a project');
	}

	public function testGetPendingApprovals() {
		$pending = $this->User->getPendingApprovals();
		$this->assertEquals(array(
			array(
				'EmailConfirmationKey' => array(
					'key' => 'ba6f23c5ce588f16647fe32603fb1593'
				),
				'User' => array(
					'id' => '11',
					'name' => 'A non-confirmed user',
					'email' => 'non-confirmed@example.com',
					'is_active' => false
				)
			)
		), $pending);
	}

	public function testGetPendingAccount() {
		$pending = $this->User->getPendingAccount('ba6f23c5ce588f16647fe32603fb1593');
		$this->assertEquals(array(
			'EmailConfirmationKey' => array(
				'key' => 'ba6f23c5ce588f16647fe32603fb1593'
			),
			'User' => array(
				'id' => '11',
				'name' => 'A non-confirmed user',
				'email' => 'non-confirmed@example.com',
				'is_active' => false
			)
		), $pending);
	}

	public function testApprovePendingAccountFailures() {
		$ok = $this->User->approvePendingAccount(null);
		$this->assertFalse($ok);
		$ok = $this->User->approvePendingAccount(array());
		$this->assertFalse($ok);
		$ok = $this->User->approvePendingAccount(array('Foo'));
		$this->assertFalse($ok);
		$ok = $this->User->approvePendingAccount(array('User' => array()));
		$this->assertFalse($ok);
		$ok = $this->User->approvePendingAccount(array('User' => array('id' => 11, 'is_active' => true)));
		$this->assertFalse($ok);

		$this->User = $this->getMockForModel('User', array('save'));
		$this->User
			->expects($this->once())
			->method('save')
			->will($this->returnValue(false));

		$ok = $this->User->approvePendingAccount(array('User' => array('id' => 11, 'is_active' => false)));
		$this->assertFalse($ok);
	}

	public function testApprovePendingAccountSuccess() {
		$pending = $this->User->getPendingAccount('ba6f23c5ce588f16647fe32603fb1593');
		$ok = $this->User->approvePendingAccount($pending);
		$this->assertTrue($ok);

		$deadKey = $this->User->EmailConfirmationKey->findByKey('ba6f23c5ce588f16647fe32603fb1593');
		$this->assertEquals(array(), $deadKey);
	}

	private function crunchTaskList($tasks) {
		return array_map(function($a){return $a['Task']['id'];}, $tasks);
	}

	public function testTasksOfStatusForUser() {
		$open = $this->crunchTaskList($this->User->tasksOfStatusForUser(2, 'open'));
		$inProgress = $this->crunchTaskList($this->User->tasksOfStatusForUser(2, 'in progress'));
		$resolved = $this->crunchTaskList($this->User->tasksOfStatusForUser(2, 'resolved'));
		$closed = $this->crunchTaskList($this->User->tasksOfStatusForUser(2, 'closed'));
		$dropped = $this->crunchTaskList($this->User->tasksOfStatusForUser(2, 'dropped'));
		$done = $this->crunchTaskList($this->User->tasksOfStatusForUser(2, array('resolved', 'closed')));

		$this->assertEquals(array(), $open);
		$this->assertEquals(array(4, 10, 11, 12, 13), $inProgress);
		$this->assertEquals(array(1), $resolved);
		$this->assertEquals(array(), $closed);
		$this->assertEquals(array(), $dropped);
		$this->assertEquals(array(1), $done);
	}
}
