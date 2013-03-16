<?php
/**
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   SourceKettle Development Team 2013
 * @link        http://github.com/SourceKettle/sourcekettle
 * @package     SourceKettle.Console.Command
 * @since       SourceKettle v 1.0
 * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class GitShell extends AppShell {

	public $uses = array('SshKey', 'Project', 'Collaborator');

	public $tasks = array('SshSync');

	// Git read and write commands, for checking access permissions
	private $read_commands  = array('git-upload-pack',  'git upload-pack');
	private $write_commands = array('git-receive-pack', 'git receive-pack');

	public function main() {
		$this->out("You need to specify a command. Try 'sync_keys' or 'serve'.");
	}

	/**
	 * Syncs all of the SSH keys to the git user's authorized_keys file to allow for ssh access
	 */
	public function sync_keys() {
		$this->SshSync->execute();
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
		// * The authorized_keys file generated by the function above ensures that a very
		//   specific command is run when that key is used - git-serve.py {userid associated with key}
		// * The git-serve.py script does some sanity checking and runs this command via CakePHP,
		//   passing it the user ID as an argument - the SSH_ORIGINAL_COMMAND environment var is
		//   available as we're being run via SSH
		// * This command does a LOT of sanity and permission checks, then returns the validated command
		//   for the git user to run
		// * git-serve.py then runs that command and the remote git program starts pulling/pushing data
		//
		// ... got it?


		// Firstly, get the SSH_ORIGINAL_COMMAND and other useful variables from environment
		$vars = array_merge($_SERVER, $_ENV);

		if (!isset($vars['SSH_ORIGINAL_COMMAND']) or !isset($vars['argv'])) {
			$this->err("Error: Required environment variables are not defined");
			exit(1);
		}

		$ssh_original_command = $vars['SSH_ORIGINAL_COMMAND'];
		$argv   = $vars['argv'];
		$userid = array_pop($argv);

		// User ID must be numeric and greater than zero
		if (!preg_match('/^\s*(\d+)\s*$/', $userid, $matches)) {
			$this->err("Error: You do not have the necessary permissions");
			exit(1);
		}
		$userid = $matches[1];

		if ($userid <= 0) {
			$this->err("Error: You do not have the necessary permissions");
			exit(1);
		}

		// Secondly, validate the arguments and get the command into a generic format

		// Check if SSH_ORIGINAL_COMMAND contains newlines and bomb out early (nice easy sanity check)
		if (strpos($ssh_original_command, "\n") !== false) { //!=== as it may also return non-boolean values that evaluate to false
			$this->err("Error: SSH_ORIGINAL_COMMAND contains newlines");
			exit(1);
		}

		// If it's a valid git command it will look something like:
		// git-receive-pack 'projects/fnord.git'
		// or:
		// git receive-pack 'projects/fnord.git'

		// Match both forms; command will be in $1, command args will be in $3
		// Bomb out if the command doesn't match
		if (!preg_match('/^\s*(git(\s+|\-)\S+)\s+(.+)$/', $ssh_original_command, $matches)) {
			$this->err("Error: Command is not a valid git command");
			exit(1);
		}

		// Make sure it's in the git-receive-pack format, not git receive-pack
		$command = preg_replace('/\s+/', '-', $matches[1]);

		// Remove any quotes around the command arguments (go go gadget irregular expressions!)
		$args	= preg_replace('/^(\'|\")(.+)\\1/', '\\2', $matches[3]);


		// Check if it's a valid git command to start with...
		if (!$this->isValidGitCommand($command)) {
			$this->err("Error: Unknown command");
			exit(1);
		}

		// Now check that they've given us a valid repo name
		// Should look something like:
		// projects/fnord.git
		// ...but actually, we'll just throw away the entire path except for the last part,
		// then make sure it's a valid unix username with '.git' on the end.
		// NB project names are always valid unix usernames
		if (!preg_match('#^(.*/)?([a-zA-Z0-9][a-zA-Z0-9@._-]*).git$#', $args, $matches)) {
			$this->err("Error: Malformed repository name");
			exit(1);
		}

		$_proj_name = $matches[2];



		// Try and get project info, if it doesn't exist then don't give that fact away...
		$project = $this->Project->getProject($_proj_name, true);
		if (empty ($project)){
			$this->err("Error: You do not have the necessary permissions");
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
		$devtrack_config = Configure::read('devtrack');
		$repo_path = $devtrack_config['repo']['base'] . "/$_proj_name.git";

		// Make sure there's actually a git repository for this project...
		if (strtolower($rt['RepoType']['name']) != 'git' or !is_dir($repo_path)) {
			$this->err("Error: You do not have the necessary permissions");
			exit(1);
		}

		//Now check if the user has the correct permissions for the operation they are trying to perform

		// Read requested and they have permission, serve the request
		if ($this->isReadCommand($command) and ($this->Project->hasRead($userid))) {
			print "$command '$repo_path'";
			exit(0);

		// Write requested and they have permission, serve the request
		} else if ($this->isWriteCommand($command) and ($this->Project->hasWrite($userid))) {
			print "$command '$repo_path'";
			exit(0);

		// They do not have permission
		} else {
			$this->err("Error: You do not have the necessary permissions");
			exit(1);
		}

	}

	/**
	* Override the default welcome. We do not want to print the welcome message as this breaks git, so do nothing
	*/
	protected function _welcome(){

	}

}

?>
