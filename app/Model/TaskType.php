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

	private $__byId = array();
	private $__byName = array();
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$ts = $this->find('all', array('recursive' => -1, 'fields' => array('id', 'name', 'label', 'icon', 'class')));
		foreach ($ts as $s) {
			$this->__byId[ $s['TaskType']['id'] ] = $s['TaskType'];
			$this->__byName[ $s['TaskType']['name'] ] = $s['TaskType'];
		}
	}
	public function idToName($id) {
		return @$this->__byId[$id];
	}

	public function nameToID($name) {
		return @$this->__byName[$name]['id'];
	}

	public function getLookupTable() {
		return $this->__byId;
	}
}
