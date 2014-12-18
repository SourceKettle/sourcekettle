<?php

/**
 * ProjectShell - admin commands to add/remove user accounts on the command line,
 * and to set/unset admin priveleges etc.
 *
 * @author amn@ecs.soton.ac.uk
 */
class ProjectShell extends AppShell {

	public $uses = array('User', 'Project', 'Collaborator', 'Security', 'Team', 'CollaboratingTeam');
	// TODO this should be centralised somewhere?
	private $accessLevels = array(
		'guest' => 0,
		'user'  => 1,
		'admin' => 2,
	);

	public function getOptionParser() {
		$parser = parent::getOptionParser();

		// TODO this is roughly copied from ProjectController, consolidate and move to RepoType model
		$this->repoTypes = $this->Project->RepoType->find('list');

		$this->repoTypes = array_map('strtolower', $this->repoTypes);
		$this->sourcekettle_config = $this->getSourcekettleConfig();

		if (isset($this->sourcekettle_config['SourceRepository']['default']['value'])) {
			$d = strtolower($this->sourcekettle_config['SourceRepository']['default']['value']);
		} else {
			$d = 'none';
		}

		if (in_array($d, $this->repoTypes)) {
			$defaultRepo = $d;
		} else {
			$defaultRepo = 'none';
		}
		// TODO end of copypasta

		$parser->addSubCommand('add', array(
			'help' => __('Create a new project'),
			'parser' => array(
				'description' => array(
					__("Use this command to add a new project to the system."),
				),
				'arguments' => array(
					'name' => array(
						'help' => __("The project name"),
						'required' => true,
					),
				),
				'options' => array(
					'description' => array(
						'short' => 'd',
						'help' => __("The longer project description"),
					),
					'repo-type' => array(
						'short' => 't',
						'help' => __("The type of SCM repository for the project"),
						'choices' => $this->repoTypes,
						'default' => $defaultRepo,
					),
					'is-public' => array(
						'short' => 'p',
						'help' => __("Is the project publically accessible?"),
						'boolean' => true,
						'default' => false,
					),
					'admins' => array(
						'short' => 'a',
						'help' => __("Comma-separated list of email address(es) of project administrator(s) (NB must provide at least one)"),
						'required' => true,
					),
					'users' => array(
						'short' => 'u',
						'help' => __("Comma-separated list of email address(es) of project user(s)"),
					),
					'guests' => array(
						'short' => 'g',
						'help' => __("Comma-separated list of email address(es) of project guest(s)"),
					),
				)
			)
		));


		$parser->addSubCommand('addCollaborator', array(
			'help' => __('Adds a user as a project collaborator'),
			'parser' => array(
				'description' => array(
					__("Use this command to add a user as a collaborator. If the user is already a collaborator, you can set their access level."),
				),
				'arguments' => array(
					'name' => array(
						'help' => __("The project name"),
						'required' => true,
					),
					'email' => array(
						'help' => __("The user's email address"),
						'required' => true,
					),
				),
				'options' => array(
					'type' => array(
						'short' => 't',
						'help' => __("The collaborator's access level"),
						'choices' => array('guest', 'user', 'admin'),
						'default' => 'user',
					),
				),
			),
		));

		$parser->addSubCommand('removeCollaborator', array(
			'help' => __('Removes a project collaborator (does not delete the user account)'),
			'parser' => array(
				'description' => array(
					__("Use this command to revoke a collaborator's access to a project."),
				),
				'arguments' => array(
					'name' => array(
						'help' => __("The project name"),
						'required' => true,
					),
					'email' => array(
						'help' => __("The user's email address"),
						'required' => true,
					),
				),
			),
		));

		$parser->addSubCommand('addTeam', array(
			'help' => __('Grants a team access as project collaborators'),
			'parser' => array(
				'description' => array(
					__("Use this command to add a team as collaborators. If the team is already collaborating, you can set their access level."),
				),
				'arguments' => array(
					'name' => array(
						'help' => __("The project name"),
						'required' => true,
					),
					'team' => array(
						'help' => __("The team name"),
						'required' => true,
					),
				),
				'options' => array(
					'type' => array(
						'short' => 't',
						'help' => __("The team's access level"),
						'choices' => array('guest', 'user', 'admin'),
						'default' => 'user',
					),
				),
			),
		));

		$parser->addSubCommand('removeTeam', array(
			'help' => __('Revokes a team\'s access to the project'),
			'parser' => array(
				'description' => array(
					__("Use this command to revoke a team's access to a project."),
				),
				'arguments' => array(
					'name' => array(
						'help' => __("The project name"),
						'required' => true,
					),
					'team' => array(
						'help' => __("The team name"),
						'required' => true,
					),
				),
			),
		));

		// Make this a lookup of name -> ID
		$this->repoTypes = array_flip($this->repoTypes);

		return $parser;
	}

/**
 * Adds a new project
 */
	public function add() {

		$name = $this->args[0];

		// Find existing project
		$found = $this->Project->findByName($name);

		// If it exists, error out
		if (!empty($found)) {
			$this->error(__("Found an existing project with that name!"));
		}

		$this->out(__("Creating a new project..."));

		if (!isset($this->params['admins']) || empty($this->params['admins'])) {
			$this->error(__("Please provide the email address of at least one project administrator"));
		}

		$this->params['guests'] = array_map('trim', split(',', @$this->params['guests']));
		$this->params['users']  = array_map('trim', split(',', @$this->params['users']));
		$this->params['admins'] = array_map('trim', split(',', @$this->params['admins']));

		$this->User->recursive = -1;
		$collaborators = array();

		foreach ($this->params['guests'] as $email) {
			if (empty($email)) {
				continue;
			}
			$user = $this->User->findByEmail($email);
			if (!$user) {
				$this->error("$email is not a recognised user account!");
			}
			$collaborators[$user['User']['id']] = array('user_id' => $user['User']['id'], 'access_level' => $this->accessLevels['guest']);
		}

		$users = array();
		foreach ($this->params['users'] as $email) {
			if (empty($email)) {
				continue;
			}
			$user = $this->User->findByEmail($email);
			if (!$user) {
				$this->error("$email is not a recognised user account!");
			}
			$collaborators[$user['User']['id']] = array('user_id' => $user['User']['id'], 'access_level' => $this->accessLevels['user']);
		}

		$admins = array();
		foreach ($this->params['admins'] as $email) {
			if (empty($email)) {
				continue;
			}
			$user = $this->User->findByEmail($email);
			if (!$user) {
				$this->error("$email is not a recognised user account!");
			}
			$collaborators[$user['User']['id']] = array('user_id' => $user['User']['id'], 'access_level' => $this->accessLevels['admin']);
		}


		$this->Project->create();

		$ok = $this->Project->saveAll(array(
			'Project' => array(
				'name'      => $name,
				'description' => @$this->params['description'],
				'public' => $this->params['is-public'],
				'repo_type' => $this->repoTypes[$this->params['repo-type']],
				'deleted'   => 0,
			),
			'Collaborator' => $collaborators
		// TODO disable callbacks to avoid permissions error - maybe find a better way to do this?
		), array('callbacks' => false));

		if (!$ok) {
			$this->error(__("Failed to create project '$name'!"));
		}

		if ($this->params['repo-type'] != 'none' && !$this->Project->Source->create()) {
			$this->Project->delete();
			$this->error(__("Failed to create repository for project '$name' :-("));
		}

		$project = $this->Project->findByName($name);
		$this->out(__("Project '$name' created with ID ".$project['Project']['id']));
		
	}

	public function addCollaborator() {

		$projectName = $this->args[0];
		$userEmail = $this->args[1];

		$this->User->recursive = -1;
		$projectDetails = $this->Project->findByName($projectName);
		$userDetails = $this->User->findByEmail($userEmail);

		// Convert 'user', 'guest' or 'admin' to access level integer
		$accessLevel = $this->accessLevels[$this->params['type']];

		if (!$projectDetails) {
			$this->error(__("Could not find a project named '$projectName'!"));
		}

		if (!$userDetails) {
			$this->error(__("Could not find a user with email address '$userEmail'!"));
		}

		$userId = $userDetails['User']['id'];

		// Check existing collaborator list
		foreach ($projectDetails['Collaborator'] as $collab) {

			// Don't need to do anything :-)
			if ($collab['user_id'] == $userId && $collab['access_level'] == $accessLevel) {
				$this->out(__("$userEmail is already a ".$this->params['type']." on the project."));
				return;

			// Found them, wrong status, stop checking
			} elseif ($collab['user_id'] == $userId) {
				$this->Collaborator->id = $collab['id'];
				break;
			}
		}

		// Need to add a new collaborator link
		$ok = $this->Collaborator->save(array('Collaborator' => array(
			'project_id' => $projectDetails['Project']['id'],
			'user_id'    => $userDetails['User']['id'],
			'access_level' => $accessLevel,
		)), array('callbacks' => false));

		if (!$ok) {
			$this->error(__("Failed to add collaborator!"));
		}

		$this->out(__("$userEmail is now a ".$this->params['type']." on $projectName"));
	}

	public function removeCollaborator() {

		$projectName = $this->args[0];
		$userEmail   = $this->args[1];

		$collabDetails = $this->Collaborator->find('first', array('conditions' => array(
			'User.email' => $userEmail,
			'Project.name' => $projectName,
		)));

		if (!$collabDetails) {
			$this->error(__("$userEmail is not a collaborator on $projectName!"));
		}
		$ok = $this->Collaborator->deleteAll(
			array('Collaborator.id' => $collabDetails['Collaborator']['id']),
		false, false);

		if (!$ok) {
			$this->error(__("Failed to remove collaborator!"));
		}

		$this->out(__("$userEmail is no longer collaborating on $projectName"));
	}

	public function addTeam() {

		$projectName = $this->args[0];
		$teamName = $this->args[1];

		$this->Team->recursive = -1;
		$projectDetails = $this->Project->findByName($projectName);
		$teamDetails = $this->Team->findByName($teamName);

		// Convert 'user', 'guest' or 'admin' to access level integer
		$accessLevel = $this->accessLevels[$this->params['type']];

		if (!$projectDetails) {
			$this->error(__("Could not find a project named '$projectName'!"));
		}

		if (!$teamDetails) {
			$this->error(__("Could not find a team named '$teamName'!"));
		}

		$teamId = $teamDetails['Team']['id'];

		// Check existing collaborator list
		foreach ($projectDetails['CollaboratingTeam'] as $collab) {

			// Don't need to do anything :-)
			if ($collab['team_id'] == $teamId && $collab['access_level'] == $accessLevel) {
				$this->out(__("$teamName already has ".$this->params['type']." access to the project."));
				return;

			// Found them, wrong status, stop checking
			} elseif ($collab['team_id'] == $teamId) {
				$this->CollaboratingTeam->id = $collab['id'];
				break;
			}
		}

		// Need to add a new collaborator link
		$ok = $this->CollaboratingTeam->save(array('CollaboratingTeam' => array(
			'project_id' => $projectDetails['Project']['id'],
			'team_id'    => $teamDetails['Team']['id'],
			'access_level' => $accessLevel,
		)), array('callbacks' => false));

		if (!$ok) {
			$this->error(__("Failed to add team!"));
		}

		$this->out(__("$teamName is now a ".$this->params['type']." on $projectName"));
	}

	public function removeTeam() {

		$projectName = $this->args[0];
		$teamName   = $this->args[1];

		$collabDetails = $this->CollaboratingTeam->find('first', array('conditions' => array(
			'Team.name' => $teamName,
			'Project.name' => $projectName,
		)));

		if (!$collabDetails) {
			$this->error(__("$teamName is not collaborating on $projectName!"));
		}
		$ok = $this->CollaboratingTeam->deleteAll(
			array('CollaboratingTeam.id' => $collabDetails['CollaboratingTeam']['id']),
		false, false);

		if (!$ok) {
			$this->error(__("Failed to remove collaborator!"));
		}

		$this->out(__("$teamName is no longer collaborating on $projectName"));
	}


}

