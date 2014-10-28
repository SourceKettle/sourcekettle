<?php
App::uses('AppModel', 'Model');
/**
 * ProjectGroup Model
 *
 * @property ProjectGroupMember $ProjectGroupMember
 * @property ProjectGroupPermission $ProjectGroupPermission
 */
class ProjectGroup extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ProjectGroupMember' => array(
			'className' => 'ProjectGroupMember',
			'foreignKey' => 'project_group_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ProjectGroupPermission' => array(
			'className' => 'ProjectGroupPermission',
			'foreignKey' => 'project_group_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
