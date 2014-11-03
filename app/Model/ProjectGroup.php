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
