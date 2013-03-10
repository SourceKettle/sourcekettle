<?php
/**
 *
 * TaskMetric task for the SourceKettle system
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

App::uses('DaemonRunner', 'CakeDaemon.Model');
App::uses('DaemonQueue', 'CakeDaemon.Model');
App::uses('BackgroundDaemon', 'Model');

class TaskMetricTask extends Shell {

	public static $taskName = 'TaskMetricTask';

	public static $singleton = true;

	/**
	 * cron function.
	 * Run 4 times a min
	 */
	public function cron() {
		return '15 seconds';
	}

	/**
	 * execute function.
	 * Collect the details of the currently running system and log them
	 *
	 * @param array $params (default: array())
	 * @return true if successful
	 */
	public function execute($params = array()) {
		$runners = DaemonRunner::sessionRead('runners');
		$queueLength = ClassRegistry::init('DaemonQueue')->find('count', array(
			'conditions' => array(
				'created <=' => date('Y-m-d H:i:s')
			),
		));

		$record = array(
			'date' => $params['DaemonQueue']['created'],
			'number_of_nodes' => count($runners) - 1,
			'running_nodes' => count(Set::extract($runners, '/DaemonRunner[job!=-1]/uuid')) - 1,
			'queue_length' => $queueLength - 1,
		);
		return ClassRegistry::init('BackgroundDaemon')->addLog($record);
	}

	/**
	 * getTaskId function.
	 * This is task ID 2
	 */
	public function getTaskId() {
		return 2;
	}
}
