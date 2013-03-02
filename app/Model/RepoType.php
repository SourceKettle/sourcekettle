<?php
/**
 *
 * Repo Type model for the SourceKettle system
 * Represents a repository type in the system
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

class RepoType extends AppModel {

/**
 * useDbConfig
 * This Model is static and as such does not need a DB table
 */
	public $useDbConfig = 'array';

/**
 * recursive
 * We always come to this model, and never want the content related to it
 */
	public $recursive = false;

/**
 * records
 * The emulation of DB contents.
 * Note: To add a repo type add to this array
 */
	public $records = array(
		array('id' => 1, 'name' => 'n/a'),
		array('id' => 2, 'name' => 'Git'),
		array('id' => 3, 'name' => 'Subversion'),
	);

/**
 * hasMany associations
 */
	public $hasMany = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'repo_type',
			'dependent' => false,
		)
	);

/**
 * getType function.
 * Gets the repository type of the current project
 *
 * @param id int the project id
 * @throws UnsupportedRepositoryType
 * @returns the repo_type int
 */
	public function getType($id = null) {
		if ($id == null && $this->Project->id == null) {
			throw new NotFoundException(__('Could not find project'));
		}
		$this->Project->id = ($id == null) ? $this->Project->id : $id;

		return $this->Project->field('repo_type');
	}

/**
 * getTypeDetails function.
 * Get details for the RepoType.
 * E.g. The name of the repository type
 *
 * @param int $id the project id
 * @return the details of the repotype
 */
	public function getTypeDetails($id = null) {
		$repoType = $this->getType($id);
		$details = $this->find('first', array('conditions' => array('id' => $repoType)));

		if ($details == false) {
			throw new UnsupportedRepositoryType(__("Repository type not supported"));
		}

		return $details;
	}

/**
 * isGit function.
 * Check if the current Repo is Git
 *
 * @param int $id the project id
 * @return true if git
 */
	public function isGit($id = null) {
		return ($this->getType($id) == 2);
	}

/**
 * isSVN function.
 * Check if the current Repo is SVN
 *
 * @param int $id the project id
 * @return true if SVN
 */
	public function isSVN($id = null) {
		return ($this->getType($id) == 3);
	}
}
