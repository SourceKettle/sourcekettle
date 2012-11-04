<?php
/**
*
* Collaborator Unit Tests for the DevTrack system
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright     DevTrack Development Team 2012
* @link          http://github.com/chrisbulmer/devtrack
* @package       DevTrack.Test.Case.Model
* @since         DevTrack v 1.0
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
App::uses('Collaborator', 'Model');

class CollaboratorTestCase extends CakeTestCase {

    /**
     * fixtures - Populate the database with data of the following models
     */
    public $fixtures = array('app.collaborator', 'app.project', 'app.user');

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
        $this->Collaborator = ClassRegistry::init('Collaborator');
    }

    /**
     * tearDown function.
     * Tear down all created data after the tests.
     *
     * @access public
     * @return void
     */
    public function tearDown() {
        unset($this->Collaborator);

        parent::tearDown();
    }

    /**
     * test Collaborator->getTitleForHistory function.
     * Tests that the getTitleForHistory function returns the name of the user.
     *
     * @access public
     * @return void
     */
    public function testGetTitleForHistoryExists() {
        $title = $this->Collaborator->getTitleForHistory(1);
        $this->assertEquals("Mr Smith", $title, "wrong title returned");
    }

    /**
     * test Collaborator->getTitleForHistory function.
     * Tests that the getTitleForHistory function null for an invalid user.
     *
     * @access public
     * @return void
     */
    public function testGetTitleForHistoryDoesntExist() {
        $title = $this->Collaborator->getTitleForHistory(0);
        $this->assertEquals(null, $title, "wrong title returned");
    }

    /**
     * test Collaborator->collaboratorsForProject function.
     * Test that the correct array is returned.
     *
     * @access public
     * @return void
     */
    public function testCollaboratorsForProject() {
        $usersA = $this->Collaborator->collaboratorsForProject(1);
        $usersB = array(
            "Mr Smith [Mr.Smith@example.com]"  => "Mr Smith [Mr.Smith@example.com]",
            "Mrs Guest [mrs.guest@example.com]"=> "Mrs Guest [mrs.guest@example.com]",
            "Mr User [mr.user@example.com]"    => "Mr User [mr.user@example.com]",
            "Mr Admin [mr.admin@example.com]"  => "Mr Admin [mr.admin@example.com]"
        );
        $this->assertEquals($usersB, $usersA, json_encode($usersA));
    }
}
