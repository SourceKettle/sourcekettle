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
				'alias' => array('value' => 'SourceKettle Test Site', 'source' => 'System settings', 'locked' => false),
				'theme' => array('value' => 'amelia', 'source' => 'System settings', 'locked' => false),
				'terminology' => array('value' => 'default', 'source' => 'System settings', 'locked' => true),
			),
			'SourceRepository' => array(
				'user' => array('value' => 'nobody', 'source' => 'System settings', 'locked' => false),
				'base' => array('value' => dirname(dirname(dirname(__DIR__))).'/Test/Fixture/repositories', 'source' => 'System settings', 'locked' => false),
				'default' => array('value' => 'Git', 'source' => 'Defaults', 'locked' => false),
			),
			'Status' => array(
				'sync_required' => array('value' => '0', 'source' => 'System settings', 'locked' => false),
			),
			'Users' => array(
				'sysadmin_email' => array('value' => 'admin@example.org', 'source' => 'System settings', 'locked' => false),
				'send_email_from' => array('value' => 'sysadmin@example.com', 'source' => 'Defaults', 'locked' => false),
				'register_enabled' => array('value' => true, 'source' => 'Defaults', 'locked' => false),
			),
			'Features' => array(
				'task_enabled' => array('value' => true, 'source' => 'System settings', 'locked' => false),
				'time_enabled' => array('value' => true, 'source' => 'Defaults', 'locked' => false),
				'source_enabled' => array('value' => false, 'source' => 'System settings', 'locked' => true),
				'attachment_enabled' => array('value' => true, 'source' => 'Defaults', 'locked' => false),
			),
			'Ldap' => array(
				'enabled' => array('value' => false, 'source' => 'Defaults', 'locked' => false),
				'url' => array('value' => 'ldaps://ldap.example.com', 'source' => 'Defaults', 'locked' => false),
				'bind_dn' => array('value' => 'cn=some_user,ou=Users,dc=example,dc=com', 'source' => 'Defaults', 'locked' => false),
				'bind_pw' => array('value' => 'some_password', 'source' => 'Defaults', 'locked' => false),
				'base_dn' => array('value' => 'ou=Users,dc=example,dc=com', 'source' => 'Defaults', 'locked' => false),
				'filter' => array('value' => 'mail=%USERNAME%', 'source' => 'Defaults', 'locked' => false),
			),
		), $settings, "Incorrect settings returned");
	}


	public function testFlattenTree() {
		$before = array(
			'UserInterface' => array(
				'theme' => 'amelia',
				'alias' => 'MooseKettle',
			),
			'Users' => array(
				'register_enabled' => true,
				'sysadmin_email' => 'foo@bar.baz',
			),
			'Features' => array(
				'task_enabled' => 'true',
				'time_enabled' => 'false',
			),
		);

		$this->assertEquals(array(
			'UserInterface.theme' => 'amelia',
			'UserInterface.alias' => 'MooseKettle',
			'Users.register_enabled' => true,
			'Users.sysadmin_email' => 'foo@bar.baz',
			'Features.task_enabled' => true,
			'Features.time_enabled' => false,
		), $this->Setting->flattenTree($before));
	}

	public function testSaveSettingsTreeNoSettings() {
		$this->assertFalse($this->Setting->saveSettingsTree(array()));
		$this->assertFalse($this->Setting->saveSettingsTree(array('Foo' => 'bar')));
	}

	public function testSaveSettingsTree() {
		$before = array('Setting' => array(
			'UserInterface' => array(
				'theme' => 'spacelab',
				'alias' => 'MooseKettle',
			),
			'Users' => array(
				'register_enabled' => true,
				'sysadmin_email' => 'foo@bar.baz',
			),
			'Features' => array(
				'task_enabled' => 'true',
				'time_enabled' => 'false',
				'moose_enabled' => 'false',
			),
		));
		$this->assertTrue($this->Setting->saveSettingsTree($before, false));

		$after = $this->Setting->findByName('UserInterface.theme');
		$this->assertEquals('spacelab', $after['Setting']['value']);
		$after = $this->Setting->findByName('UserInterface.alias');
		$this->assertEquals('MooseKettle', $after['Setting']['value']);
		$after = $this->Setting->findByName('Users.register_enabled');
		$this->assertEquals('1', $after['Setting']['value']);
		$after = $this->Setting->findByName('Users.sysadmin_email');
		$this->assertEquals('foo@bar.baz', $after['Setting']['value']);
		$after = $this->Setting->findByName('Features.task_enabled');
		$this->assertEquals(true, $after['Setting']['value']);
		$after = $this->Setting->findByName('Features.time_enabled');
		$this->assertEquals(false, $after['Setting']['value']);
		$after = $this->Setting->findByName('Features.moose_enabled');
		$this->assertEquals(array(), $after);
	}

	public function testSaveSettingsTreeLocked() {
		$before = array('Setting' => array(
			'UserInterface' => array(
				'theme' => true,
			),
			'Features' => array(
				'task_enabled' => 'true',
			),
		));
		$this->assertTrue($this->Setting->saveSettingsTree($before, true));

		$after = $this->Setting->findByName('UserInterface.theme');
		$this->assertTrue($after['Setting']['locked']);
		$after = $this->Setting->findByName('Features.task_enabled');
		$this->assertTrue($after['Setting']['locked']);
	}

	public function testSaveSettingsTreeLockedUnknownSetting() {
		$before = array('Setting' => array(
			'UserInterface' => array(
				'theme' => true,
			),
			'Features' => array(
				'time_enabled' => 'true',
			),
		));
		$this->assertFalse($this->Setting->saveSettingsTree($before, true));
	}

	public function testSaveSettingsTreeSaveFail() {
		$before = array('Setting' => array(
			'UserInterface' => array(
				'theme' => 'default',
			),
		));

		$this->Setting = $this->getMockForModel('Setting', array('save'));
		$this->Setting
			->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$this->assertFalse($this->Setting->saveSettingsTree($before, true));
	}

	public function testLoadSettingsWithUserSettings() {
		$settings = $this->Setting->loadConfigSettings(1);
		$this->assertEquals('spruce', $settings['UserInterface']['theme']['value']);
		$this->assertTrue($settings['Features']['attachment_enabled']['value']);
	}

	public function testLoadSettingsWithProjectSettings() {
		$settings = $this->Setting->loadConfigSettings(null, 2);
		$this->assertEquals('amelia', $settings['UserInterface']['theme']['value']);
		$this->assertFalse($settings['Features']['attachment_enabled']['value']);
	}

	public function testLoadSettingsWithProjectSettingsNoProject() {
		$settings = $this->Setting->loadConfigSettings(null, 'gribble');
		$this->assertEquals('amelia', $settings['UserInterface']['theme']['value']);
		$this->assertTrue($settings['Features']['attachment_enabled']['value']);
	}

	public function testLoadSettingsWithUserAndProjectSettings() {
		$settings = $this->Setting->loadConfigSettings(1, 'public');
		$this->assertEquals('spruce', $settings['UserInterface']['theme']['value']);
		$this->assertFalse($settings['Features']['attachment_enabled']['value']);
		$this->assertEquals('default', $settings['UserInterface']['terminology']['value']);
		$this->assertFalse($settings['Features']['source_enabled']['value']);
		$this->assertFalse(isset($settings['UserInterface']['goose']));
		$this->assertFalse(isset($settings['Features']['moose_enabled']));
	}

}
