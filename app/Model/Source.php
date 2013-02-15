<?php
/**
 *
 * Source model for the DevTrack system
 * Represents a source in the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.Model
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppModel', 'Model');
App::uses('UnsupportedRepositoryType', 'Exception');
App::uses('RepoTypes', 'GitCake.Model');

class Source extends AppModel {

/**
 * useTable
 */
	public $useTable = 'source';

/**
 * belongsTo associations
 */
	public $belongsTo = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id',
		)
	);

/**
 * hasMany associations
 */
	public $hasMany = array(
		'Blob' => array(
			'className' => 'GitCake.Blob',
		),
		'Commit' => array(
			'className' => 'GitCake.Commit',
		),
	);

/**
 * getType function.
 * @throws UnsupportedRepositoryType
 */
	public function getType() {
		$types = array(
			1 => null,
			2 => RepoTypes::GIT,
			3 => RepoTypes::SVN
		);
		$repoType = $this->Project->field('repo_type');

		if (isset($types[$repoType])) {
			return $types[$repoType];
		}
		throw new UnsupportedRepositoryType(__("Repository type not supported"));
	}

/**
 * create function.
 *
 * @access public
 * @param array $data (default: array())
 * @param bool $filterKey (default: false)
 * @throws UnsupportedRepositoryType
 */
	public function create($data = array(), $filterKey = false) {
		$type = $this->getType();
		$location = $this->getRepositoryLocation();

		if ($type == RepoTypes::GIT) {
			App::uses('SourceGit', 'GitCake.Model');
			return SourceGit::create($location, 'g+rwX', 'group');
		} else if ($type == RepoTypes::SVN) {
			App::uses('SourceSubversion', 'GitCake.Model');
			return SourceSubversion::create($location, 'g+rwX', 'group');
		} else {
			throw new UnsupportedRepositoryType(__("Repository type not supported"));
		}
	}

/**
 * branches function.
 * Fetch the branches.
 */
	public function getBranches() {
		return $this->Blob->getBranches();
	}

/**
 * getDefaultBranch function.
 * Fetch the default branch for the repo
 */
	public function getDefaultBranch() {
		$branches = $this->getBranches();
		$type = $this->getType();

		if ($type == RepoTypes::GIT) {
			$master = 'master';
		} else if ($type == RepoTypes::SVN) {
			$master = 'HEAD';
		} else {
			$master = null;
		}

		if (empty($branches)) {
			return $master;
		} else if (in_array($master, $branches)) {
			return $master;
		} else {
			return $branches[0];
		}
	}

/**
 * getRepositoryLocation function.
 * Get the location of the repo
 *
 * @throws UnsupportedRepositoryType
 */
	public function getRepositoryLocation() {
		$devtrackConfig = Configure::read('devtrack');
		$base = $devtrackConfig['repo']['base'];

		if ($base[strlen($base) - 1] != '/') $base .= '/';

		$name = $this->Project->field('name');
		$type = $this->getType();

		if ($type == RepoTypes::GIT) {
			$type = 'git';
		} else if ($type == RepoTypes::SVN) {
			$type = 'svn';
		} else {
			throw new UnsupportedRepositoryType(__("Repository type not supported"));
		}

		return "{$base}{$name}.{$type}/";
	}

/**
 * init function.
 */
	public function init() {
		$type = $this->getType();
		$location = $this->getRepositoryLocation();

		// Need to create a Git singleton
		$this->Blob->open($type, $location);
		$this->Commit->open($type, $location);
	}

	public function fetchHistory($project = '', $number = 10, $offset = 0, $user = -1, $query = array()) {
		$events = array();
		$branches = $this->getBranches();
		$project = $this->Project->getProject($project);

		if (!empty ($branches)) {
			foreach ($branches as $branch) {

				foreach ($this->Commit->history($branch, $number, $offset, '') as $a => $commit) {
					$commit = $this->Commit->fetch($commit);

					$newEvent = array();
					$newEvent['modified'] = date('Y-m-d H:i:s', strtotime($commit['date']));
					$newEvent['Type'] = 'Source';

					// Gather project details
					$newEvent['Project']['id'] = $project['Project']['id'];
					$newEvent['Project']['name'] = $project['Project']['name'];

					// Gather user details
					$newEvent['Actioner']['id'] = -1;
					$newEvent['Actioner']['name'] = $commit['author']['name'];
					$newEvent['Actioner']['email'] = $commit['author']['email'];
					$newEvent['Actioner']['exists'] = false;

					// Gather subject details
					$newEvent['Subject']['id'] = $commit['hash'];
					$newEvent['Subject']['title'] = $commit['subject'];
					$newEvent['Subject']['exists'] = true;

					// Gather change details
					$newEvent['Change']['field'] = '+';
					$newEvent['Change']['field_old'] = null;
					$newEvent['Change']['field_new'] = null;

					// Check if the actioner exists
					$actioner = $this->Project->Collaborator->User->findByEmail($newEvent['Actioner']['email']);
					if ($actioner) {
						$newEvent['Actioner']['id'] = $actioner['User']['id'];
						$newEvent['Actioner']['name'] = $actioner['User']['name'];
						$newEvent['Actioner']['exists'] = true;
					}

					// Store URL override
					$newEvent['url'] = array('api' => false, 'project' => $project['Project']['name'], 'controller' => 'source', 'action' => 'commit', $commit['hash']);
					$events[] = $newEvent;
				}
			}
		}

		// Collect time events

		// Sort function for events
		// assumes $array{ $array{ 'modified' => 'date' }, ... }
		$cmp = function($a, $b) {
			if (strtotime($a['modified']) == strtotime($b['modified'])) return 0;
			if (strtotime($a['modified']) < strtotime($b['modified'])) return 1;
			return -1;
		};

		usort($events, $cmp);

		return $events;
	}
}
