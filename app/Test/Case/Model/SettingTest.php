<?php
/**
*
* Setting Unit Tests for the DevTrack system
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
App::uses('Setting', 'Model');

class SettingTestCase extends CakeTestCase {

    /**
     * setUp function.
     * Run before each unit test.
     * Corrrecly sets up the test environment.
     *
     * @access public
     * @return void
     */
    public $fixtures = array('app.setting');

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
        $this->Setting = ClassRegistry::init('Setting');
    }

    /**
     * tearDown function.
     * Tear down all created data after the tests.
     *
     * @access public
     * @return void
     */
    public function tearDown() {
        unset($this->Setting);

        parent::tearDown();
    }

    /**
     * test Setting->syncRequired function.
     * Tests that the sync required function sets the sync flag.
     *
     * @access public
     * @return void
     */
    public function testSyncRequired() {
        $beforeA = $this->Setting->findByName('sync_required');
        $beforeB = array(
            'Setting' => array(
                'id' => 1,
                'name' => 'sync_required',
                'value' => 0,
                'created' => '2012-06-02 20:05:59',
                'modified' => '2012-06-02 20:05:59'
            ),
        );
        $this->assertEquals($beforeA, $beforeB, "before settings arnt equal");

        $this->Setting->syncRequired();

        $afterA = $this->Setting->findByName('sync_required');
        $afterB = array(
            'Setting' => array(
                'id' => 1,
                'name' => 'sync_required',
                'value' => 1,
                'created' => '2012-06-02 20:05:59',
                'modified' => '2012-06-02 20:05:59'
            ),
        );
        $this->assertEquals($beforeA, $beforeB, "after settings arnt equal");
    }
}
