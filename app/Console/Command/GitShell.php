<?php


/**
 * Description of GitShell
 *
 * @author Chris
 */
class GitShell extends AppShell {

	public  $uses = array('SshKey', 'Project', 'Collaborator', 'Setting');

	// Git read and write commands, for checking access permissions
	private $read_commands  = array('git-upload-pack',  'git upload-pack');
	private $write_commands = array('git-receive-pack', 'git receive-pack');

	/*public function __construct($stdout = null, $stderr = null, $stdin = null) {
		Configure::write('Cache.disable', true);
		parent::__construct($stdout, $stderr, $stdin);
	}*/

	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->addSubCommand('authorizedKeys', array(
			'help' => __('Prints out authorized SSH public keys for SourceKettle git access via SSH'),
			'parser' => array(
				'description' => array(
					__('This command can be called by sshd using the AuthorizedKeysCommand option'),
				),
				'arguments' => array(
					'username' => array(
						'help' => __('The username to sync keys for - NB to work nicely with sshd, this command will only take effect if the git username is supplied, or with no username.'),
						'required' => false,
					),
				),
				'options' => array(
					'sync' => array(
						'help' => __('Sync the real authorized_keys file. This will be used by default if you cannot use AuthorizedKeysCommand. Needs to be called on cron.'),
						'boolean' => true,
					),
				),
			),
		));

		return $parser;
	}

	public function main() {
		$this->out("You need to specify a command. Try 'authorizedKeys' or 'serve'.");
	}

	// Generates an authorized_keys file from the SSH keys in the database.
	// Can be used directly from sshd using the AuthorizedKeysCommand option,
	// and can be periodically called from cron to sync the real authorized_keys file.
	public function authorizedKeys() {

		$sourcekettleConfig = $this->getSourceKettleConfig();

		// sshd will give us a username, we will sanity check that it's the git user
		// We need to print nothing if it's for another user on the system
		if (count($this->args) > 0) {
			
			$requestedUser = $this->args[0];

			if ($requestedUser !== $sourcekettleConfig['SourceRepository']['user']['value']) {
				exit(0);
			}
		// No username given (e.g. we're syncing the real keys file), default to git user
		} else {
			$requestedUser = $sourcekettleConfig['SourceRepository']['user']['value'];
		}

		// If we've been given the --sync option,
		// we need to make sure the git user's home directory and .ssh dir exist etc.
		$sync = $this->params['sync'];
		
		if ($sync) {

			// No need to sync if no keys have changed
			if ($sourcekettleConfig['Status']['sync_required']['value'] != 1) {
				exit(0);
			}

			$requestedUser = $sourcekettleConfig['SourceRepository']['user']['value'];
			$gitDetails = posix_getpwnam($requestedUser);

			// Sanity check #1, fail if the user doesn't exist...
			if(!$gitDetails){
				$this->err(__("Cannot sync keys - git user '$requestedUser' does not exist - have you set up SourceKettle properly?"));
				exit(1);
			}

			// Get their homedir
			$gitHomedir = $gitDetails['dir'];
	
			// Sanity check #2, make sure they have a .ssh directory - we *could* auto-create this, but I'd rather fail safe
			if(!is_dir($gitHomedir.'/.ssh')){
				$this->err(__("Cannot sync keys - $gitHomedir/.ssh not found - have you set up SourceKettle properly?"));
				exit(1);
			}
	
			// Now we know where to write to...
			$sshKeyfile = $gitHomedir.'/.ssh/authorized_keys';

			// Sigh, flock() isn't ideal for this... open for append to avoid
			// zeroing out the existing file if it's locked...
			$outfile = fopen($sshKeyfile, "a");

			// Attempt to get a write lock
			if(!flock($outfile,LOCK_EX|LOCK_NB)) {
				fclose($outfile);
				$this->error("Failed to lock $sshKeyfile!");
				exit(0);
			}

			// We have the lock, zero out the file
			if (!ftruncate($outfile, 0)) {
				flock($outfile,LOCK_UN);
				fclose($outfile);
				$this->error("Failed to overwrite $sshKeyfile!");
				exit(0);
			}
		}

		// We will auto-run out git serve command when the git user logs in, and we should disable any
		// features that may be used for nefarious purposes
		$template = 'command="%s %s",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty %s'."\n";

		// This is the git-serve command that will be run when git logs in
		// CAKE/Console/cake is the cakephp console command, APP is our application dir, and git serve is the sourcekettle console command
		$cmd = CAKE . 'Console' . DS . 'cake -app \'' . APP . '\'' . ' git serve';

		// Now list all SSH keys
		foreach ($this->SshKey->find('all') as $key) {
			$sshkey = $key['SshKey']['key'];
			$userid = $key['User']['id'];

			// Sanity check the user ID
			if (!isset($userid) || $userid <= 0) {
				continue;
			}

			// Sanity check the key
			if (strlen($sshkey) <= 40) {
				continue;
			}

			// Remove newlines if they've added any
			$content = trim(str_replace(array("\n", "\r"), '', $sshkey));
		
			// ...and print the key + command
			if ($sync) {
				fprintf($outfile, $template, $cmd, $userid, $content);
			} else {
				printf($template, $cmd, $userid, $content);
			}
		}
		
		if ($sync) {

			// Release the hounds^Wlock
			flock($outfile,LOCK_UN);
			fclose($outfile);

			// Make sure it's only readable/writable by the git user
			chmod($sshKeyfile, 0600);
			chown($sshKeyfile, $requestedUser);

			// Don't sync again unless keys have changed
			$this->Setting->syncRequired(false);
		}
	}

	/**
	 * Syncs all of the SSH keys to the git user's authorized_keys file to allow for ssh access
	 */
	public function sync_keys() {
		$this->runCommand("authorizedKeys", array("authorizedKeys", "--sync"));
		exit(0);
	}

	// Helper functions to validate git commands
	private function isReadCommand($command){
		return in_array($command, $this->read_commands);
	}
	private function isWriteCommand($command){
		return in_array($command, $this->write_commands);
	}
	private function isValidGitCommand($command){
		return ($this->isReadCommand($command) or $this->isWriteCommand($command));
	}

	public function serve() {
	
		// Some background info on how this function is called...
		// * User logs in using an SSH key
		// * The authorized_keys file generated by the function above ensures that this command
		//   is called with one argument, the user ID associated with the key
		// * This command does a LOT of sanity and permission checks, and runs the original
		//   git command (stored in SSH_ORIGINAL_COMMAND)
		//


		// Firstly, get the SSH_ORIGINAL_COMMAND and other useful variables from environment
		$vars = array_merge($_SERVER, $_ENV);

		if (!isset($vars['SSH_ORIGINAL_COMMAND']) or !isset($vars['argv'])) {
			$this->err(__("Error: Required environment variables are not defined. Please contact the system administrator."));
			exit(1);
		}

		$ssh_original_command = $vars['SSH_ORIGINAL_COMMAND']; 
		$argv   = $vars['argv'];
		$userid = array_pop($argv);

		// User ID must be numeric and greater than zero
		if (!preg_match('/^\s*(\d+)\s*$/', $userid, $matches)) {
			$this->err(__("Error: You do not have the necessary permissions to access this git repository."));
			exit(1);
		}
		$userid = $matches[1];

		if ($userid <= 0) {
			$this->err(__("Error: You do not have the necessary permissions to access this git repository."));
			exit(1);
		}

		// Secondly, validate the arguments and get the command into a generic format

		// Check if SSH_ORIGINAL_COMMAND contains newlines and bomb out early (nice easy sanity check)
		if (strpos($ssh_original_command, "\n") !== false) { //!=== as it may also return non-boolean values that evaluate to false
			$this->err(__("Error: invalid git command (are you actually connecting using git?)"));
			exit(1);
		}
		
		// If it's a valid git command it will look something like:
		// git-receive-pack 'projects/fnord.git'
		// or:
		// git receive-pack 'projects/fnord.git'

		// Match both forms; command will be in $1, command args will be in $3
		// Bomb out if the command doesn't match
		if (!preg_match('/^\s*(git(\s+|\-)\S+)\s+(.+)$/', $ssh_original_command, $matches)) {
			$this->err(__("Error: invalid git command (are you actually connecting using git?)"));
			exit(1);
		}

		// Make sure it's in the git-receive-pack format, not git receive-pack
		$command = preg_replace('/\s+/', '-', $matches[1]);

		// Remove any quotes around the command arguments (go go gadget irregular expressions!)
		$args	= preg_replace('/^(\'|\")(.+)\\1/', '\\2', $matches[3]);

		// Check if it's a valid git command to start with...
		if (!$this->isValidGitCommand($command)) {
			$this->err(__("Error: invalid git command (are you actually connecting using git?)"));
			exit(1);
		}

		// Now check that they've given us a valid repo name
		// Should look something like:
		// projects/fnord.git
		// ...but actually, we'll just throw away the entire path except for the last part,
		// then make sure it's a valid unix username with '.git' on the end.
		// NB project names are always valid unix usernames
		if (!preg_match('#^(.*/)?([a-zA-Z0-9][a-zA-Z0-9@._-]*).git$#', $args, $matches)) {
			$this->err(__("Error: Malformed repository name (are you actually connecting using git?)"));
			exit(1);
		}

		$_proj_name = $matches[2];
		
		// Try and get project info, if it doesn't exist then don't give that fact away...
		$project = $this->Project->getProject($_proj_name, true);
		if (empty ($project)){
			$this->err(__("Error: You do not have the necessary permissions to access this git repository."));
			exit(1);
		}

		// We don't need to set all the details, just the ID so we can call hasRead/hasWrite
		$this->Project->id = $project['Project']['id'];

		$rt = $this->Project->RepoType->find(
	 		'first', array(
			'conditions' => array('RepoType.id' => $project['Project']['repo_type']),
			'recursive'  => -1
		));

		// Get the repository location
		$sourcekettle_config = $this->getSourceKettleConfig();
		$repo_path = $sourcekettle_config['SourceRepository']['base']['value'] . "/$_proj_name.git";

		// Make sure there's actually a git repository for this project...
		if (strtolower($rt['RepoType']['name']) != 'git' or !is_dir($repo_path)) {
			$this->err(__("Error: You do not have the necessary permissions to access this git repository."));
			exit(1);
		}

		// We already know the command is valid, so it's either a read or a write command...

		// Check read permission
		if ($this->isReadCommand($command) and !$this->Project->hasRead($userid)) {
			$this->err(__("Error: You do not have the necessary permissions to access this git repository."));
			exit(1);

		// Check write permission
		} else if ($this->isWriteCommand($command) and !$this->Project->hasWrite($userid)) {
			$this->err(__("Error: You do not have the necessary permissions to access this git repository."));
			exit(1);

		}

		// Sanity checks complete. Pass through to the git command.
		passthru("$command ".escapeshellarg($repo_path));
	}

	/**
	* Override the default welcome. We do not want to print the welcome message as this breaks git, so do nothing
	*/
	protected function _welcome(){

	}

}

?>
