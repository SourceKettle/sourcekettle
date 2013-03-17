<?php
/**
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2013
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.Console.Command
 * @since         SourceKettle v 1.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AbstractWorker', 'Worker');
App::import('Vendor', 'SystemDaemon', array('file' => 'SystemDaemon'.DS.'System'.DS.'Daemon.php'));

class WorkerShell extends AbstractWorker {

	public $tasks = array('SshSync');

	private $__childPid = array();

	private $__numOfWorkers = 2;

	var $name = 'Commander';

/**
 * getOptionParser function.
 * @see http://book.cakephp.org/2.0/en/console-and-shells.html
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->addSubcommand('start', array(
			'help' => __('Start the Workers.')
		))->addOption('no-daemon', array(
			'boolean' => true,
			'help' => __('Prevent background forking')
		));
		$parser->addSubcommand('stop', array(
			'help' => __('Stop the Workers.')
		));
		$parser->addSubcommand('status', array(
			'help' => __('Check the state of the Workers.')
		));
		return $parser;
	}

	public function getTube() {
		return 'sourcekettle';
	}

/**
 * handleSIGCHLD function.
 * Handle any finished children
 *
 * @param mixed $signo
 */
	public function handleSIGCHLD($signo) {
		//TODO handle the death of a child
	}

/**
 * stop function.
 * Force stop the running instance.
 */
	public function stop() {
		// Setup
		$options = array(
			'appName' => 'sourcekettle',
			'logLocation' => TMP . "logs" . DS . 'sourcekettle.log',
		);

		System_Daemon::setOptions($options);
		System_Daemon::stopRunning();
	}

/**
 * start function.
 * Start an instance of the worker.
 */
	public function start() {
		$this->out('Starting Commander...');

		// Setup
		$options = array(
			'appName' => 'sourcekettle',
			'appDescription' => 'Runs extended tasks defined in the host app in the background',
			'sysMaxExecutionTime' => '0',
			'sysMaxInputTime' => '0',
			'sysMemoryLimit' => '1024M',
			'logLocation' => TMP . "logs" . DS . 'sourcekettle.log',
			'logVerbosity' => System_Daemon::LOG_INFO
		);
		System_Daemon::setOptions($options);
		System_Daemon::setSigHandler('SIGCHLD', array(&$this, 'handleSIGCHLD'));
		// TODO Handle the hang up signal

		// This program can also be run in the forground with argument no-daemon
		if (!$this->params['no-daemon']) {
			// Spawn Daemon
			System_Daemon::start();
		}

		$this->_log('Starting ' . $this->__numOfWorkers . ' worker(s)');
		for($i = 1; $i <= $this->__numOfWorkers; $i++) {
			$this->__childPid[$i] = $this->__spawnWorker($i);
			sleep(1);
		}

		// Here we have to do several things
		// 1) Check that all our children are still ok
		// 2) Sleep - ZZzzz
		while (!System_Daemon::isDying()) {
			$this->_log('Running periodic checks');
			//TODO Check that all our children are still ok

			$this->_rest();
		}

		System_Daemon::stop();
	}

/**
 * status function.
 * Check the status of the daemon
 */
	public function status() {
		// Setup
		$options = array(
			'appName' => 'sourcekettle',
			'logLocation' => TMP . "logs" . DS . 'sourcekettle.log',
		);
		System_Daemon::setOptions($options);

		if (System_Daemon::isRunning() == true) {
			$this->out('Daemon is running');
			exit(0);
		} else {
			$this->out('Daemon is not running');
			exit(1);
		}
	}

	function task($job) {
		if (!isset($job['task'])) {
			return false;
		}

		foreach ($this->tasks as $task) {
			$task = $this->{$task};
			foreach ($task->responsibleFor as $responsibility) {
				if ($responsibility == $job['task']) {
					return $task->execute($job);
				}
			}
		}
		return false;
	}

	private function __spawnWorker($num) {
		$pid = pcntl_fork();

		if ($pid == -1) {
			return false;
		} else if ($pid) {
			$this->_log('Started Worker ' . $num);
			return true;
		} else {
			$this->name = 'Worker ' . $num;
			$this->execute();
			return true;
		}
	}

	protected function _log($message) {
		$this->log($this->name . ' -> ' . $message, 'sourcekettle');
	}

/**
 * Override the default welcome.
 */
	protected function _welcome(){
	}

}
