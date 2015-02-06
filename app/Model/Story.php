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
		'points_estimate' => array(
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

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		// Get the DB table prefix from our database config, for if
		// we have multiple systems in the same DB or fixtures have a prefix
		$db =& ConnectionManager::getDataSource($this->useDbConfig);
		$table_prefix = $db->config['prefix'];

		$this->virtualFields = array(
			'public_id' => "(SELECT ".
				"COUNT(`{$table_prefix}{$this->table}`.`id`) ".
			"FROM ".
				"`{$table_prefix}{$this->table}` ".
			"WHERE ".
				"`{$table_prefix}{$this->table}`.`id` <= `{$this->alias}`.`id` ".
			"AND ".
				"`{$table_prefix}{$this->table}`.`project_id` = `{$this->alias}`.`project_id`)",
		);
	}
}
