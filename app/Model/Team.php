<?php
App::uses('AppModel', 'Model');
/**
 * Team Model
 *
 * @property TeamMember $TeamMember
 */
class Team extends AppModel {

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
		'TeamMember' => array(
			'className' => 'TeamMember',
			'foreignKey' => 'team_id',
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
