<?php
/**
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   SourceKettle Development Team 2013
 * @link        http://github.com/SourceKettle/sourcekettle
 * @package     SourceKettle.Console.Command.Task
 * @since       SourceKettle v 1.0
 * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('File', 'Utility');

class SshSyncTask extends Shell {

	public $responsibleFor = array('sync_keys');

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

	function execute($params = array()) {
		$coreConfig = Configure::read('devtrack');
		$gitHomedir = $this->getValidHomeDir($coreConfig);

		// Get all of the SSH keys from the database
		$keys = ClassRegistry::init('SshKey')->find('all');

		$keyString = $this->buildKeyString($keys);
		$this->__storeKeys($gitHomedir, $keyString);

		return true;
	}

	public function getValidHomeDir($coreConfig) {
		// Get username from config, and get info from the passwd file (or other entry)
		$gitUser = $coreConfig['repo']['user'];
		$gitDetails = posix_getpwnam($gitUser);

		// Sanity check #1, fail if the user doesn't exist...
		if(!$gitDetails){
			$this->err("Cannot sync keys - git user '$gitUser' does not exist - have you set up SourceKettle properly?");
			exit(1);
		}

		// Get the .ssh folder in the homedir
		$gitHomedir = $gitDetails['dir'] . DS . '.ssh';

		// Sanity check #2, make sure they have a .ssh directory - we *could* auto-create this, but I'd rather fail safe
		if(!is_dir($gitHomedir)){
			$this->err("Cannot sync keys - $gitHomedir not found - have you set up SourceKettle properly?");
			exit(1);
		}

		return $gitHomedir;
	}

	private function __storeKeys($gitHomedir, $keyString) {
		// Write to the file, making sure we get an exclusive lock to prevent corruption
		return file_put_contents($gitHomedir . DS . 'authorized_keys', $keyString, LOCK_EX);
	}
}
