<?php

/**
 * ProjectGroupShell - admin commands to add/remove user accounts on the command line,
 * and to set/unset admin priveleges etc.
 *
 * @author amn@ecs.soton.ac.uk
 */
class ProjectGroupShell extends AppShell {

	public $uses = array('ProjectGroup', 'Project', 'ProjectGroupsProject', 'Security', 'Team', 'GroupCollaboratingTeam');
	// TODO this should be centralised somewhere?
	private $accessLevels = array(
		'guest' => 0,
		'user'  => 1,
		'admin' => 2,
	);

	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->addSubCommand('listGroups', array(
			'help' => __('Lists all project groups'),
			'parser' => array(
				'description' => array(
					__("Use this command to get a list of all the project groups in the system"),
				),
				'arguments' => array(
				),
				'options' => array(
				)
			)
		));

		$parser->addSubCommand('add', array(
			'help' => __('Create a new project group'),
			'parser' => array(
				'description' => array(
					__("Use this command to add a new project group to the system."),
				),
				'arguments' => array(
					'name' => array(
						'help' => __("The project group name"),
						'required' => true,
					),
				),
				'options' => array(
					'description' => array(
						'short' => 'd',
						'help' => __("The longer project description"),
					),
					'projects' => array(
						'short' => 'p',
						'help' => __("Comma-separated list of names of projects to add to the group"),
					),
				)
			)
		));

		$parser->addSubCommand('addProject', array(
			'help' => __('Adds a project to the project group'),
			'parser' => array(
				'description' => array(
					__("Use this command to add a project to the project group"),
				),
				'arguments' => array(
					'name' => array(
						'help' => __("The project group name"),
						'required' => true,
					),
					'project' => array(
						'help' => __("The project name"),
						'required' => true,
					),
				),
			),
		));

		$parser->addSubCommand('removeProject', array(
			'help' => __('Remove a project from the project group'),
			'parser' => array(
				'description' => array(
					__("Use this command to remove a project from a project group"),
				),
				'arguments' => array(
					'name' => array(
						'help' => __("The project group name"),
						'required' => true,
					),
					'project' => array(
						'help' => __("The project name"),
						'required' => true,
					),
				),
			),
		));
		$parser->addSubCommand('addTeam', array(
			'help' => __('Grants a team access to all projects in the group as collaborators'),
			'parser' => array(
				'description' => array(
					__("Use this command to add a team as collaborators. If the team is already collaborating, you can set their access level."),
				),
				'arguments' => array(
					'name' => array(
						'help' => __("The project group name"),
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
			'help' => __('Revokes a team\'s access to the project group'),
			'parser' => array(
				'description' => array(
					__("Use this command to revoke a team's access to a project group."),
				),
				'arguments' => array(
					'name' => array(
						'help' => __("The project group name"),
						'required' => true,
					),
					'team' => array(
						'help' => __("The team name"),
						'required' => true,
					),
				),
			),
		));

		return $parser;
	}

	public function listGroups() {
		$groups = $this->ProjectGroup->find('list', array('fields' => array('name', 'description'), 'order' => array('name')));

		foreach ($groups as $name => $desc) {
			$this->out("$name [description: $desc]");
		}
	}

/**
 * Adds a new project group
 */
	public function add() {

		$name = $this->args[0];

		// Find existing group
		$found = $this->ProjectGroup->findByName($name);

		// If it exists, error out
		if (!empty($found)) {
			$this->error(__("Found an existing project group with that name!"));
		}

		$this->out(__("Creating a new project group..."));

		$this->params['projects'] = array_map('trim', split(',', @$this->params['projects']));

		$projects = array();
		foreach ($this->params['projects'] as $projectName) {
			if (empty($projectName)) {
				continue;
			}

			$project = $this->Project->findByName($projectName);
			if (!$project) {
				$this->error("$projectName is not a recognised project!");
			}
			$projects[$project['Project']['id']] = array('project_id' => $project['Project']['id']);
		}

		$this->ProjectGroup->create();

		$ok = $this->ProjectGroup->saveAll(array(
			'ProjectGroup' => array(
				'name'      => $name,
				'description' => @$this->params['description'],
				'deleted'   => 0,
			),
			'Project' => $projects
		));

		if (!$ok) {
			$this->error(__("Failed to create project group '$name'!"));
		}

		$project = $this->ProjectGroup->findByName($name);
		$this->out(__("ProjectGroup '$name' created with ID ".$project['ProjectGroup']['id']));
		
	}

	public function addProject() {

		$projectGroupName = $this->args[0];
		$projectName = $this->args[1];

		$this->Project->recursive = -1;
		$projectGroupDetails = $this->ProjectGroup->findByName($projectGroupName);
		$projectDetails = $this->Project->findByName($projectName);

		if (!$projectGroupDetails) {
			$this->error(__("Could not find a project group named '$projectGroupName'!"));
		}

		if (!$projectDetails) {
			$this->error(__("Could not find a project named '$projectName'!"));
		}

		$projectGroupId = $projectGroupDetails['ProjectGroup']['id'];

		// Check existing collaborator list
		foreach ($projectGroupDetails['Project'] as $member) {

			// Don't need to do anything :-)
			if ($member['id'] == $projectDetails['Project']['id']) {
				$this->out(__("$projectName is already a member of the group $projectGroupName."));
				return;
			}
		}

		// Need to add a new collaborator link
		$ok = $this->ProjectGroupsProject->save(array('ProjectGroupsProject' => array(
			'project_group_id' => $projectGroupDetails['ProjectGroup']['id'],
			'project_id'    => $projectDetails['Project']['id'],
		)), array('callbacks' => false));

		if (!$ok) {
			$this->error(__("Failed to add project!"));
		}

		$this->out(__("$projectName is now a member of $projectGroupName"));
	}

	public function removeProject() {

		$projectGroupName = $this->args[0];
		$projectName   = $this->args[1];

		$this->Project->recursive = -1;
		$projectGroupDetails = $this->ProjectGroup->findByName($projectGroupName);
		$projectDetails = $this->Project->findByName($projectName);

		if (!$projectGroupDetails) {
			$this->error(__("Could not find a project group named '$projectGroupName'!"));
			return;
		}

		if (!$projectDetails) {
			$this->error(__("Could not find a project named '$projectName'!"));
			return;
		}

		$memberDetails = $this->ProjectGroupsProject->findByProjectGroupIdAndProjectId($projectGroupDetails['ProjectGroup']['id'], $projectDetails['Project']['id']);
		if (!$memberDetails) {
			$this->out(__("$projectName is not a member of $projectGroupName"));
			return;
		}
		
		$ok = $this->ProjectGroupsProject->deleteAll(
			array('ProjectGroupsProject.id' => $memberDetails['ProjectGroupsProject']['id']),
		false, false);

		if (!$ok) {
			$this->error(__("Failed to remove $projectName from $projectGroupName"));
			return;
		}

		$this->out(__("$projectName is no longer a member of $projectGroupName"));
	}

	public function addTeam() {

		$projectName = $this->args[0];
		$teamName = $this->args[1];

		$this->Team->recursive = -1;
		$projectDetails = $this->ProjectGroup->findByName($projectName);
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
		foreach ($projectDetails['GroupCollaboratingTeam'] as $collab) {

			// Don't need to do anything :-)
			if ($collab['team_id'] == $teamId && $collab['access_level'] == $accessLevel) {
				$this->out(__("$teamName already has ".$this->params['type']." access to the project."));
				return;

			// Found them, wrong status, stop checking
			} elseif ($collab['team_id'] == $teamId) {
				$this->GroupCollaboratingTeam->id = $collab['id'];
				break;
			}
		}

		// Need to add a new collaborator link
		$ok = $this->GroupCollaboratingTeam->save(array('GroupCollaboratingTeam' => array(
			'project_group_id' => $projectDetails['ProjectGroup']['id'],
			'team_id'    => $teamDetails['Team']['id'],
			'access_level' => $accessLevel,
		)), array('callbacks' => false));

		if (!$ok) {
			$this->error(__("Failed to add team!"));
		}

		$this->out(__("$teamName now has ".$this->params['type']." access to $projectName"));
	}

	public function removeTeam() {

		$projectGroupName = $this->args[0];
		$teamName   = $this->args[1];

		$collabDetails = $this->GroupCollaboratingTeam->find('first', array('conditions' => array(
			'Team.name' => $teamName,
			'ProjectGroup.name' => $projectGroupName,
		)));

		if (!$collabDetails) {
			$this->error(__("$teamName is not collaborating on $projectGroupName!"));
		}
		$ok = $this->GroupCollaboratingTeam->deleteAll(
			array('GroupCollaboratingTeam.id' => $collabDetails['GroupCollaboratingTeam']['id']),
		false, false);

		if (!$ok) {
			$this->error(__("Failed to remove collaborator!"));
		}

		$this->out(__("$teamName is no longer collaborating on $projectGroupName"));
	}

}

