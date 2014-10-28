<?php
App::uses('AppModel', 'Model');
/**
 * ProjectGroupPermission Model
 *
 * @property ProjectGroup $ProjectGroup
 */
class ProjectGroupPermission extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ProjectGroup' => array(
			'className' => 'ProjectGroup',
			'foreignKey' => 'project_group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
