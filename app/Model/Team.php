<?php
App::uses('AppModel', 'Model');
/**
 * Team Model
 *
 * @property User $User
 */
class Team extends AppModel {

	public $displayField = 'name';

 	public $hasMany = array(
		// Collaborating teams are teams of users mapped to projects with an access level
		'CollaboratingTeam' => array(
			'className' => 'CollaboratingTeam',
			'foreignKey' => 'team_id',
			'dependent' => false,
		),
		// Group collaborating teams are teams of users mapped to project groups with an access level
		'GroupCollaboratingTeam' => array(
			'className' => 'CollaboratingTeam',
			'foreignKey' => 'team_id',
			'dependent' => false,
		),
	);

	public $hasAndBelongsToMany = array(
		'User' => array(
			'className' => 'User',
			'joinTable' => 'teams_users',
			'foreignKey' => 'team_id',
			'associationForeignKey' => 'user_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);

}
