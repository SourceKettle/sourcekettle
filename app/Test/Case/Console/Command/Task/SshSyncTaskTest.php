<?php

App::uses('ShellDispatcher', 'Console');
App::uses('ConsoleOutput', 'Console');
App::uses('ConsoleInput', 'Console');
App::uses('Shell', 'Console');
App::uses('SshSyncTask', 'Console/Command/Task');

class SshSyncTaskTest extends CakeTestCase {

/**
 * fixtures property
 *
 * @var array
 */
	public $fixtures = array('core.setting', 'core.ssh_key');

	public function setUp() {
		parent::setUp();
		$out = $this->getMock('ConsoleOutput', array(), array(), '', false);
		$in = $this->getMock('ConsoleInput', array(), array(), '', false);

		$this->Task = $this->getMock('SshSyncTask',
			array('in', 'err', 'createFile', '_stop', 'clear'),
			array($out, $out, $in)
		);
	}

	public function tearDown() {
		parent::tearDown();
		unset($this->Task);
	}

	public function testCron() {
		$this->assertEquals(1, $this->Task->cron());
	}

	public function testSingleton() {
		$this->assertEquals(true, SshSyncTask::$singleton);
	}

	public function testGetTaskId() {
		$this->assertEquals(1, $this->Task->getTaskId());
	}

	public function testBuildEmptyKeyString() {
		$this->assertEquals('', $this->Task->buildKeyString(array()));
	}

	public function testBuildSingleKeyString() {
		$keyContent = 'keyContent';
		$userId = '12';
		$keys = array(
			array(
				'SshKey' => array('key' => $keyContent),
				'User' => array('id' => $userId)
			)
		);
		$template = 'command="%s %s",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty %s';
		$cmd = APP . 'scm-scripts' . DS . 'git-serve.py';
		$expectedOutput = sprintf($template, $cmd, $userId, $keyContent) . "\n";
		$this->assertEquals($expectedOutput, $this->Task->buildKeyString($keys));
	}
}