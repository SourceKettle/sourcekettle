<?php
/**
 *
 * TaskType model for the SourceKettle system
 * Stores the Types for Tasks in the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.Model
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * @property Task $Task
 */
App::uses('AppModel', 'Model');

class TaskType extends AppModel {

/**
 * Display field
 */
	public $displayField = 'name';

/**
 * hasMany associations
 */
	public $hasMany = array(
		'Task' => array(
			'className' => 'Task',
			'foreignKey' => 'task_type_id',
			'dependent' => false,
		)
	);
	public $actsAs = array(
		'FilterValid' => array(
			'nameField' => 'name',
		),
	);

	public function nameToID($type_name) {
		$found = $this->find('first', array('conditions' => array('LOWER(name)' => strtolower(trim($type_name)))));
		if(empty($found)){
			return 0;
		}
		return $found['TaskType']['id'];
	}

	public function getLookupTable() {
		$tt = $this->find('all', array('recursive' => -1, 'fields' => array('id', 'name', 'label', 'icon', 'class')));
		$table = array();
		foreach ($tt as $t) {
			$table[ $t['TaskType']['id'] ] = $t['TaskType'];
		}
		return $table;
	}

}
