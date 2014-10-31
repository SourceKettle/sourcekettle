<?php
App::uses('AppModel', 'Model');
/**
 * Story Model
 *
 * @property Creator $Creator
 * @property Task $Task
 */
class Story extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'subject' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'creator_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);


/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Creator' => array(
			'className' => 'User',
			'foreignKey' => 'creator_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Task' => array(
			'className' => 'Task',
			'foreignKey' => 'story_id',
			'dependent' => false,
		)
	);

}
