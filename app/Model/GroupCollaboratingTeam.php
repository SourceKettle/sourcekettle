<?php
App::uses('AppModel', 'Model');
/**
 * GroupCollaboratingTeam Model
 *
 * @property ProjectGroup $ProjectGroup
 * @property Team $Team
 */
class GroupCollaboratingTeam extends AppModel {

	public $belongsTo = array(
		'ProjectGroup' => array(
			'className' => 'ProjectGroup',
			'foreignKey' => 'project_group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Team' => array(
			'className' => 'Team',
			'foreignKey' => 'team_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
