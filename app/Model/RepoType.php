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
 * Display field
 */
	public $displayField = 'name';

/**
 * Validation rules
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'A valid name was not given for repo type',
			),
		),
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

	public function nameToID($type_name) {
		$found = $this->find('first', array('conditions' => array('LOWER(name)' => strtolower(trim($type_name)))));
		if(empty($found)){
			return 0;
		}
		return $found['RepoType']['id'];
	}

	public function idToName($id) {
		$found = $this->findById($id);
		if(empty($found)){
			return null;
		}
		return strtolower($found['RepoType']['name']);
	}

}
