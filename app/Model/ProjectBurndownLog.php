<?php
App::uses('AppModel', 'Model');
/**
 * ProjectBurndownLog Model
 *
 * @property Project $Project
 */
class ProjectBurndownLog extends AppModel {

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
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function beforeSave($options = array()) {

		// Before we save a log entry, make sure the counts have actually changed
		$fields = array(
			'open_task_count', 'open_minutes_count', 'open_points_count',
			'closed_task_count', 'closed_minutes_count', 'closed_points_count',
		);

		// Get the latest log entry
		$lastLog = $this->find('first', array(
			'conditions' => array('project_id' => $this->data['ProjectBurndownLog']['project_id']),
			'order' => array('timestamp DESC'),
			'contain' => false,
			'fields' => $fields,
		));

		// No previous logs - definitely a change...
		if (empty($lastLog)) {
			return 1;
		}

		// Find out if any counts have changed
		$changed = 0;
		foreach ($fields as $field) {
			if ($lastLog['ProjectBurndownLog'][$field] != $this->data['ProjectBurndownLog'][$field]){
				$changed = 1;
				break;
			}
		}

		// If nothing's changed, don't save
		return $changed;
	}
}
