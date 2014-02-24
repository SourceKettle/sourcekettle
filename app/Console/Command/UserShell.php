<?php


/**
 * UserShell - admin commands to add/remove user accounts on the command line,
 * and to set/unset admin priveleges etc.
 *
 * @author amn@ecs.soton.ac.uk
 */
class UserShell extends AppShell {

	public  $uses = array('User', 'SshKey', 'Project', 'Collaborator');

	public function getOptionParser() {
		$parser = parent::getOptionParser();

		// TODO: different options for different commands
		$parser->addOption('email', array(
			'short' => 'e',
			'email' => __("The user's email address"),
			'required' => true,
		))->addOption('name', array(
			'short' => 'n',
			'help'  => __("The full name of the user"),
			'required' => true,
		))->addOption('is_admin', array(
			'short' => 'a',
			'help' => __("The user account should be created as a system administrator"),
			'boolean'     => 'true',
		));

		return $parser;
	}
	/**
	 * Adds a user account
	 */
	public function add() {
		$this->out("Adding a user account");
	}


	/**
	 * Removes a user account
	 */
	public function delete(){
		$this->out("Removing a user account");
	}

	/**
	 * Promotes a normal user to sysadmin status
	 */
	public function promote() {
		$this->out("Promoting a normal user account to sysadmin");
	}

	/**
	 * Demotes a sysadmin to normal user status
	 */
	public function demote() {
		$this->out("Demoting a sysadmin to a normal user account");
	}

}

?>
