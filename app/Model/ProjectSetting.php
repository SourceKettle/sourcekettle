<?php
App::uses('AppModel', 'Model');
/**
 * ProjectSetting Model
 *
 * @property Project $Project
 */
class ProjectSetting extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


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

	private $validNames = array(
		'Features.time_enabled',
		'Features.task_enabled',
		'Features.source_enabled',
		'Features.attachment_enabled',
	);

	public function isValidName($name) {
		return in_array($name, $this->validNames);
	}
}
