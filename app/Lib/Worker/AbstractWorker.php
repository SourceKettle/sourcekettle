<?php
/**
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * With thanks to http://developinginthedark.com/posts/beanstalk-queuing-in-cakephp
 *
 * @copyright   SourceKettle Development Team 2013
 * @link        http://github.com/SourceKettle/sourcekettle
 * @package     SourceKettle.Lib.Worker
 * @since       SourceKettle v 1.0
 * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Vendor', 'Beanstalk/Beanstalk');
App::import('Console/Command', 'AppShell');

abstract class AbstractWorker extends AppShell {

	var $config = array(
		'servers' => array('127.0.0.1:11300')
	);

	var $Beanstalk;

	var $name = 'Worker';

	function connect() {
		$this->_log('Connecting to beanstalk');
		$this->_log('Servers: '.implode(',', $this->config['servers']));
		$this->_log('Watching tube: '.$this->getTube());

		$connection = BeanStalk::open(array(
			'servers' => $this->config['servers'],
			'select' => 'random peek'
		));
		return $connection;
	}

	function execute($config = array()) {
		$this->config = array_merge($this->config, $config);

		$this->Beanstalk = $this->connect();
		$this->Beanstalk->watch($this->getTube());

		// run an initialisation for anything used by all job tasks
		$this->init();

		while(true) {
			// get latest job
			$this->_debug('Attempting to fetch a job.');
			$job = $this->Beanstalk->reserve_with_timeout(2);

			if(!$job) {
				$this->_debug('No job found. Sleeping.');
				$this->_rest(5);
			} else {
				// announce the job id being processed
				$job_id = $job->get_jid();
				$this->_log('Processing job '.$job_id);

				// retrieve the payload from the job
				$payload = unserialize($job->get());

				// process job, giving a result
				$result = $this->task($payload); // gives an array of the variabes in the job payload

				if($result) {
					// success = delete
					$job->delete();
					$this->_log('Success Job '.$job_id.'. Deleting.');
				} else {
					// failed = bury with low priority. 0 being high and lower priority as number gets higher
					$job->bury(1000);
					$this->_log('Failed Job '.$job_id.'. Burying.');
				}
			}
		}
	}

	public function getTube() {
		return 'default';
	}

	function init() { }

	function task($job) {
		return true;
	}

	protected function _debug($message) {
		if ($this->params['verbose']) {
			$this->_log($message);
		}
	}

	protected function _log($message) { }

	protected function _rest($timeToSleep = 20) {
		// Sometimes our sleep is inturrupted
		// This could be by a very noisy owl, or by a completed child process.
		while ($timeToSleep > 0) {
			$timeToSleep = sleep($timeToSleep);
		}
		clearstatcache();
	}
}
