<?php
/**
 *
 * Project model for the SourceKettle system
 * Represents a project in the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.Model
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppModel', 'Model');
App::uses('User', 'Model');
App::uses('Folder', 'Utility');
App::uses('UnsupportedRepositoryType', 'Exception');

class Project extends AppModel {

/**
 * Display field
 */
	public $displayField = 'name';

/**
 * actsAs behaviours
 */
	public $actsAs = array(
		'ProjectDeletable'
	);

/**
 * Validation rules
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a name for the project',
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'This project name has already been taken',
			),
			'minLength' => array(
				'rule' => array('minLength', 4),
				'message' => 'Project names must be at least 4 characters long',
			),
			'alphaNumericDashUnderscore' => array(
				'rule' => '/^[0-9a-zA-Z_-]+$/',
				'message' => 'May contain only letters, numbers, dashes and underscores',
			),
			'startWithALetter' => array(
				'rule' => '/^[a-zA-Z].+$/',
				'message' => 'Project names must start with a letter',
			),
		),
		'public' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				'message' => 'Please select the visibility of the project',
			),
		),
		'repo_type' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please select a repository type',
			),
		),
	);

/**
 * belongsTo associations
 */
	public $belongsTo = array(
		'RepoType' => array(
			'className' => 'RepoType',
			'foreignKey' => 'repo_type',
		)
	);

/**
 * hasMany associations
 */
	public $hasMany = array(
		'Task' => array(
			'className' => 'Task',
			'foreignKey' => 'project_id',
			'dependent' => true,
		),
		'Milestone' => array(
			'className' => 'Milestone',
			'foreignKey' => 'project_id',
			'dependent' => true,
		),
		'Source' => array(
			'className' => 'Source',
			'foreignKey' => 'project_id',
			'dependent' => false,
		),
		'Time' => array(
			'className' => 'Time',
			'foreignKey' => 'project_id',
			'dependent' => true,
		),
		'ProjectHistory' => array(
			'className' => 'ProjectHistory',
			'foreignKey' => 'project_id',
			'dependent' => true,
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'project_id',
			'dependent' => true,
		),
		'ProjectBurndownLog' => array(
			'className' => 'ProjectBurndownLog',
			'foreignKey' => 'project_id',
			'dependent' => true,
		),

		// Has-many-through relationships here...

		// The Collaborator mapping maps users to projects with an associated access level
		'Collaborator' => array(
			'className' => 'Collaborator',
			'foreignKey' => 'project_id',
			'dependent' => false,
		),

		// Collaborating teams are teams of users mapped to projects with an access level
		'CollaboratingTeam' => array(
			'className' => 'CollaboratingTeam',
			'foreignKey' => 'project_id',
			'dependent' => false,
		),

	);

	// Projects can belong to any number of project groups
	public $hasAndBelongsToMany = array(
		'ProjectGroup' => array(
			'className' => 'ProjectGroup',
			'foreignKey' => 'project_id',
			'associatedForeignKey' => 'project_group_id',
			'dependent' => false,
		),
	);

	public function beforeSave($options = array()) {

		// Do not allow "easy" changing of the project name. We will provide a separate function
		// to do this as the repository will need to be moved. This should be admin-only in the controller.
		if (isset($this->data[$this->alias]['name']) && isset($this->id) && $this->id != null) {
			unset($this->data[$this->alias]['name']);
		}
	}

	// This method exists simply to make testing easier... it means we can mock it out
	// and return a Folder object that deliberately fails to move things.
	public function getFolder($location) {
		return new Folder($location);
	}

	public function rename($nameOrId, $newName) {
		$project = $this->findByNameOrId($nameOrId, $nameOrId);
		if (!isset($project) || empty($project)) {
			throw new NotFoundException("Failed to find a project with name or id '$nameOrId'");
		}

		// Get the project ID so there's no doubt which project we're talking about...
		$this->id = $project[$this->alias]['id'];

		// Check it's not a no-op...!
		if ($project[$this->alias]['name'] == $newName) {
			return true;
		}

		// See if there's an existing project with the new name
		$existing = $this->findByName($newName);
		if (isset($existing) && !empty($existing)) {
			throw new InvalidArgumentException("Cannot rename project - another project called '$newName' already exists");
		}

		// No repository to move - just rename the project
		// NB we must disable callbacks when saving to stop our beforeSave method from filtering out the name
		if ($project['RepoType']['name'] == 'None' || $this->Source->getType() == null) {
			return ($this->save(array('name' => $newName), array('callbacks' => false)) != null);
		}

		// Repository has no location, this is bizarre but don't fret too much...
		$location = $this->Source->getRepositoryLocation();
		if ($location == null || !is_dir($location)) {
			return ($this->save(array('name' => $newName), array('callbacks' => false)) != null);
		}

		// Generate a new repository location
		$folder = $this->getFolder($location);

		$path = $folder->path;
		$dirname = dirname($path);
		$basename = basename($path);
		$newbasename = preg_replace('/^'.$project[$this->alias]['name'].'/', $newName, $basename);
		$newpath = "$dirname/$newbasename";

		// Make sure there isn't something else already in the new location
		if (file_exists($newpath)) {
			throw new InvalidArgumentException("Cannot rename project '".$project[$this->alias]['name']."' - repository cannot be moved as the directory already exists");
		}

		// Move the repo
		if (!$folder->move(array('to' => $newpath))) {
			throw new Exception(__("A problem occurred when renaming the project repository"));
		}

		return ($this->save(array('name' => $newName), array('callbacks' => false)) != null);
	}

	public function beforeDelete($cascade = true) {

		// Ensure the source knows about us... fun times.
		$this->Source->Project->id = $this->id;

		// No repository, all good
		if ($this->Source->getType() == null ) {
			return true;
		}

		// Repository directory does not exist, weird but OK
		$location = $this->Source->getRepositoryLocation();
		if ($location == null || !is_dir($location)) {
			return true;
		}

		// We have a repo, delete it
		$folder = $this->getFolder($location);
		return $folder->delete();
	}

/**
 * Fetches a project from either its name or its id
 *
 * @param $key string id or name of project to fetch
 * @return Project The project found by the given key, null if no project is found
 * @throws NotFoundException
 */
	public function getProject($key) {
		if ($key == null) { //Sanity check
			return null;
		}

		$project = null;
		if (is_numeric($key)) {
			$project = $this->find('first', array('recursive' => -1, 'conditions' => array('Project.id' => $key)));
		} else {
			$project = $this->find('first', array('recursive' => -1, 'conditions' => array('Project.name' => $key)));
		}
		if (empty($project)) {
			throw new NotFoundException("Project could not be found with reference {$key}");
		}

		return $project;
	}

	// Check a user's access level to the project
	public function checkAccessLevel($accessLevel = 0, $userId = null, $projectId = null) {
		
		// We currently do not allow access to non-logged-in users
		if ($userId == null) {
			return false;
		}

		// Default project ID if we have one already...
		if ($this->id && !isset($projectId)) {
			$projectId = $this->id;
		}

		// Public projects can be read by anybody who is logged in,
		// so if only read access is needed, grant access
		if ($this->field('public', array('Project.id' => $projectId)) && $accessLevel < 1) {
			return true;
		}

		// Check to see if they are a collaborator on the project
		$member = $this->Collaborator->find('first', array(
			'conditions' => array(
				'user_id' => $userId,
				'project_id' => $projectId
			),
			'fields' => array('access_level')));

		if (!empty($member) && $member['Collaborator']['access_level'] >= $accessLevel) {
			return true;
		}

		// Direct SQL querying is the easiest way to do this due to the model complexity...
		$db = $this->getDataSource();
		$table_prefix = $db->config['prefix'];

		// Check to see if the user is a member of any teams collaborating on the project
		$members = $db->fetchAll(
			"SELECT
				max(access_level) AS access_level
			FROM
				${table_prefix}collaborating_teams
			INNER JOIN
				${table_prefix}teams ON ${table_prefix}teams.id = ${table_prefix}collaborating_teams.team_id
			INNER JOIN
				${table_prefix}teams_users ON ${table_prefix}teams.id = ${table_prefix}teams_users.team_id
			WHERE
				${table_prefix}teams_users.user_id = ?
				AND
				${table_prefix}collaborating_teams.project_id = ?",
			array($userId, $projectId)
		);

		$hasAccessLevel = $members[0][0]['access_level'];

		if ($hasAccessLevel !== null && $hasAccessLevel >= $accessLevel) {
			return true;
		}

		// Check to see if the user is a member of any teams collaborating on
		// any of the project's groups
		$members = $db->fetchAll(
			"SELECT
				max(access_level) AS access_level
			FROM
				${table_prefix}group_collaborating_teams
			INNER JOIN
				${table_prefix}teams ON ${table_prefix}teams.id = ${table_prefix}group_collaborating_teams.team_id
			INNER JOIN
				${table_prefix}teams_users ON ${table_prefix}teams.id = ${table_prefix}teams_users.team_id
			INNER JOIN
				${table_prefix}project_groups_projects ON ${table_prefix}project_groups_projects.project_group_id = ${table_prefix}group_collaborating_teams.project_group_id
			WHERE
				${table_prefix}teams_users.user_id = ?
				AND
				${table_prefix}project_groups_projects.project_id = ?",
			array($userId, $projectId)
		);

		$hasAccessLevel = $members[0][0]['access_level'];
		if ($hasAccessLevel !== null && $hasAccessLevel >= $accessLevel) {
			return true;
		}
		// Default: fail safe and deny access
		return false;
	}

/**
 * Checks to see if a user has read access of this project
 *
 * @param $user int id of the user to check
 * @return boolean true if read permissions
 */
	public function hasRead($userId = null, $projectId = null) {
		return $this->checkAccessLevel(-1, $userId, $projectId);
	}

/**
 * Checks to see if a user has write access of this project
 *
 * @param $user int id of the user to check
 * @return boolean true if write permissions
 */
	public function hasWrite($userId = null, $projectId = null) {
		return $this->checkAccessLevel(1, $userId, $projectId);
	}

/**
 * Checks to see if a user is an admin of this project
 *
 * @param $user int id of the user to check
 * @return boolean true if admin
 */
	public function isAdmin($userId = null, $projectId = null) {
		return $this->checkAccessLevel(2, $userId, $projectId);
	}

	// Sort function for events
	// assumes $array{ $array{ 'modified' => 'date' }, ... }
	private static function __compareEvents($a, $b) {
		if (strtotime($a['modified']) == strtotime($b['modified'])) return 0;
		if (strtotime($a['modified']) < strtotime($b['modified'])) return 1;
		return -1;
	}

	public function fetchEventsForProject($projectId, $number = 8) {

		$project = $this->getProject($projectId);

		$events = array();

		// Types of event to collect
		$_types = array('Collaborator', 'Time', 'Task', 'Milestone');

		try {
			$this->Source->init();
			array_unshift($_types, 'Source');
		} catch (Exception $e) {
		}

		// Iterate over all of the types of event
		foreach ($_types as $x) {
			$_modelEvents = array();
			$_x = 0;
			$numberOfEvents = 0;

			// While the number of events we have for this type is too few
			while ($numberOfEvents < $number) {

				// Escape if we have no more events
				$_newEvents = $this->{$x}->fetchHistory($project['Project']['name'], $number, $number * $_x++);
				if (empty($_newEvents)) break;

				// Munge the old and the new together and sort
				$_modelEvents = array_merge($_modelEvents, $_newEvents);
				usort($_modelEvents, array("Project", "__compareEvents"));

				// Check that no adjacent events are duplicates
				$_lEvent = null;
				foreach ($_modelEvents as $a => $_mEvent) {
					if ($_lEvent && $_lEvent['Type'] == $_mEvent['Type'] &&
						$_lEvent['Project']['id'] == $_mEvent['Project']['id'] &&
						$_lEvent['Actioner']['id'] == $_mEvent['Actioner']['id'] &&
						$_lEvent['Subject']['id'] == $_mEvent['Subject']['id'] &&
						$_lEvent['Change']['field'] == $_mEvent['Change']['field'] &&
						$_lEvent['Change']['field_old'] == $_mEvent['Change']['field_old'] &&
						$_lEvent['Change']['field_new'] == $_mEvent['Change']['field_new']) {
						unset($_modelEvents[$a]);
					}
					$_lEvent = $_mEvent;
				}
				$numberOfEvents = count($_modelEvents);
			}

			// Bring all the events back together
			$events = array_merge($events, $_modelEvents);
		}

		// Finally sort all the events
		usort($events, array("Project", "__compareEvents"));
		return array_slice($events, 0, $number);
	}

/**
 * Returns a list of open tasks that are not assigned to milestones.
 */
	public function getProjectBacklog() {
		$backlog = $this->Task->find(
			'all',
			array(
				'conditions' => array(
					'TaskStatus.name' => array('open', 'in progress', 'dropped'),
					'Task.project_id' => $this->id,
					'OR' => array(
						'Task.milestone_id' => 0,
						'Task.milestone_id is null',
					)
				)
			)
		);
		return $backlog;
	}

	public function listCollaborators($projectId = null) {
		if ($projectId == null) {
			$projectId = $this->id;
		}

		$collabs = $this->Collaborator->find('list',
			array(
				'conditions' => array('project_id' => $projectId),
				'fields' => array('user_id'),
			)
		);

		return $this->Collaborator->User->find('list',
			array(
				'conditions' => array('id' => array_values($collabs)),
			)
		);

	}

}
