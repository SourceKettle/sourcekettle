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
        $before = $this->Setting->findByName('Status.sync_required');
        $this->assertEquals($before['Setting']['name'], 'Status.sync_required', "Failed to retrieve sync_required setting");
        $this->assertEquals($before['Setting']['value'], '0', "Incorrect sync_required setting found");

        $this->Setting->syncRequired();

        $after = $this->Setting->findByName('Status.sync_required');
        $this->assertEquals($after['Setting']['name'], 'Status.sync_required', "Failed to retrieve sync_required setting");
        $this->assertEquals($after['Setting']['value'], '1', "Incorrect sync_required setting found");
    }

	public function testLoadConfigSettings() {
		$settings = $this->Setting->loadConfigSettings();
		$this->assertEquals(array(
			'UserInterface' => array(
				'alias' => array('value' => 'SourceKettle Test Site', 'source' => 'System settings', 'locked' => 0),
				'theme' => array('value' => 'default', 'source' => 'Defaults', 'locked' => 0),
				'terminology' => array('value' => 'default', 'source' => 'Defaults', 'locked' => 0),
			),
			'SourceRepository' => array(
				'user' => array('value' => 'nobody', 'source' => 'System settings', 'locked' => 0),
				'base' => array('value' => dirname(dirname(dirname(__DIR__))).'/Test/Fixture/repositories', 'source' => 'System settings', 'locked' => 0),
				'default' => array('value' => 'Git', 'source' => 'Defaults', 'locked' => 0),
			),
			'Status' => array(
				'sync_required' => array('value' => '0', 'source' => 'System settings', 'locked' => 0),
			),
			'Users' => array(
				'sysadmin_email' => array('value' => 'admin@example.org', 'source' => 'System settings', 'locked' => 0),
				'send_email_from' => array('value' => 'sysadmin@example.com', 'source' => 'Defaults', 'locked' => 0),
				'register_enabled' => array('value' => true, 'source' => 'Defaults', 'locked' => 0),
			),
			'Features' => array(
				'task_enabled' => array('value' => true, 'source' => 'System settings', 'locked' => 0),
				'time_enabled' => array('value' => true, 'source' => 'Defaults', 'locked' => 0),
				'source_enabled' => array('value' => true, 'source' => 'Defaults', 'locked' => 0),
				'attachment_enabled' => array('value' => true, 'source' => 'Defaults', 'locked' => 0),
			),
			'Ldap' => array(
				'enabled' => array('value' => '0', 'source' => 'Defaults', 'locked' => 0),
				'url' => array('value' => 'ldaps://ldap.example.com', 'source' => 'Defaults', 'locked' => 0),
				'bind_dn' => array('value' => 'cn=some_user,ou=Users,dc=example,dc=com', 'source' => 'Defaults', 'locked' => 0),
				'bind_pw' => array('value' => 'some_password', 'source' => 'Defaults', 'locked' => 0),
				'base_dn' => array('value' => 'ou=Users,dc=example,dc=com', 'source' => 'Defaults', 'locked' => 0),
				'filter' => array('value' => 'mail=%USERNAME%', 'source' => 'Defaults', 'locked' => 0),
			),
		), $settings, "Incorrect settings returned");
	}
}
