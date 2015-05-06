<?php
/**
 *
 * TaskPriority model for the SourceKettle system
 * Stores the Priorities for Tasks in the system
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

class TaskPriority extends AppModel {

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
			'foreignKey' => 'task_priority_id',
			'dependent' => false,
		)
	);
	public $actsAs = array(
		'FilterValid' => array(
			'nameField' => 'name',
		),
	);

	private $__byId = array();
	private $__byName = array();
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$ts = $this->find('all', array('contain' => false, 'fields' => array('id', 'name', 'label', 'level', 'icon', 'class')));
		foreach ($ts as $s) {
			$this->__byId[ $s['TaskPriority']['id'] ] = $s['TaskPriority'];
			$this->__byName[ $s['TaskPriority']['name'] ] = $s['TaskPriority'];
		}
	}
	public function idToName($id) {
		return @$this->__byId[$id]['name'];
	}

	public function nameToID($name) {
		return @$this->__byName[$name]['id'];
	}

	public function getLookupTable() {
		return $this->__byId;
	}
}
