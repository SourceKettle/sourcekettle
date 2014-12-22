<?php

/**
 * TeamShell - admin commands to add/remove teams on the command line,
 * and to add/remove team members.
 *
 * @author amn@ecs.soton.ac.uk
 */
class TeamShell extends AppShell {

	public $uses = array('Team', 'User', 'TeamsUser', 'Security', 'Team');

	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->addSubCommand('listTeams', array(
			'help' => __('Lists all teams'),
			'parser' => array(
				'description' => array(
					__("Use this command to get a list of all the teams in the system"),
				),
				'arguments' => array(
				),
				'options' => array(
				)
			)
		));
		$parser->addSubCommand('add', array(
			'help' => __('Create a new team'),
			'parser' => array(
				'description' => array(
					__("Use this command to add a new team to the system."),
				),
				'arguments' => array(
					'name' => array(
						'help' => __("The team name"),
						'required' => true,
					),
				),
				'options' => array(
					'description' => array(
						'short' => 'd',
						'help' => __("The longer teamdescription"),
					),
					'users' => array(
						'short' => 'p',
						'help' => __("Comma-separated list of names of users to add to the team"),
					),
				)
			)
		));

		$parser->addSubCommand('addUser', array(
			'help' => __('Adds a user to a team'),
			'parser' => array(
				'description' => array(
					__("Use this command to add a user to a team"),
				),
				'arguments' => array(
					'name' => array(
						'help' => __("The team name"),
						'required' => true,
					),
					'user' => array(
						'help' => __("The user name"),
						'required' => true,
					),
				),
			),
		));

		$parser->addSubCommand('removeUser', array(
			'help' => __('Remove a user from the team'),
			'parser' => array(
				'description' => array(
					__("Use this command to remove a user from a team"),
				),
				'arguments' => array(
					'name' => array(
						'help' => __("The team name"),
						'required' => true,
					),
					'user' => array(
						'help' => __("The user name"),
						'required' => true,
					),
				),
			),
		));
		return $parser;
	}

	public function listTeams() {
		$teams = $this->Team->find('list', array('fields' => array('name', 'description'), 'order' => array('name')));

		foreach ($teams as $name => $desc) {
			$this->out("$name [description: $desc]");
		}
	}
/**
 * Adds a new team
 */
	public function add() {

		$name = $this->args[0];

		// Find existing group
		$found = $this->Team->findByName($name);

		// If it exists, error out
		if (!empty($found)) {
			$this->error(__("Found an existing team with that name!"));
		}

		$this->out(__("Creating a new team..."));

		$this->params['users'] = array_map('trim', split(',', @$this->params['users']));

		$users = array();
		foreach ($this->params['users'] as $userEmail) {
			if (empty($userEmail)) {
				continue;
			}

			$user = $this->User->findByEmail($userEmail);
			if (!$user) {
				$this->error("$userEmail is not a recognised user!");
			}
			$users[$user['User']['id']] = array('user_id' => $user['User']['id']);
		}

		$this->Team->create();

		$ok = $this->Team->saveAll(array(
			'Team' => array(
				'name'      => $name,
				'description' => @$this->params['description'],
				'deleted'   => 0,
			),
			'User' => $users
		));

		if (!$ok) {
			$this->error(__("Failed to create team '$name'!"));
		}

		$user = $this->Team->findByName($name);
		$this->out(__("Team '$name' created with ID ".$user['Team']['id']));
		
	}

	public function addUser() {

		$teamName = $this->args[0];
		$userEmail = $this->args[1];

		$this->User->recursive = -1;
		$teamDetails = $this->Team->findByName($teamName);
		$userDetails = $this->User->findByEmail($userEmail);

		if (!$teamDetails) {
			$this->error(__("Could not find a team named '$teamName'!"));
		}

		if (!$userDetails) {
			$this->error(__("Could not find a user named '$userEmail'!"));
		}

		$teamId = $teamDetails['Team']['id'];

		// Check existing collaborator list
		foreach ($teamDetails['User'] as $member) {

			// Don't need to do anything :-)
			if ($member['id'] == $userDetails['User']['id']) {
				$this->out(__("$userEmail is already a member of the group $teamName."));
				return;
			}
		}

		$ok = $this->TeamsUser->save(array('TeamsUser' => array(
			'team_id' => $teamDetails['Team']['id'],
			'user_id' => $userDetails['User']['id'],
		)));

		if (!$ok) {
			$this->error(__("Failed to add $userEmail to $teamName!"));
		}

		$this->out(__("$userEmail is now a member of $teamName"));
	}

	public function removeUser() {

		$teamName = $this->args[0];
		$userEmail   = $this->args[1];

		$this->User->recursive = -1;
		$teamDetails = $this->Team->findByName($teamName);
		$userDetails = $this->User->findByEmail($userEmail);

		if (!$teamDetails) {
			$this->error(__("Could not find a team named '$teamName'!"));
			return;
		}

		if (!$userDetails) {
			$this->error(__("Could not find a user named '$userEmail'!"));
			return;
		}

		$memberDetails = $this->TeamsUser->findByTeamIdAndUserId($teamDetails['Team']['id'], $userDetails['User']['id']);
		if (!$memberDetails) {
			$this->out(__("$userEmail is not a member of $teamName"));
			return;
		}
		
		$ok = $this->TeamsUser->deleteAll(
			array('TeamsUser.id' => $memberDetails['TeamsUser']['id']),
		false, false);

		if (!$ok) {
			$this->error(__("Failed to remove $userEmail from $teamName"));
			return;
		}

		$this->out(__("$userEmail is no longer a member of $teamName"));
	}

}

