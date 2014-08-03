<?php
/**
 *
 * Setting model for the SourceKettle system
 * Represents core settings in the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.Model
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppModel', 'Model');

class Setting extends AppModel {

/**
 * Display field
 */
	public $displayField = 'name';

/**
 * Validation rules
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		// NB values may be empty.
	);

/**
 * syncRequired function.
 * Notify the system that the keys need to be sync'd
 */
	public function syncRequired() {
		$setting = $this->findByName('sync_required', array('id'));
		$this->id = $setting['Setting']['id'];
		$this->set('value', '1');
		$this->save();
	}

/**
 * Merges any settings from our config files with settings from the database,
 * which take priority.
 */
	public function loadConfigSettings() {
		$settings = Configure::read('sourcekettle');
		foreach ($this->find('list', array('fields' => array('Setting.name', 'Setting.value')))  as $name => $value) {
			if (preg_match('/^([^\.]+)\.(.+)$/', $name, $matches)) {
				$settings[$matches[1]] = @$settings[$matches[1]] ?: array();
				$settings[$matches[1]][$matches[2]] = $value;
			} else {
				$settings[$name] = $value;
			}
		}
		return $settings;
	}
}
