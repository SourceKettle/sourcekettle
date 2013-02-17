<?php

class SshSyncTask extends Shell {

	const UPDATEKEYS = 1;

	public static $taskName = 'SshSync';

	public static $singleton = true;

	public function cron() {
		return 1;
	}

	public function execute($params = array()) {
		$this->log("Executing {$this->name}", "worker");
		return true;
	}

	public function getTaskId() {
		return 1;
	}
}
