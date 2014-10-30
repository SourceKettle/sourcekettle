<?php
/**
*
* Project Unit Tests for the SourceKettle system
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
App::uses('Project', 'Model');

class ProjectTestCase extends CakeTestCase {

    /**
     * fixtures - Populate the database with data of the following models
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
		'app.setting',
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
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
        $this->Project = ClassRegistry::init('Project');
    }

    /**
     * tearDown function.
     * Tear down all created data after the tests.
     *
     * @access public
     * @return void
     */
    public function tearDown() {
        unset($this->Project);
        parent::tearDown();
    }

    /**
     * test Project->getProject function.
     * Tests that requesting a null project will return a null project.
     *
     * @access public
     * @return void
     */
    public function testGetProjectNull() {
        $project = $this->Project->getProject(null);
        $this->assertTrue($project == null, "Project is not null");
    }

    /**
     * test Project->getProject function.
     * Tests that requesting a project that does not exist throws a
     * NotFoundException.
     *
     * @access public
     * @return void
     */
    public function testGetProjectNonExistant() {
        try {
            $project = $this->Project->getProject('nonexistant');
            $this->assertTrue(false, "getProject did not throw an exception");
        } catch (NotFoundException $e) {
            $this->assertTrue(true, "getProject threw exception {$e->getMessage()}");
        } catch (Exception $e) {
            $this->assertTrue(false, "getProject threw wrong exception {$e->getMessage()}");
        }
    }

    /**
     * test Project->getProject function.
     * Tests that a project is correctly returned when a name
     * reference is used.
     *
     * @access public
     * @return void
     */
    public function testGetProjectByName() {
        $project = $this->Project->getProject('private');
        $this->assertTrue($project != null, "Project is null");
        $this->assertEquals('private', $project['Project']['name'], "wrong project returned");
    }

    /**
     * test Project->getProject function..
     * Tests that an invalid user is denied access to a project
     *
     * @access public
     * @return void
     */
    public function testGetProjectReadInvalidUser() {
        try {
            $project = $this->Project->getProject(1);
            $this->assertTrue(false, "getProject did not throw an exception");
        } catch (Exception $e) {
            $this->assertTrue(true, "getProject threw exception {$e->getMessage()}");
        }
    }

    /**
     * test Project->hasRead function.
     *
     * @access public
     * @return void
     */
    public function testHasReadInvalidUser() {
        $hasRead = $this->Project->hasRead(null, 1);
        $this->assertFalse($hasRead, "user has incorrect privileges");

		$this->Project->id = 1;
        $hasRead = $this->Project->hasRead();
        $this->assertFalse($hasRead, "user has incorrect privileges");
    }

    /**
     * test Project->hasRead function.
     *
     * @access public
     * @return void
     */
    public function testHasReadNotCollaboratorPrivate() {
        $hasRead = $this->Project->hasRead(2, 3);
        $this->assertFalse($hasRead, "user has incorrect privileges");

		$this->Project->id = 3;
        $hasRead = $this->Project->hasRead(2);
        $this->assertFalse($hasRead, "user has incorrect privileges");
    }

    /**
     * test Project->hasRead function.
     *
     * @access public
     * @return void
     */
    public function testHasReadNotCollaboratorPublic() {
        $hasRead = $this->Project->hasRead(2, 4);
        $this->assertTrue($hasRead, "user has incorrect privileges");

		$this->Project->id = 4;
        $hasRead = $this->Project->hasRead(2);
        $this->assertTrue($hasRead, "user has incorrect privileges");
    }

    /**
     * test Project->hasRead function.
     * Tests that a guest does not have write privileges.
     *
     * @access public
     * @return void
     */
    public function testHasReadGuest() {
		$this->Project->id = 1;
        $hasRead = $this->Project->hasRead(3);
        $this->assertTrue($hasRead, "user has incorrect privileges");

        $hasRead = $this->Project->hasRead(3, 1);
        $this->assertTrue($hasRead, "user has incorrect privileges");
    }

    /**
     * test Project->hasRead function.
     * Tests that a user has write privileges
     *
     * @access public
     * @return void
     */
    public function testHasReadUser() {
		$this->Project->id = 1;
        $hasRead = $this->Project->hasRead(4);
        $this->assertTrue($hasRead, "user has incorrect privileges");

        $hasRead = $this->Project->hasRead(4, 1);
        $this->assertTrue($hasRead, "user has incorrect privileges");
    }

    /**
     * test Project->hasRead function.
     * Tests that a project admin has write privileges.
     *
     * @access public
     * @return void
     */
    public function testHasReadAdmin() {
        $this->Project->id = 1;
        $hasRead = $this->Project->hasRead(5);
        $this->assertTrue($hasRead, "user has incorrect privileges");
        
		$hasRead = $this->Project->hasRead(5, 1);
        $this->assertTrue($hasRead, "user has incorrect privileges");
    }

    /**
     * test Project->hasRead function.
     * Tests that a system admin has write privileges to a project they have not been explicitly made admin for.
     *
     * @access public
     * @return void
     */
    public function testHasReadSysAdmin() {
        $this->Project->id = 2;
        $hasRead = $this->Project->hasRead(5);
        $this->assertTrue($hasRead, "user has incorrect privileges");

        $hasRead = $this->Project->hasRead(5, 2);
        $this->assertTrue($hasRead, "user has incorrect privileges");
    }

    /**
     * test Project->hasWrite function.
     *
     * @access public
     * @return void
     */
    public function testHasWriteInvalidUser() {
        $hasWrite = $this->Project->hasWrite(null, 1);
        $this->assertFalse($hasWrite, "user has incorrect privileges");

		$this->Project->id = 1;
        $hasWrite = $this->Project->hasWrite();
        $this->assertFalse($hasWrite, "user has incorrect privileges");
    }

    /**
     * test Project->hasWrite function.
     * Tests that a guest does not have write privileges.
     *
     * @access public
     * @return void
     */
    public function testHasWriteGuest() {
		$this->Project->id = 1;
        $hasWrite = $this->Project->hasWrite(3);
        $this->assertFalse($hasWrite, "user has incorrect privileges");

        $hasWrite = $this->Project->hasWrite(3, 1);
        $this->assertFalse($hasWrite, "user has incorrect privileges");
    }

    /**
     * test Project->hasWrite function.
     * Tests that a user has write privileges
     *
     * @access public
     * @return void
     */
    public function testHasWriteUser() {
		$this->Project->id = 1;
        $hasWrite = $this->Project->hasWrite(4);
        $this->assertTrue($hasWrite, "user has incorrect privileges");

        $hasWrite = $this->Project->hasWrite(4, 1);
        $this->assertTrue($hasWrite, "user has incorrect privileges");
    }

    /**
     * test Project->hasWrite function.
     * Tests that a project admin has write privileges.
     *
     * @access public
     * @return void
     */
    public function testHasWriteAdmin() {
        $this->Project->id = 1;
        $hasWrite = $this->Project->hasWrite(5);
        $this->assertTrue($hasWrite, "user has incorrect privileges");
        
		$hasWrite = $this->Project->hasWrite(5, 1);
        $this->assertTrue($hasWrite, "user has incorrect privileges");
    }

    /**
     * test Project->hasWrite function.
     * Tests that a system admin has write privileges to a project they have not been explicitly made admin for.
     *
     * @access public
     * @return void
     */
    public function testHasWriteSysAdmin() {
        $this->Project->id = 2;
        $hasWrite = $this->Project->hasWrite(5);
        $this->assertTrue($hasWrite, "user has incorrect privileges");

        $hasWrite = $this->Project->hasWrite(5, 2);
        $this->assertTrue($hasWrite, "user has incorrect privileges");
    }

    /**
     * test Project->isAdmin function.
     * Tests that an invalid user is not a project admin.
     *
     * @access public
     * @return void
     */
    public function testIsAdminInvalidUser() {
		$this->Project->id = 1;
        $hasWrite = $this->Project->isAdmin();
        $this->assertFalse($hasWrite, "user has incorrect privileges");

        $hasWrite = $this->Project->isAdmin(null, 1);
        $this->assertFalse($hasWrite, "user has incorrect privileges");
    }

    /**
     * test Project->isAdmin function.
     * Tests that a guest is not a project admin.
     *
     * @access public
     * @return void
     */
    public function testIsAdminGuest() {
		$this->Project->id = 1;
        $hasWrite = $this->Project->isAdmin(3);
        $this->assertFalse($hasWrite, "user has incorrect privileges");

        $hasWrite = $this->Project->isAdmin(3, 1);
        $this->assertFalse($hasWrite, "user has incorrect privileges");
    }

    /**
     * test Project->isAdmin function.
     * Tests that a user is not a project admin.
     *
     * @access public
     * @return void
     */
    public function testIsAdminUser() {
		$this->Project->id = 1;
        $hasWrite = $this->Project->isAdmin(4);
        $this->assertFalse($hasWrite, "user has incorrect privileges");

        $hasWrite = $this->Project->isAdmin(4, 1);
        $this->assertFalse($hasWrite, "user has incorrect privileges");
    }

    /**
     * test Project->isAdmin function.
     * Tests that an admin is a project admin.
     *
     * @access public
     * @return void
     */
    public function testIsAdminAdmin() {
        $this->Project->id = 1;
        $hasWrite = $this->Project->isAdmin(5);
        $this->assertTrue($hasWrite, "user has incorrect privileges");

        $hasWrite = $this->Project->isAdmin(5, 1);
        $this->assertTrue($hasWrite, "user has incorrect privileges");
    }


	public function testDelete() {
		$this->Project->id = 3;
		$ok = $this->Project->delete();
		$this->assertTrue($ok, "Failed to delete project");
		$project_data = $this->Project->findById(3);
		$this->assertEqual($project_data, array(), "Project data retrieved after deletion");
	}

	public function testDeleteWithGitRepository() {
		$this->Project->id = 1;

		// Check that the git repo exists
		$repoLocation = $this->Project->Source->getRepositoryLocation();
		$repo = new Folder($repoLocation);
		$this->assertEquals($repo->cd($repoLocation), $repoLocation);

		// Delete project, should also delete the repo
		$ok = $this->Project->delete();
		$this->assertTrue($ok, "Failed to delete project");

		// Ensure the repo is now gone along with the project
		$this->assertFalse($repo->cd($repoLocation));
		$project_data = $this->Project->findById(1);
		$this->assertEqual($project_data, array(), "Project data retrieved after deletion");
	}

	public function testDeleteWithMissingGitRepository() {
		$this->Project->id = 1;

		// Check that the git repo exists
		$repoLocation = $this->Project->Source->getRepositoryLocation();
		$repo = new Folder($repoLocation);
		$this->assertEquals($repo->cd($repoLocation), $repoLocation);
		$this->assertEquals($repo->delete(), true);

		// Delete project, should also delete the repo
		$ok = $this->Project->delete();
		$this->assertTrue($ok, "Failed to delete project");

		// Ensure the repo is now gone along with the project
		$this->assertFalse($repo->cd($repoLocation));
		$project_data = $this->Project->findById(1);
		$this->assertEqual($project_data, array(), "Project data retrieved after deletion");
	}

	public function testFailToChangeName() {
		$before = $this->Project->findById(1);
		$saved = $this->Project->save(array('name' => 'shoes'));
		$this->assertNotContains('name', $saved['Project']);
		$after = $this->Project->findById(1);
		$this->assertEquals($before, $after);
	}

	public function testGetProjectBacklog() {
		$this->Project->id = 2;
		$this->Project->read();
		$backlog = $this->Project->getProjectBacklog();

		// Ignore the modified/created dates on TaskStatus et al, we don't care...
		foreach ($backlog as $i => $b) {
			unset($b['TaskStatus']['created']);
			unset($b['TaskStatus']['modified']);
			unset($b['TaskPriority']['created']);
			unset($b['TaskPriority']['modified']);
			unset($b['TaskType']['created']);
			unset($b['TaskType']['modified']);
			$backlog[$i] = $b;
		}

		$this->assertEquals($backlog, array(
		array(
			'Task' => array(
				'id' => '3',
				'project_id' => '2',
				'owner_id' => '3',
				'task_type_id' => '1',
				'task_status_id' => '2',
				'task_priority_id' => '3',
				'assignee_id' => '0',
				'milestone_id' => '0',
				'time_estimate' => '2h 25m',
				'story_points' => '0',
				'subject' => 'In Progress Urgent Task 3 for no milestone',
				'description' => 'lorem ipsum dolor sit amet',
				'created' => '0000-00-00 00:00:00',
				'modified' => '0000-00-00 00:00:00',
				'deleted' => '0',
				'deleted_date' => null,
				'public_id' => '3',
				'dependenciesComplete' => 1
			),
			'Project' => array(
				'id' => '2',
				'name' => 'public',
				'description' => 'desc',
				'public' => true,
				'repo_type' => '1',
				'created' => '2012-06-01 12:46:07',
				'modified' => '2012-06-01 12:46:07',
				'deleted' => '0',
				'deleted_date' => null
			),
			'Owner' => array(
				'id' => '3',
				'name' => 'Mrs Guest',
				'email' => 'mrs.guest@example.com',
				'password' => 'Lorem ipsum dolor sit amet',
				'is_admin' => false,
				'is_active' => true,
				'theme' => 'default',
				'created' => '2012-06-01 12:50:08',
				'modified' => '2012-06-01 12:50:08',
				'deleted' => '0',
				'deleted_date' => null,
				'is_internal' => '1'
			),
			'TaskType' => array(
				'id' => '1',
				'name' => 'bug',
				'label' => 'Bug',
				'icon' => '',
				'class' => 'important',
			),
			'TaskStatus' => array(
				'id' => '2',
				'name' => 'in progress',
				'label' => 'In Progress',
				'icon' => '',
				'class' => 'warning',
			),
			'TaskPriority' => array(
				'id' => '3',
				'name' => 'urgent',
				'label' => 'Urgent',
				'level' => 3,
				'icon' => 'exclamation-sign',
				'class' => '',
			),
			'Assignee' => array(
				'password' => null,
				'id' => null,
				'name' => null,
				'email' => null,
				'is_admin' => null,
				'is_active' => null,
				'theme' => null,
				'created' => null,
				'modified' => null,
				'deleted' => null,
				'deleted_date' => null,
				'is_internal' => '0'
			),
			'Milestone' => array(
				'id' => null,
				'project_id' => null,
				'subject' => null,
				'description' => null,
				'due' => null,
				'is_open' => null,
				'created' => null,
				'modified' => null,
				'deleted' => null,
				'deleted_date' => null
			),
			'TaskComment' => array(),
			'Time' => array(),
			'DependsOn' => array(),
			'DependedOnBy' => array()
		)), "Incorrect project backlog returned");
	}

	public function testFetchEventsForProject() {
		$this->Project->id = 1;
		$this->Project->read();
		$events = $this->Project->fetchEventsForProject();
		$this->assertEquals($events, array(
			0 => array(
				'modified' => '2014-07-31 18:45:27',
				'Type' => 'Source',
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
					'id' => '25b7355ef40ec350de1fdfefd332170e9740155f',
					'title' => 'second checkin',
					'exists' => true
				),
				'Change' => array(
					'field' => '+',
					'field_old' => null,
					'field_new' => null
				),
				'url' => array(
					'api' => false,
					'project' => 'private',
					'controller' => 'source',
					'action' => 'commit',
					0 => '25b7355ef40ec350de1fdfefd332170e9740155f'
				)
			),
			1 => array(
				'modified' => '2014-07-31 18:45:27',
				'Type' => 'Source',
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
					'id' => '783d699c9acd8dbb9a8ba441038ab3d9440a6ead',
					'title' => 'third checkin ermagerd',
					'exists' => true
				),
				'Change' => array(
					'field' => '+',
					'field_old' => null,
					'field_new' => null
				),
				'url' => array(
					'api' => false,
					'project' => 'private',
					'controller' => 'source',
					'action' => 'commit',
					0 => '783d699c9acd8dbb9a8ba441038ab3d9440a6ead'
				)
			),
			2 => array(
				'modified' => '2014-07-31 18:45:27',
				'Type' => 'Source',
				'Project' => array(
					'id' => '1',
					'name' => 'private'
				),
				'Actioner' => array(
					'id' => '2',
					'name' => 'Mrs Smith',
					'email' => 'Mrs.Smith@example.com',
					'exists' => true
				),
				'Subject' => array(
					'id' => '7fafe8bdeb1b29964959aafe0133aa2123be6d84',
					'title' => 'stop overengineering',
					'exists' => true
				),
				'Change' => array(
					'field' => '+',
					'field_old' => null,
					'field_new' => null
				),
				'url' => array(
					'api' => false,
					'project' => 'private',
					'controller' => 'source',
					'action' => 'commit',
					0 => '7fafe8bdeb1b29964959aafe0133aa2123be6d84'
				)
			),
			3 => array(
				'modified' => '2014-07-31 18:45:26',
				'Type' => 'Source',
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
					'id' => '6df76712905c60dc439f1edee8b593b835782036',
					'title' => 'first ever checkin',
					'exists' => true
				),
				'Change' => array(
					'field' => '+',
					'field_old' => null,
					'field_new' => null
				),
				'url' => array(
					'api' => false,
					'project' => 'private',
					'controller' => 'source',
					'action' => 'commit',
					0 => '6df76712905c60dc439f1edee8b593b835782036'
				)
			),
			4 => array(
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
					0 => '5'
				)
			),
			5 => array(
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
					0 => '5'
				)
			)
		), "Incorrect project events returned");
	}

	public function testRenameUnknownProject() {
		try{
			$this->Project->rename(99, 'not_at_all_public');
		} catch (NotFoundException $e) {
			$this->assertTrue(true);
		} catch (Exception $e) {
			$this->assertFalse(true, "Wrong exception was thrown when renaming unknown project");
		}
	}

	public function testRenameNoOp() {
		$before = $this->Project->findById(2);
		$this->assertTrue($this->Project->rename(2, 'public'));
		$after = $this->Project->findById(2);
		$this->assertEquals($before, $after);
	}

	public function testRenameDestinationExists() {
		$before = $this->Project->findById(1);
		try {
			$this->Project->rename(1, 'public');
		} catch(InvalidArgumentException $e) {
			$this->assertTrue(true);
		} catch (Exception $e) {
			$this->assertFalse(true, "Wrong exception was thrown when renaming project over another one");
		}

		$after = $this->Project->findById(1);
		$this->assertEquals($before, $after);
	}

	public function testRenameByIdNoRepository() {
		$before = $this->Project->findById(2);
		$this->assertTrue($this->Project->rename(2, 'not_at_all_public'));
		$after = $this->Project->findById(2);
		$before['Project']['name'] = 'not_at_all_public';
		unset($before['Project']['modified']);
		unset($after['Project']['modified']);
		$this->assertEquals($before, $after);
	}

	public function testRenameByIdGitRepository() {
		$before = $this->Project->findById(1);
		$this->assertTrue($this->Project->rename(1, 'not_at_all_private'));
		$after = $this->Project->findById(1);
		$before['Project']['name'] = 'not_at_all_private';
		unset($before['Project']['modified']);
		unset($after['Project']['modified']);
		$this->assertEquals($before, $after);
	}

	public function testRenameByNameNoRepository() {
		$before = $this->Project->findById(2);
		$this->assertTrue($this->Project->rename("public", 'not_at_all_public'));
		$after = $this->Project->findById(2);
		$before['Project']['name'] = 'not_at_all_public';
		unset($before['Project']['modified']);
		unset($after['Project']['modified']);
		$this->assertEquals($before, $after);
	}

	public function testRenameByNameGitRepository() {
		$before = $this->Project->findById(1);
		$this->assertTrue($this->Project->rename("private", 'not_at_all_private'));
		$after = $this->Project->findById(1);
		$before['Project']['name'] = 'not_at_all_private';
		unset($before['Project']['modified']);
		unset($after['Project']['modified']);
		$this->assertEquals($before, $after);
	}

	// Rename, but fail because a git repository exists in the new location
	public function testRenameWithClashingGitRepository() {
		$sourcekettleConfig = ClassRegistry::init('Setting')->loadConfigSettings();
		$base = $sourcekettleConfig['repo']['base'];
		mkdir("$base/not_at_all_private.git");
		$before = $this->Project->findById(1);
		try{
			$this->assertFalse($this->Project->rename("private", 'not_at_all_private'));
		} catch (InvalidArgumentException $e) {
			$this->assertTrue(true, "Caught expected exception");
		} catch (Exception $e) {
			$this->assertTrue(false, "Caught unexpected exception");
		}
		$after = $this->Project->findById(1);
		$this->assertEquals($before, $after);
	}

	// Rename a project which claims to have a git repo, but doesn't for some reason
	public function testRenameWithPhantomGitRepository() {
		$before = $this->Project->findById(1);
		$repo = $this->Project->Source->getRepositoryLocation();
		$f = new Folder($repo);
		$f->delete();
		$this->assertTrue($this->Project->rename("private", 'not_at_all_private'));
		$after = $this->Project->findById(1);
		$before['Project']['name'] = 'not_at_all_private';
		unset($before['Project']['modified']);
		unset($after['Project']['modified']);
		$this->assertEquals($before, $after);
	}

	/*public function testRenameFail() {
		$before = $this->Project->findById(1);
		$this->Project->Folder = $this->getMock('Folder', array('move'))
			->expects($this->once())
			->method('move')
			->will($this->returnValue(false));
		$repo = $this->Project->Source->getRepositoryLocation();
		$this->assertTrue($this->Project->rename("private", 'not_at_all_private'));
	}*/

	public function testListCollaborators() {
		$this->Project->id = 1;
		$collabs = $this->Project->listCollaborators();
		$this->assertEquals(array(
			1 => 'Mr Smith',
			3 => 'Mrs Guest',
			4 => 'Mr User',
			5 => 'Mr Admin',
			10 => 'Another user',
		), $collabs);
	}
}
