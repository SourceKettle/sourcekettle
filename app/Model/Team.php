<?php
App::uses('AppModel', 'Model');
/**
 * Team Model
 *
 * @property User $User
 */
class Team extends AppModel {

	public $displayField = 'name';

	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a name for the team',
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'This team name has already been taken',
			),
			'minLength' => array(
				'rule' => array('minLength', 4),
				'message' => 'Team names must be at least 4 characters long',
			),
			'alphaNumericDashUnderscore' => array(
				'rule' => '/^[0-9a-zA-Z_-]+$/',
				'message' => 'May contain only letters, numbers, dashes and underscores',
			),
			'startWithALetter' => array(
				'rule' => '/^[a-zA-Z].+$/',
				'message' => 'Team names must start with a letter',
			),
		),
	);

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
		)
	);

}
