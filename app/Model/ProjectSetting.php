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
		'Features.story_enabled',
		'Features.epic_enabled',
		'Features.source_enabled',
		'Features.attachment_enabled',
		'Features.4col_kanban_enabled',
		'UserInterface.terminology',
	);

	public function isValidName($name) {
		return in_array($name, $this->validNames);
	}

	public function saveSettingsTree($project, $data) {

		if (!is_numeric($project)) {
			$project = $this->Project->findByName($project);
		} else {
			$project = $this->Project->findById($project);
		}

		if (empty($project) || !isset($data['ProjectSetting'])) {
			return false;
		}

		// Flatten out the settings tree into dot-separated key => value
		$settings = Setting::flattenTree($data['ProjectSetting']);

		$ok = true;

		// Save each setting in turn...
		foreach ($settings as $name => $value) {

			// Not a valid setting - skip it
			if (!$this->isValidName($name)) {
				continue;
			}

			// Default data to save
			$save = array('ProjectSetting' => array('project_id' => $project['Project']['id'], 'name' => $name, 'value' => $value));

			// Find the setting's ID in the database, if present
			$id = $this->findByNameAndProjectId($name, $project['Project']['id']);
			if ($id) {
				$save['ProjectSetting']['id'] = $id['ProjectSetting']['id'];
			}

			unset($this->id);
			$ok = $this->save($save);
			if (!$ok) {
				$ok = false;
			}
		}

		return $ok;
	}
}
