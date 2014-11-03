<?php
/**
*
* Setting Unit Tests for the SourceKettle system
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
        $before = $this->Setting->findByName('sync_required');
        $this->assertEquals($before['Setting']['name'], 'sync_required', "Failed to retrieve sync_required setting");
        $this->assertEquals($before['Setting']['value'], '0', "Incorrect sync_required setting found");

        $this->Setting->syncRequired();

        $after = $this->Setting->findByName('sync_required');
        $this->assertEquals($after['Setting']['name'], 'sync_required', "Failed to retrieve sync_required setting");
        $this->assertEquals($after['Setting']['value'], '1', "Incorrect sync_required setting found");
    }

	public function testLoadConfigSettings() {
		$settings = $this->Setting->loadConfigSettings();
		$this->assertEquals(array(
			'global' => array(
				'alias' => 'SourceKettle Test Site'
			),
			'repo' => array(
				'user' => 'nobody',
				'base' => dirname(dirname(dirname(__DIR__))).'/Test/Fixture/repositories',
				'default' => 'Git'
			),
			'sync_required' => '0',
			'sysadmin_email' => 'admin@example.org'
		), $settings, "Incorrect settings returned");
	}
}
