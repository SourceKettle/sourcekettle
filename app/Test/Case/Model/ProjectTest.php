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
    public $fixtures = array('app.project', 'app.repo_type', 'app.collaborator', 'app.user');

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

        // Set ourselves as user 1
        User::store(array(
        	'id' => 1
        ));
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
    public function testGetProjectName() {
        $project = $this->Project->getProject('private');
        $this->assertTrue($project != null, "Project is null");
        $this->assertEquals('private', $project['Project']['name'], "wrong project returned");
    }

    /**
     * test Project->getProject function.
     * Tests that a non member can view a public project
     *
     * @access public
     * @return void
     */
    public function testGetProjectReadPublicNonMember() {
        User::store(array(
        	'id' => 2
        ));
        $project = $this->Project->getProject('public');
        $this->assertTrue($project != null, "Project is null");
        $this->assertEquals('public', $project['Project']['name'], "wrong project returned");
    }

    /**
     * test Project->getProject function.
     * Tests that a non member cannot view a private project
     *
     * @access public
     * @return void
     */
    public function testGetProjectReadPrivateNonMember() {
        User::store(array(
        	'id' => 2
        ));
        try {
            $project = $this->Project->getProject('private');
            $this->assertTrue(false, "getProject did not throw an exception");
        } catch (ForbiddenException $e) {
            $this->assertTrue(true, "getProject threw exception {$e->getMessage()}");
        } catch (Exception $e) {
            $this->assertTrue(false, "getProject threw wrong exception {$e->getMessage()}");
        }
    }

    /**
     * test Project->getProject function..
     * Tests that an invalid user is denied access to a project
     *
     * @access public
     * @return void
     */
    public function testGetProjectReadInvalidUser() {
        User::store(array(
        	'id' => 0
        ));
        try {
            $project = $this->Project->getProject(1);
            $this->assertTrue(false, "getProject did not throw an exception");
        } catch (Exception $e) {
            $this->assertTrue(true, "getProject threw exception {$e->getMessage()}");
        }
    }

    /**
     * test Project->hasWrite function.
     *
     * @access public
     * @return void
     */
    public function testHasWriteInvalidUser() {
        User::store(array(
        	'id' => null
        ));
        $hasWrite = $this->Project->hasWrite(null, 1);
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
        $hasWrite = $this->Project->hasWrite(5, null);
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
        $hasWrite = $this->Project->hasWrite(5, null);
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
        User::store(array(
        	'id' => null
        ));
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
        $hasWrite = $this->Project->isAdmin(5, null);
        $this->assertTrue($hasWrite, "user has incorrect privileges");
    }
}
