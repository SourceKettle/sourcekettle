<?php
/**
 *
 * BackgroundDaemon model for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.Model
 * @since         SourceKettle v 1.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppModel', 'Model');
App::import('Model', 'CakeDaemon.DaemonQueue');

class BackgroundDaemon extends AppModel {

	public $useTable = 'daemon_history';

	// TODO remove this static PID definition
	private $__pidLocation = "/var/run/cakedaemon/cakedaemon.pid";

	public function addLog($object) {
		return ($this->save($object) && $this->deleteAll(array('date <' => date('Y-m-d H:i:s', strtotime('-10 minutes')))));
	}

	/**
	 * checkDaemonRunning function.
	 * Check if the background daemon is running
	 *
	 * @return true if the daemon is running
	 */
	public function checkDaemonRunning() {
		if (!file_exists($this->__pidLocation)) {
			return false;
		}

		$f = fopen($this->__pidLocation, 'r');
		if (!$f) {
			return false;
		}
		$pid = fread($f, filesize($this->__pidLocation));
		fclose($f);

		if (!$pid) {
			return false;
		}

		exec('ps ' . intval($pid), $out, $ret);

		return ($ret == 0);
	}

	/**
	 * fetchDaemonData function.
	 * Retrieve history for the background daemon
	 *
	 * @return array of information
	 */
	public function fetchDaemonData() {
		$allData = $this->find('all', array(
			'order' => array('date' => 'ASC')
		));

		$data['numNodes'] = Set::extract($allData, '/BackgroundDaemon/number_of_nodes');
		$data['runningNodes'] = Set::extract($allData, '/BackgroundDaemon/running_nodes');
		$data['queueLength'] = Set::extract($allData, '/BackgroundDaemon/queue_length');

		$dates = Set::extract($allData, '/BackgroundDaemon/date');
		$data['firstTime'] = (isset($dates[0])) ? date('H:i', strtotime($dates[0])): '00:00';
		$data['lastTime'] = (isset($dates[0])) ? date('H:i', strtotime($dates[count($dates)-1])) : '00:00';
		$data['maxNodes'] = (isset($data['numNodes'][0])) ? max($data['numNodes']) : 0;

		return $data;
	}

	/**
	 * lastRunOfType function.
	 * Check when the last run of a task type was
	 *
	 * @param mixed $type
	 * @return string of date
	 */
	public function lastRunOfType($type) {
		$this->DaemonQueue = ClassRegistry::init('DaemonQueue');
		$lastRun = $this->DaemonQueue->find('first', array('conditions' => array('task' => $type)));
		return $lastRun['DaemonQueue']['created'];
	}
}
