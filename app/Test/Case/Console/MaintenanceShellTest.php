<?php

require_once(__DIR__ . DS . 'AppShellTest.php');

App::uses('AppShell', 'Console/Command');
App::uses('MaintenanceShell', 'Console/Command');
App::uses('Controller', 'Controller');

class MaintenanceShellTestCase extends AppShellTestCase {
	public $fixtures = array(
		'app.User',
		'app.LostPasswordKey',
		'app.EmailConfirmationKey',
		'app.Setting',
		'app.UserSetting',
		'app.ProjectSetting',
	);

	public function setUp() {
		parent::setUp();
        	$this->Shell = new MaintenanceShell();
	}

	public function tearDown() {
		parent::tearDown();
		unset($this->Shell);
	}

	public function testCleanupKeys() {

		$this->Shell->cleanupKeys();

		$lpKeys = $this->Shell->LostPasswordKey->find('list');
		$ecKeys = $this->Shell->EmailConfirmationKey->find('list');

		$this->assertEquals(array(2 => 2), $lpKeys);

		$this->assertEquals(array(), $ecKeys);
	}
}
