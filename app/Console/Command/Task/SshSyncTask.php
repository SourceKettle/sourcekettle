<?php

App::uses('File', 'Utility');

class SshSyncTask extends Shell {

	const UPDATEKEYS = 1;

	public static $taskName = 'SshSync';

	public static $singleton = true;

	public function buildKeyString($keys = array()) {
		// We will auto-run our git serve command when the git user logs in, and we should disable any
		// features that may be used for nefarious purposes
		$template = 'command="%s %s",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty %s';

		// This is the git-serve command that will be run when git logs in
		$cmd = APP . 'scm-scripts' . DS . 'git-serve.py';

		// Build up a list of SSH keys to write to file
		// NOTE - very small risk of memory exhaustion, it'd take a huge number of keys though...
		$out = "#\n";
		$out .= "# This file is maintained by SourceKettle\n";
		$out .= "# Please refer to the manual\n";
		$out .= "#\n";

		foreach ($keys as $key) {
			$sshkey = $key['SshKey']['key'];
			$userid = $key['User']['id'];

			$keyContent = trim(str_replace(array("\n", "\r"), '', $sshkey));
			$out .= sprintf($template, $cmd, $userid, $keyContent) . "\n";
		}
		return $out;
	}

	public function cron() {
		return '30 seconds';
	}

	public function execute($params = array()) {
		$this->Setting = ClassRegistry::init('Setting');

		// Don't bother unless a key's actually been changed...
		$syncRequired = $this->Setting->find('first', array('conditions' => array('name' => 'sync_required')));
		if ($syncRequired['Setting']['value'] == 1) {
			$coreConfig = Configure::read('devtrack');
			$gitHomedir = $this->getValidHomeDir($coreConfig);

			// Get all of the SSH keys from the database
			$keys = ClassRegistry::init('SshKey')->find('all');

			$keyString = $this->buildKeyString($keys);
			$this->__storeKeys($gitHomedir, $keyString);

			// Don't sync again unless keys have changed
			$syncRequired['Setting']['value'] = 0;
			$this->Setting->save($syncRequired);
		}
		return true;
	}

	public function getTaskId() {
		return 1;
	}

	public function getValidHomeDir($coreConfig) {
		// Get username from config, and get info from the passwd file (or other entry)
		$gitUser = $coreConfig['repo']['user'];
		$gitDetails = posix_getpwnam($gitUser);

		// Sanity check #1, fail if the user doesn't exist...
		if(!$gitDetails){
			$this->err("Cannot sync keys - git user '$gitUser' does not exist - have you set up DevTrack properly?");
			exit(1);
		}

		// Get the .ssh folder in the homedir
		$gitHomedir = $gitDetails['dir'] . DS . '.ssh';

		// Sanity check #2, make sure they have a .ssh directory - we *could* auto-create this, but I'd rather fail safe
		if(!is_dir($gitHomedir)){
			$this->err("Cannot sync keys - $gitHomedir not found - have you set up DevTrack properly?");
			exit(1);
		}

		return $gitHomedir;
	}

	private function __storeKeys($gitHomedir, $keyString) {
		// Write to the file, making sure we get an exclusive lock to prevent corruption
		return file_put_contents($gitHomedir . DS . 'authorized_keys', $keyString, LOCK_EX);
	}
}
