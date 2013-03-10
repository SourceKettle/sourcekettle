<?php

/**
 *
 * AdminController Controller for the SourceKettle system
 * Provides the hard-graft overview of the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   SourceKettle Development Team 2012
 * @link        http://github.com/SourceKettle/sourcekettle
 * @package     SourceKettle.Controller
 * @since       SourceKettle v 0.1
 * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');

class AdminController extends AppController {

	public $useTable = false;

	public $uses = array('BackgroundDaemon');

	public $helpers = array(
		'GoogleChart.GoogleChart',
		'Time'
	);

/**
 * index method
 *
 * @return void
 */
	public function admin_index() {
		$this->set("daemonState", $this->BackgroundDaemon->checkDaemonRunning());
		$this->set("daemonData", $this->BackgroundDaemon->fetchDaemonData());
		$this->set("lastSshKeyRun", $this->BackgroundDaemon->lastRunOfType(1));
		$this->set("lastMonitorRun", $this->BackgroundDaemon->lastRunOfType(2));
	}

}
