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
		'Collaborator' => array(
			'className' => 'Collaborator',
			'foreignKey' => 'project_id',
			'dependent' => true,
		),
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
		)
	);

	public function beforeDelete($cascade = true) {
		if ($this->Source->getType() != null) {
			$location = $this->Source->getRepositoryLocation();
			if ($location == null || !is_dir($location)) {
				return true;
			}
			$folder = new Folder($location);
			if ($folder->delete()) {
				// Successfully deleted project and its nested folders
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}

/**
 * Fetches a project from either its name or its id
 *
 * @param $key string id or name of project to fetch
 * @return Project The project found by the given key, null if no project is found
 * @throws NotFoundException
 * @throws ForbiddenException
 */
	public function getProject($key, $skipPerms = false, $recursive = false) {
		if ($key == null) { //Sanity check
			return null;
		}

		// Convert from true/false to the rather bizarre Cake style 0/-1...
		if ($recursive) {
			$recursive = 0;
		} else {
			$recursive = -1;
		}

		$project = null;
		if (is_numeric($key)) {
			$project = $this->find('first', array('recursive' => $recursive, 'conditions' => array('Project.id' => $key)));
		} else {
			$project = $this->find('first', array('recursive' => $recursive, 'conditions' => array('Project.name' => $key)));
		}
		if (empty($project)) {
			throw new NotFoundException("Project could not be found with reference {$key}");
		}

		// TODO MVC fail
		// In some cases, User::get('id') isn't set (like GitCommand)
		if (!$skipPerms && isset($this->Auth) && !$this->Auth->user('is_admin')) {
			// Lock out those who are not allowed to read
			if ( !$this->hasRead($this->Auth->user('id'), $project['Project']['id']) ) {
				throw new ForbiddenException(__('You do not have permissions to access this project.'));
			}
		}

		return $project;
	}

/**
 * Checks to see if a user has read access of this project
 *
 * @param $user int id of the user to check
 * @return boolean true if read permissions
 */
	public function hasRead($user = null, $project = null) {
		// TODO MVC fail
		if ( $user == null ) {
			$user = $this->Auth->user('id');
		}
		if ( $user == null ) {
			return false;
		}

		if ($this->id) $project = $this->id;

		if ($this->field('public', array('Project.id' => $project))) {
			return true;
		}

		$member = $this->Collaborator->find('first', array('conditions' => array('user_id' => $user, 'project_id' => $project), 'fields' => array('access_level')));

		if ( !empty($member) && $member['Collaborator']['access_level'] > -1 ) {
			return true;
		}

		return false;
	}

/**
 * Checks to see if a user has write access of this project
 *
 * @param $user int id of the user to check
 * @return boolean true if write permissions
 */
	public function hasWrite($userId = null, $projectId = null) {
		// TODO MVC fail
		if ( $userId == null ) {
			$userId = User::get('id');
		}
		if ( $userId == null ) {
			return false;
		}

		// System admins can write to any project
		$isAdmin = $this->Collaborator->User->field('is_admin', array('id' => $userId));
		if ( $isAdmin ) {
			return true;
		}

		if ($this->id) {
			$projectId = $this->id;
		}


		// Check whether user is a project admin
		$member = $this->Collaborator->find('first', array('conditions' => array('user_id' => $userId, 'project_id' => $projectId), 'fields' => array('access_level')));
		if ( !empty($member) && $member['Collaborator']['access_level'] > 0 ) {
			return true;
		}

		// Fail safe, no write privilege
		return false;
	}

/**
 * Checks to see if a user is an admin of this project
 *
 * @param $user int id of the user to check
 * @return boolean true if admin
 */
	public function isAdmin($user = null, $project = null) {
		// TODO MVC fail
		if ( $user == null ) $user = User::get('id');
		if ( $user == null ) return false;

		if ($this->id) $project = $this->id;

		$member = $this->Collaborator->find('first', array('conditions' => array('user_id' => $user, 'project_id' => $project), 'fields' => array('access_level')));

		if ( !empty($member) && $member['Collaborator']['access_level'] > 1 ) {
			return true;
		}

		return false;
	}

	public function fetchEventsForProject($number = 8) {
		// Sort function for events
		// assumes $array{ $array{ 'modified' => 'date' }, ... }
		$cmp = function($a, $b) {
			if (strtotime($a['modified']) == strtotime($b['modified'])) return 0;
			if (strtotime($a['modified']) < strtotime($b['modified'])) return 1;
			return -1;
		};

		$this->recursive = 2;
		$project = $this->getProject($this->id);

		$events = array();

		// Types of event to collect
		$_types = array('Collaborator', 'Time', 'Task', 'Milestone');

		try {
			$this->Source->init();
			array_push($_types, 'Source');
		} catch (UnsupportedRepositoryType $e) {
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

				// Mudge the old and the new together and sort
				$_modelEvents = array_merge($_modelEvents, $_newEvents);
				usort($_modelEvents, $cmp);

				// Check that no adjacent events are duplicates
				$_lEvent = null;
				foreach ($_modelEvents as $a => $_mEvent) {
					if ($_lEvent && $_lEvent['Type'] == $_mEvent['Type'] &&
						$_lEvent['Project']['id'] == $_mEvent['Project']['id'] &&
						$_lEvent['Actioner']['id'] == $_mEvent['Actioner']['id'] &&
						$_lEvent['Subject']['id'] == $_mEvent['Subject']['id'] &&
						$_lEvent['Change']['field'] == $_mEvent['Change']['field']) {
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
		usort($events, $cmp);
		return array_slice($events, 0, $number);
	}

/**
 * Returns a list of open tasks that are not assigned to milestones.
 */
	public function getProjectBacklog(){
		$backlog = $this->Task->find(
			'all',
			array(
				'conditions' => array(
					// TODO hard-coded IDs
					'Task.task_status_id' => array(1, 2, 5), // open/in-progress/dropped
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

}
