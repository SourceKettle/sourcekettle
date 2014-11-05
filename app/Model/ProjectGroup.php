<?php
App::uses('AppModel', 'Model');
/**
 * ProjectGroup Model
 *
 * @property ProjectGroupMember $ProjectGroupMember
 * @property ProjectGroupPermission $ProjectGroupPermission
 */
class ProjectGroup extends AppModel {

	public $displayField = 'name';

	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a name for the project group',
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'This project group name has already been taken',
			),
			'minLength' => array(
				'rule' => array('minLength', 4),
				'message' => 'Project group names must be at least 4 characters long',
			),
			'alphaNumericDashUnderscore' => array(
				'rule' => '/^[0-9a-zA-Z_-]+$/',
				'message' => 'May contain only letters, numbers, dashes and underscores',
			),
			'startWithALetter' => array(
				'rule' => '/^[a-zA-Z].+$/',
				'message' => 'Project group names must start with a letter',
			),
		),
	);

	public $hasAndBelongsToMany = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_group_id',
			'associatedForeignKey' => 'project_id',
			'dependent' => false,
		),
	);

	public $hasMany = array(
		// Group collaborating teams are teams of users mapped to project groups with an access level
		'GroupCollaboratingTeam' => array(
			'className' => 'GroupCollaboratingTeam',
			'foreignKey' => 'project_group_id',
			'dependent' => false,
		),
	);

}
