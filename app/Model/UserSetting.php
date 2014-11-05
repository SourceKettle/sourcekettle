<?php
App::uses('AppModel', 'Model');

class UserSetting extends AppModel {

	public $displayField = 'name';

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	// Loads in user settings, overriding anything that isn't already locked
	public function loadUserSettings($settings = array(), $userId) {

		// TODO locked settings?
		$userSettings = $this->find('list', array(
			'conditions' => array('user_id' => $userId),
			'fields' => array('UserSetting.name', 'UserSetting.value'),
		));

		foreach ($userSettings as $name => $value) {

			// Key can be e.g. foo.bar.baz, corresponding to $settings['foo']['bar']['baz']
			$path = explode('.', $name);
			$current = &$settings;

			// Eat key parts one at a time
			while(($key = array_shift($path))) {

				// If we're on the last key part, set the value
				if (empty($path)) {
					$current[$key] = $value;

				// Otherwise, make sure it maps to an array
				} else {
					$current[$key] = @$current[$key] ?: array();
				}

				// Keep track of progress through the settings array
				$current = &$current[$key];
			}
		}
		return $settings;
	}
}
