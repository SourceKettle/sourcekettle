<?php
App::uses('AppModel', 'Model');
/**
 * MilestoneBurndownLog Model
 *
 * @property Milestone $Milestone
 */
class MilestoneBurndownLog extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'timestamp';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Milestone' => array(
			'className' => 'Milestone',
			'foreignKey' => 'milestone_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function beforeSave($options) {

		// Before we save a log entry, make sure the counts have actually changed
		$fields = array(
			'open_task_count', 'open_minutes_count', 'open_points_count',
			'closed_task_count', 'closed_minutes_count', 'closed_points_count',
		);

		// Get the latest log entry
		$lastLog = $this->find('first', array(
			'order' => array('timestamp DESC'),
			'recursive' => -1,
			'fields' => $fields,
		));

		// Find out if any counts have changed
		$changed = 0;
		foreach ($fields as $field) {
			if ($lastLog['MilestoneBurndownLog'][$field] != $this->data['MilestoneBurndownLog'][$field]){
				$changed = 1;
				break;
			}
		}

		// If nothing's changed, don't save
		return $changed;
	}
}
