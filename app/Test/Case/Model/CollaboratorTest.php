<?php
/**
*
* Collaborator Unit Tests for the SourceKettle system
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
App::uses('Collaborator', 'Model');

class CollaboratorTestCase extends CakeTestCase {

    /**
     * fixtures - Populate the database with data of the following models
     */
    public $fixtures = array(
		'app.collaborator',
		'app.user',
		'app.project',
		'app.project_history',
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

	public function testFetchHistory() {
		$history = $this->Collaborator->fetchHistory('private', 10);
		$this->assertEquals($history, array(
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
					0 => '5'
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
					0 => '5'
				)
			),
		), "Incorrect history data returned");
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
            1 => "Mr Smith [Mr.Smith@example.com]",
            3 => "Mrs Guest [mrs.guest@example.com]",
            4 => "Mr User [mr.user@example.com]",
            5 => "Mr Admin [mr.admin@example.com]",
            10 => "Another user [another-user@example.com]",
        );
        $this->assertEquals($usersB, $usersA, json_encode($usersA));
    }
}
