<?php
/**
*
* Email Confirmation Unit Tests for the SourceKettle system
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
App::uses('LostPasswordKey', 'Model');

class LostPasswordKeyTestCase extends CakeTestCase {

    /**
     * fixtures - Populate the database with data of the following models
     */
    public $fixtures = array('app.lost_password_key', 'app.user');

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
        $this->LostPasswordKey = ClassRegistry::init('LostPasswordKey');
    }

    /**
     * tearDown function.
     * Tear down all created data after the tests.
     *
     * @access public
     * @return void
     */
    public function tearDown() {
        unset($this->LostPasswordKey);

        parent::tearDown();
    }

    /**
     * test fixtures function.
     *
     * @access public
     * @return void
     */
    public function testFixture() {
        $this->LostPasswordKey->recursive = -1;
        $fixtures = array(
            array(
                'LostPasswordKey' => array(
                    'id' => "1",
                    'user_id' => "1",
                    'key' => 'ab169f5ff7fbbcdd7db9bd077',
                    'created' => '2012-11-04 13:16:47',
                    'modified' => '2012-11-04 13:16:47'
                ),
            ),
        );
        $fixturesB = $this->LostPasswordKey->find('all');
        $this->assertEquals($fixtures, $fixturesB, "Arrays were not equal");
    }
}
