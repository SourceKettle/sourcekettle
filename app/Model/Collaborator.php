<?php
/**
 *
 * Collaborator model for the DevTrack system
 * Represents a project collaborator in the system
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

class Collaborator extends AppModel {

/**
 * actsAs behaviours
 */
	public $actsAs = array(
		'ProjectComponent',
		'ProjectHistory',
		'ProjectDeletable'
	);

/**
 * Validation rules
 */
	public $validate = array(
		'project_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'A valid project id was not entered',
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'A valid user id was not entered',
			),
		),
		'access_level' => array(
			'inlist' => array(
				'rule' => array('inlist', array(0, 1, 2)),
				'message' => 'The user access level was not in the defined types',
			),
		),
	);

/**
 * belongsTo associations
 */
	public $belongsTo = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id',
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
		)
	);

/**
 * TODO: Remove
 */
	public function fetchHistory($project = '', $number = 50, $offset = 0, $user = -1, $query = array()) {
		$events = $this->Project->ProjectHistory->fetchHistory($project, $number, $offset, $user,'collaborator');
		return $events;
	}

/**
 * TODO: Remove
 */
	public function getTitleForHistory($id) {
		$this->id = $id;
		if (!$this->exists()) {
			return null;
		} else {
			$this->User->id = $this->field('user_id');
			return $this->User->field('name');
		}
	}

/**
 * collaboratorsForProject function.
 * Return the collaborators for the specified project.
 *
 * @param mixed $project the project
 */
	public function collaboratorsForProject($project = null) {
		$users = array();
		$collaborators = $this->find('all', array(
			'conditions' => array('Collaborator.project_id' => $project)
		));
		foreach ($collaborators as $collaborator) {
			//$collaborator = "{$collaborator['User']['name']} [{$collaborator['User']['email']}]";
			$users[$collaborator['User']['id']] = "{$collaborator['User']['name']} [{$collaborator['User']['email']}]";
		}
		return $users;
	}
}
