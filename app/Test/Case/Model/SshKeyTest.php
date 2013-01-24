<?php
/**
*
* Email Confirmation Unit Tests for the DevTrack system
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright     DevTrack Development Team 2012
* @link          http://github.com/SourceKettle/devtrack
* @package       DevTrack.Test.Case.Model
* @since         DevTrack v 1.0
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
App::uses('SshKey', 'Model');

class SshKeyTestCase extends CakeTestCase {

    /**
     * fixtures - Populate the database with data of the following models
     */
    public $fixtures = array('app.ssh_key', 'app.user');

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
        $this->SshKey = ClassRegistry::init('SshKey');
    }

    /**
     * tearDown function.
     * Tear down all created data after the tests.
     *
     * @access public
     * @return void
     */
    public function tearDown() {
        unset($this->SshKey);

        parent::tearDown();
    }

    /**
     * test fixtures function.
     *
     * @access public
     * @return void
     */
    public function testFixture() {
        $this->SshKey->recursive = -1;
        $fixtures = array(
            array(
                'SshKey' => array(
                    'id' => "1",
                    'user_id' => "1",
                    'key' => '0b73478cee542c314e014b4e4e7200670b73478cee542c314e014b4e4e720067t',
                    'comment' => 'Lorem ipsum dolor sit amet',
                    'created' => '2012-06-01 12:49:40',
                    'modified' => '2012-06-01 12:49:40'
                ),
            ),
        );
        $fixturesB = $this->SshKey->find('all');
        $this->assertEquals($fixtures, $fixturesB, "Arrays were not equal");
    }
}
