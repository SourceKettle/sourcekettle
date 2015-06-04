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
		'app.story',
	);

    public function setUp() {
        parent::setUp();
        $this->Project = ClassRegistry::init('Project');
    }

    public function tearDown() {
        unset($this->Project);
        parent::tearDown();
    }


    public function testGetProjectNull() {
        $project = $this->Project->getProject(null);
        $this->assertTrue($project == null, "Project is not null");
    }

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

    public function testGetProjectByName() {
        $project = $this->Project->getProject('private');
        $this->assertTrue($project != null, "Project is null");
        $this->assertEquals('private', $project['Project']['name'], "wrong project returned");
    }

    public function testGetProjectReadInvalidUser() {
        try {
            $project = $this->Project->getProject(1);
            $this->assertTrue(false, "getProject did not throw an exception");
        } catch (Exception $e) {
            $this->assertTrue(true, "getProject threw exception {$e->getMessage()}");
        }
    }

    public function testHasReadInvalidUser() {
        $hasRead = $this->Project->hasRead(null, 1);
        $this->assertFalse($hasRead, "user has incorrect privileges");

		$this->Project->id = 1;
        $hasRead = $this->Project->hasRead();
        $this->assertFalse($hasRead, "user has incorrect privileges");
    }

    public function testHasReadNotCollaboratorPrivate() {
        $hasRead = $this->Project->hasRead(2, 3);
        $this->assertFalse($hasRead, "user has incorrect privileges");

		$this->Project->id = 3;
        $hasRead = $this->Project->hasRead(2);
        $this->assertFalse($hasRead, "user has incorrect privileges");
    }

    public function testHasReadNotCollaboratorPublic() {
        $hasRead = $this->Project->hasRead(2, 4);
        $this->assertTrue($hasRead, "user has incorrect privileges");

		$this->Project->id = 4;
        $hasRead = $this->Project->hasRead(2);
        $this->assertTrue($hasRead, "user has incorrect privileges");
    }

    public function testHasReadGuest() {
		$this->Project->id = 1;
        $hasRead = $this->Project->hasRead(3);
        $this->assertTrue($hasRead, "user has incorrect privileges");

        $hasRead = $this->Project->hasRead(3, 1);
        $this->assertTrue($hasRead, "user has incorrect privileges");
    }

    public function testHasReadUser() {
		$this->Project->id = 1;
        $hasRead = $this->Project->hasRead(4);
        $this->assertTrue($hasRead, "user has incorrect privileges");

        $hasRead = $this->Project->hasRead(4, 1);
        $this->assertTrue($hasRead, "user has incorrect privileges");
    }

    public function testHasReadAdmin() {
        $this->Project->id = 1;
        $hasRead = $this->Project->hasRead(5);
        $this->assertTrue($hasRead, "user has incorrect privileges");
        
		$hasRead = $this->Project->hasRead(5, 1);
        $this->assertTrue($hasRead, "user has incorrect privileges");
    }

    public function testHasReadSysAdmin() {
        $this->Project->id = 2;
        $hasRead = $this->Project->hasRead(5);
        $this->assertTrue($hasRead, "user has incorrect privileges");

        $hasRead = $this->Project->hasRead(5, 2);
        $this->assertTrue($hasRead, "user has incorrect privileges");
    }

    public function testHasWriteInvalidUser() {
        $hasWrite = $this->Project->hasWrite(null, 1);
        $this->assertFalse($hasWrite, "user has incorrect privileges");

		$this->Project->id = 1;
        $hasWrite = $this->Project->hasWrite();
        $this->assertFalse($hasWrite, "user has incorrect privileges");
    }

    public function testHasWriteGuest() {
		$this->Project->id = 1;
        $hasWrite = $this->Project->hasWrite(3);
        $this->assertFalse($hasWrite, "user has incorrect privileges");

        $hasWrite = $this->Project->hasWrite(3, 1);
        $this->assertFalse($hasWrite, "user has incorrect privileges");
    }

    public function testHasWriteUser() {
		$this->Project->id = 1;
        $hasWrite = $this->Project->hasWrite(4);
        $this->assertTrue($hasWrite, "user has incorrect privileges");

        $hasWrite = $this->Project->hasWrite(4, 1);
        $this->assertTrue($hasWrite, "user has incorrect privileges");
    }

    public function testHasWriteAdmin() {
        $this->Project->id = 1;
        $hasWrite = $this->Project->hasWrite(5);
        $this->assertTrue($hasWrite, "user has incorrect privileges");
        
		$hasWrite = $this->Project->hasWrite(5, 1);
        $this->assertTrue($hasWrite, "user has incorrect privileges");
    }

    public function testHasWriteSysAdmin() {
        $this->Project->id = 2;
        $hasWrite = $this->Project->hasWrite(5);
        $this->assertTrue($hasWrite, "user has incorrect privileges");

        $hasWrite = $this->Project->hasWrite(5, 2);
        $this->assertTrue($hasWrite, "user has incorrect privileges");
    }

    public function testIsAdminInvalidUser() {
		$this->Project->id = 1;
        $hasWrite = $this->Project->isAdmin();
        $this->assertFalse($hasWrite, "user has incorrect privileges");

        $hasWrite = $this->Project->isAdmin(null, 1);
        $this->assertFalse($hasWrite, "user has incorrect privileges");
    }

    public function testIsAdminGuest() {
		$this->Project->id = 1;
        $hasWrite = $this->Project->isAdmin(3);
        $this->assertFalse($hasWrite, "user has incorrect privileges");

        $hasWrite = $this->Project->isAdmin(3, 1);
        $this->assertFalse($hasWrite, "user has incorrect privileges");
    }

    public function testIsAdminUser() {
		$this->Project->id = 1;
        $hasWrite = $this->Project->isAdmin(4);
        $this->assertFalse($hasWrite, "user has incorrect privileges");

        $hasWrite = $this->Project->isAdmin(4, 1);
        $this->assertFalse($hasWrite, "user has incorrect privileges");
    }

    public function testIsAdminAdmin() {
        $this->Project->id = 1;
        $hasWrite = $this->Project->isAdmin(5);
        $this->assertTrue($hasWrite, "user has incorrect privileges");

        $hasWrite = $this->Project->isAdmin(5, 1);
        $this->assertTrue($hasWrite, "user has incorrect privileges");
    }

	// Check that our 3 sets of developers (PHP, Java and Python devs)
	// have admin access to their respective projects via team/group collaboration
	public function testCheckAdminAccessLevelToGroupedProjects() {

		// NB: order of args is access_level, user_id, project_id

		// Check PHP devs/projects
		$this->assertTrue($this->Project->checkAccessLevel(2, 13, 6), "PHP developer should be an admin on both PHP projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 13, 7), "PHP developer should be an admin on both PHP projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 16, 6), "PHP developer should be an admin on both PHP projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 16, 7), "PHP developer should be an admin on both PHP projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 17, 6), "PHP developer should be an admin on both PHP projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 17, 7), "PHP developer should be an admin on both PHP projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 19, 6), "PHP developer should be an admin on both PHP projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 19, 7), "PHP developer should be an admin on both PHP projects");

		// Check Java devs/projects
		$this->assertTrue($this->Project->checkAccessLevel(2, 14, 8), "Java developer should be an admin on both Java projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 14, 9), "Java developer should be an admin on both Java projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 17, 8), "Java developer should be an admin on both Java projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 17, 9), "Java developer should be an admin on both Java projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 18, 8), "Java developer should be an admin on both Java projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 18, 9), "Java developer should be an admin on both Java projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 19, 8), "Java developer should be an admin on both Java projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 19, 9), "Java developer should be an admin on both Java projects");

		// Check Python devs/projects
		$this->assertTrue($this->Project->checkAccessLevel(2, 15, 10), "Python developer should be an admin on both Python projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 15, 11), "Python developer should be an admin on both Python projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 16, 10), "Python developer should be an admin on both Python projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 16, 11), "Python developer should be an admin on both Python projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 18, 10), "Python developer should be an admin on both Python projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 18, 11), "Python developer should be an admin on both Python projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 19, 10), "Python developer should be an admin on both Python projects");
		$this->assertTrue($this->Project->checkAccessLevel(2, 19, 11), "Python developer should be an admin on both Python projects");
	}

	// Check that our 3 sets of developers (PHP, Java and Python devs)
	// have user-level access to their respective non-specialist projects via team/group collaboration
	public function testCheckUserAccessLevelToGroupedProjects() {

		// Multi-skilled developers have admin access, checked above
	
		// NB: order of args is access_level, user_id, project_id

		// Check PHP devs/Java projects
		$this->assertTrue($this->Project->checkAccessLevel(1, 13, 8), "PHP developer should be a user on both Java projects");
		$this->assertTrue($this->Project->checkAccessLevel(1, 13, 9), "PHP developer should be a user on both Java projects");
		$this->assertTrue($this->Project->checkAccessLevel(1, 16, 8), "PHP developer should be a user on both Java projects");
		$this->assertTrue($this->Project->checkAccessLevel(1, 16, 9), "PHP developer should be a user on both Java projects");

		// Check Java devs/Python projects
		$this->assertTrue($this->Project->checkAccessLevel(1, 14, 10), "Java developer should be a user on both Python projects");
		$this->assertTrue($this->Project->checkAccessLevel(1, 14, 11), "Java developer should be a user on both Python projects");
		$this->assertTrue($this->Project->checkAccessLevel(1, 17, 10), "Java developer should be a user on both Python projects");
		$this->assertTrue($this->Project->checkAccessLevel(1, 17, 11), "Java developer should be a user on both Python projects");

		// Check Python devs/PHP projects
		$this->assertTrue($this->Project->checkAccessLevel(1, 15, 6), "Python developer should be a user on both PHP projects");
		$this->assertTrue($this->Project->checkAccessLevel(1, 15, 7), "Python developer should be a user on both PHP projects");
		$this->assertTrue($this->Project->checkAccessLevel(1, 18, 6), "Python developer should be a user on both PHP projects");
		$this->assertTrue($this->Project->checkAccessLevel(1, 18, 7), "Python developer should be a user on both PHP projects");
		
	}

	// Check that our 3 sets of developers (PHP, Java and Python devs)
	// have no access to their respective non-linked projects via team/group collaboration
	public function testCheckNoAccessLevelToGroupedProjects() {

		// NB: order of args is access_level, user_id, project_id

		// Check PHP devs/Python projects
		$this->assertFalse($this->Project->checkAccessLevel(0, 13, 10), "PHP developer should have no access Python projects");
		$this->assertFalse($this->Project->checkAccessLevel(0, 13, 11), "PHP developer should have no access Python projects");

		// Check Java devs/PHP projects
		$this->assertFalse($this->Project->checkAccessLevel(0, 14, 6), "Java developer should have no access PHP projects");
		$this->assertFalse($this->Project->checkAccessLevel(0, 14, 7), "Java developer should have no access PHP projects");

		// Check Python devs/Java projects
		$this->assertFalse($this->Project->checkAccessLevel(0, 15, 8), "Python developer should have no access Java projects");
		$this->assertFalse($this->Project->checkAccessLevel(0, 15, 9), "Python developer should have no access Java projects");
	}

	// Check that our ninja perl team has access to the one perl project
	public function testCheckAdminAccessLevelToPerlProject() {

		// NB: order of args is access_level, user_id, project_id

		$this->assertTrue($this->Project->checkAccessLevel(2, 20, 12), "Perl developer should have admin access to Perl project");
		$this->assertTrue($this->Project->checkAccessLevel(2, 21, 12), "Perl developer should have admin access to Perl project");

	}

	public function testDelete() {
		$this->Project->id = 3;

		$ok = $this->Project->delete();
		$this->assertTrue($ok, "Failed to delete project");

		$project_data = $this->Project->findById(3);
		$this->assertEqual(array(), $project_data, "Project data retrieved after deletion");

		$tasks = $this->Project->Task->findByProjectId(3);
		$this->assertEqual(array(), $tasks, "Tasks retrieved after deletion");

		$milestones = $this->Project->Milestone->findByProjectId(3);
		$this->assertEqual(array(), $milestones, "Milestones retrieved after deletion");

		$times = $this->Project->Time->findByProjectId(3);
		$this->assertEqual(array(), $times, "Times retrieved after deletion");

		$collaborators = $this->Project->Collaborator->findByProjectId(3);
		$this->assertEqual(array(), $collaborators, "Collaborators retrieved after deletion");
	}

	public function testDeleteWithCollaboratingTeams() {
		$this->Project->id = 12;

		$ok = $this->Project->delete();
		$this->assertTrue($ok, "Failed to delete project");
		$collaboratingteams = $this->Project->CollaboratingTeam->findByProjectId(12);
		$this->assertEqual(array(), $collaboratingteams, "CollaboratingTeams retrieved after deletion");

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
		$saved = $this->Project->save(array('Project' => array('id' => 1, 'name' => 'shoes')));
		$this->assertNotContains('name', $saved['Project']);
		$after = $this->Project->findById(1);
		$this->assertEquals($before['Project']['name'], $after['Project']['name']);
	}

	// Test that only the tasks with no milestone are returned
	public function testGetProjectBacklog() {
		$this->Project->id = 2;
		$this->Project->read();
		$backlog = $this->Project->getProjectBacklog();
		$backlog = array_map(function($a){return $a['Task']['id'];}, $backlog);
		sort($backlog);
		$this->assertEquals(array(3, 13, 14, 15, 16, 17, 20), $backlog);
	}

	public function testFetchEventsForProject() {
		$events = $this->Project->fetchEventsForProject(1);

		$events = array_map(function($a) {
			return array(
				'Type' => $a['Type'],
				'project_id' => $a['Project']['id'],
				'actioner_id' => $a['Actioner']['id'],
				'subject_id' => $a['Subject']['id'],
				'subject_title' => $a['Subject']['title'],
				'change_field' => $a['Change']['field'],
				'change_field_old' => $a['Change']['field_old'],
				'change_field_new' => $a['Change']['field_new'],
				'url' => (isset($a['url'])? $a['url']: null),
			);
		}, $events);

		$this->assertEquals(array(
			array(
				'Type' => 'Source',
				'project_id' => '1',
				'actioner_id' => '-1',
				'subject_id' => '0b20ced61a6edb811ddbe3c502b931b0450f3a61',
				'subject_title' => 'make a change on the new thing branch',
				'change_field' => '+',
				'change_field_old' => null,
				'change_field_new' => null,
				'url' => array(
					'api' => false,
					'project' => 'private',
					'controller' => 'source',
					'action' => 'commit',
					0 => '0b20ced61a6edb811ddbe3c502b931b0450f3a61'
				)
			),
			array(
				'Type' => 'Source',
				'project_id' => '1',
				'actioner_id' => '2',
				'subject_id' => '2325c49ec93a7164bbcbd4a3c0594170d4d9e121',
				'subject_title' => 'stop overengineering',
				'change_field' => '+',
				'change_field_old' => null,
				'change_field_new' => null,
				'url' => array(
					'api' => false,
					'project' => 'private',
					'controller' => 'source',
					'action' => 'commit',
					0 => '2325c49ec93a7164bbcbd4a3c0594170d4d9e121'
				)
			),
			array(
				'Type' => 'Source',
				'project_id' => '1',
				'actioner_id' => '1',
				'subject_id' => '04022f5b0b7c9f635520f68a511cccfad4330da3',
				'subject_title' => 'third checkin ermagerd',
				'change_field' => '+',
				'change_field_old' => null,
				'change_field_new' => null,
				'url' => array(
					'api' => false,
					'project' => 'private',
					'controller' => 'source',
					'action' => 'commit',
					0 => '04022f5b0b7c9f635520f68a511cccfad4330da3'
				)
			),
			array(
				'Type' => 'Source',
				'project_id' => '1',
				'actioner_id' => '1',
				'subject_id' => '1436052fb1244a045981392e20efa03f39e0737a',
				'subject_title' => 'second checkin',
				'change_field' => '+',
				'change_field_old' => null,
				'change_field_new' => null,
				'url' => array(
					'api' => false,
					'project' => 'private',
					'controller' => 'source',
					'action' => 'commit',
					0 => '1436052fb1244a045981392e20efa03f39e0737a'
				)
			),
			array(
				'Type' => 'Source',
				'project_id' => '1',
				'actioner_id' => '1',
				'subject_id' => '848f3fe7032a76b180e9831d53e4152fd4da85d9',
				'subject_title' => 'first ever checkin',
				'change_field' => '+',
				'change_field_old' => null,
				'change_field_new' => null,
				'url' => array(
					'api' => false,
					'project' => 'private',
					'controller' => 'source',
					'action' => 'commit',
					0 => '848f3fe7032a76b180e9831d53e4152fd4da85d9'
				)
			),
			array(
				'Type' => 'Task',
				'project_id' => '1',
				'actioner_id' => '1',
				'subject_id' => '1',
				'subject_title' => '#1 (Task 1 for private project)',
				'change_field' => 'task_status_id',
				'change_field_old' => '1',
				'change_field_new' => '2',
				'url' => null,
			),
			array(
				'Type' => 'Collaborator',
				'project_id' => '1',
				'actioner_id' => '1',
				'subject_id' => '2',
				'subject_title' => 'Mr Admin',
				'change_field' => 'access_level',
				'change_field_old' => '2',
				'change_field_new' => '1',
				'url' => array(
					'api' => false,
					'admin' => false,
					'controller' => 'users',
					'action' => 'view',
					0 => '5'
				)
			),
			array(
				'Type' => 'Task',
				'project_id' => '1',
				'actioner_id' => '1',
				'subject_id' => '1',
				'subject_title' => '#1 (Task 1 for private project)',
				'change_field' => '+',
				'change_field_old' => '',
				'change_field_new' => '',
				'url' => null,
			),
		), $events);

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
		$base = $sourcekettleConfig['SourceRepository']['base']['value'];
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

	public function testRenameFail() {
		$folder = $this->getMock('Folder');
		$folder
			->expects($this->once())
			->method('move')
			->will($this->returnValue(false));
		$folder->path = $this->Project->Source->getRepositoryLocation();
	
		$this->Project = $this->getMockForModel('Project', array('getFolder'));
		$this->Project
			->expects($this->once())
			->method('getFolder')
			->will($this->returnValue($folder));
		$before = $this->Project->findById(1);
		$repo = $this->Project->Source->getRepositoryLocation();
		try{
			$this->Project->rename("private", 'not_at_all_private');
		} catch (Exception $e) {
			$this->assertEquals(__("A problem occurred when renaming the project repository"), $e->getMessage());
		}
	}

	public function testListCollaboratorsDirectOnly() {
		$this->Project->id = 1;
		$collabs = $this->Project->listCollaborators();
		$this->assertEquals(array(
			array('id' => 10, 'title' => 'Another user [another-user@example.com]'),
			array('id' => 5, 'title' => 'Mr Admin [mr.admin@example.com]'),
			array('id' => 1, 'title' => 'Mr Smith [Mr.Smith@example.com]'),
			array('id' => 4, 'title' => 'Mr User [mr.user@example.com]'),
			array('id' => 3, 'title' => 'Mrs Guest [mrs.guest@example.com]'),
		), $collabs);
	}

	public function testListCollaboratorsDirectAndTeam() {
		$collabs = $this->Project->listCollaborators(12);
		$this->assertEquals(array(
			array('id' => 20, 'title' => 'A Perl developer [perl-dev@example.com]'),
			array('id' => 21, 'title' => 'Another Perl developer [another-perl-dev@example.com]'),
			array('id' => 4, 'title' => 'Mr User [mr.user@example.com]'),
			array('id' => 3, 'title' => 'Mrs Guest [mrs.guest@example.com]'),
			array('id' => 2, 'title' => 'Mrs Smith [mrs.smith@example.com]'),
		), $collabs);
	}

	public function testListCollaboratorsTeamViaProjectGroup() {
		$collabs = $this->Project->listCollaborators(6);
		$this->assertEquals(array(
			array('id' => 17, 'title' => 'A PHP and Java developer [php-and-java-dev@example.com]'),
			array('id' => 16, 'title' => 'A PHP and Python developer [php-and-python-dev@example.com]'),
			array('id' => 13, 'title' => 'A PHP developer [php-dev@example.com]'),
			array('id' => 19, 'title' => 'A PHP, Python and Java developer [php-python-and-java-dev@example.com]'),
			array('id' => 18, 'title' => 'A Python and Java developer [python-and-java-dev@example.com]'),
			array('id' => 15, 'title' => 'A Python developer [python-dev@example.com]'),
		), $collabs);
	}

	public function testListCollaboratorsOverlapping() {
		$collabs = $this->Project->listCollaborators(13);
		// perl-dev is a direct collaborator AND a collaborator via the perl dev team, should only be listed once
		$this->assertEquals(array(
			array('id' => 20, 'title' => 'A Perl developer [perl-dev@example.com]'),
			array('id' => 21, 'title' => 'Another Perl developer [another-perl-dev@example.com]'),
		), $collabs);
	}
}
