<?php
App::uses('AppModel', 'Model');
/**
 * CollaboratingTeam Model
 *
 * @property Team $Team
 * @property Project $Project
 */
class CollaboratingTeam extends AppModel {

	public $belongsTo = array(
		'Team' => array(
			'className' => 'Team',
			'foreignKey' => 'team_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function findAllByProjectId($projectId) {
		return $this->find('all', array(
			'contain' => array('Project', 'Team' => array('User')),
			'conditions' => array('Project.id' => $projectId),
		));
	}
}
