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

	// Helper function to flatten out the settings tree into a flat 'foo.bar.baz' => 'quux' format
	public static function flattenTree($data, $soFar = null, $output = array()) {

		if (!is_array($data)) {
			// Convert flags from true/false to 1/0
			if (preg_match('/[_.]enabled$/', $soFar)) {
				$data = ($data && strtolower($data) != 'false') ? 1 : 0;
			}
			$output[$soFar] = $data;
		} else {
			foreach($data as $key => $value) {
				if($soFar) {
					$newKey = "$soFar.$key";
				} else {
					$newKey = $key;
				}
				self::flattenTree($value, $newKey, &$output);
			}
		}

		return $output;
	}

	public function saveSettingsTree($data) {

		// Flatten out the settings tree into dot-separated key => value
		if (!isset($data['Setting'])) {
			return false;
		}

		$settings = self::flattenTree($data['Setting']);

		// Get defaults and flatten; note that it will also have the 'value', 'locked' etc. on the end...
		$defaults = self::flattenTree($this->getDefaultSettings());

		$ok = true;

		// Save each setting in turn...
		foreach ($settings as $name => $value) {

			// Default data to save
			$save = array('Setting' => array('name' => $name, 'value' => $value));

			// Not a valid setting (not in defaults) - skip it
			if (!isset($defaults["$name.value"])) {
				continue;
			}

			// Find the setting's ID in the database, if present
			$id = $this->findByName($name);
			if ($id) {
				$save['Setting']['id'] = $id['Setting']['id'];

			// If it's not in the DB, we'll need to provide a 'locked' status; use the default...
			} else {
				$save['Setting']['locked'] = $defaults["$name.locked"];
			}

			if (!$this->save($save)) {
				$ok = false;
			}
		}

		return $ok;
	}

/**
 * syncRequired function.
 * Notify the system that the keys need to be sync'd
 */
	public function syncRequired() {
		return $this->save(array('Setting' => array('Status' => array('sync_required' => true))));
	}

	// Hard coded default settings, for when we don't have anything in the database
	public function getDefaultSettings() {
		return array(

			// User-related settings
			'Users' => array(
				// Can users register?
				'register_enabled' => array('source' => 'Defaults', 'locked' => 0, 'value' => '1'),
				// Contact address for problems
				'sysadmin_email' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'sysadmin@example.com'),
				// From: address for any emails sent by the system
				'send_email_from' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'sysadmin@example.com'),
			),

			// LDAP authentication settings
			'Ldap' => array(
				// Use LDAP?
				'enabled' => array('source' => 'Defaults', 'locked' => 0, 'value' => '0'),
				// ldap:// or ldaps:// URL to connect to the system
				'url' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'ldaps://ldap.example.com'),
				// Credentials for connecting to LDAP
				'bind_dn' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'cn=some_user,ou=Users,dc=example,dc=com'),
				'bind_pw' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'some_password'),
				// Base DN for user accounts
				'base_dn' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'ou=Users,dc=example,dc=com'),
				// Filter for finding user accounts
				'filter' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'mail=%USERNAME%'),
			),

			// Features that are enabled
			'Features' => array(
				// Allow time tracking/logging
				'time_enabled' => array('source' => 'Defaults', 'locked' => 0, 'value' => '1'),
				// Allow source code management repositories and browsing
				'source_enabled' => array('source' => 'Defaults', 'locked' => 0, 'value' => '1'),
				// Allow task tracking
				'task_enabled' => array('source' => 'Defaults', 'locked' => 0, 'value' => '1'),
				// Allow attachment uploads
				'attachment_enabled' => array('source' => 'Defaults', 'locked' => 0, 'value' => '1'),
			),

			// UI-related settings - appearance etc.
			'UserInterface' => array(
				// What to call the system, if you don't want to call it 'SourceKettle'
				'alias' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'SourceKettle'),
				// The theme to use
				'theme' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'default'),
				// Terminology for projects - e.g. do you call it a 'Milestone', a 'Sprint', a 'Timebox'...?
				'terminology' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'default'),
			),

			// Status flags
			'Status' => array(
				// Used to indicate that SSH keys should be updated, etc.
				'sync_required' => array('source' => 'Defaults', 'locked' => 0, 'value' => '0'),
			),

			// Source code management settings
			'SourceRepository' => array(
				// User account for SSH repository access
				'user' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'nobody'),
				// Where repositories are stored
				'base' => array('source' => 'Defaults', 'locked' => 0, 'value' => '/var/sourcekettle/repositories'),
				// Default repository type
				'default' => array('source' => 'Defaults', 'locked' => 0, 'value' => 'Git'),
			),
		);
	}


	// Given an array of settings, a dotted-path name and a value, merge the value into the settings tree
	private function mergeSetting($source, $currentSettings, $name, $value, $locked = false) {

		// Key can be e.g. foo.bar.baz, corresponding to $settings['foo']['bar']['baz']
		$path = explode('.', $name);
		$current = &$currentSettings;
		
		// Eat key parts one at a time
		while(($key = array_shift($path))) {

			// Not a valid setting - skip it
			if(!isset($current[$key])) {
				continue;

			// If we're on the last key part, set the value if it's overridable
			} elseif (empty($path) && !$current[$key]['locked']) {
				if (preg_match('/_enabled$/', $key)) {
					$value = (boolean) $value;
				}
				$current[$key] = array('value' => $value, 'locked' => $locked, 'source' => $source);
			}

			// Keep track of progress through the settings array
			$current = &$current[$key];
		}

		return $currentSettings;
	}

	public function loadConfigSettings($userId = null, $project = null) {

		// Start with the defaults - this also provides a complete list of all settings that are valid
		$settings = $this->getDefaultSettings();

		// Override with database settings
		$dbSettings = $this->find('all', array('fields' => array('Setting.name', 'Setting.value', 'Setting.locked')));
		foreach ($dbSettings as $dbSetting) {
			$name = $dbSetting['Setting']['name'];
			$value = $dbSetting['Setting']['value'];
			$locked = $dbSetting['Setting']['locked'];
			
			$settings = $this->mergeSetting("System settings", $settings, $name, $value, $locked);
		}

		// Now, find any overridden settings form the user's choices
		if ($userId) {
			$model = ClassRegistry::init('UserSetting');
			$userSettings = $model->find('all', array('conditions' => array('user_id' => $userId), 'fields' => array('UserSetting.name', 'UserSetting.value')));
			foreach ($userSettings as $dbSetting) {
				$name = $dbSetting['UserSetting']['name'];
				$value = $dbSetting['UserSetting']['value'];
				
				if (!$model->isValidName($name)){
					continue;
				}
				$settings = $this->mergeSetting("User preferences", $settings, $name, $value);
			}
		}

		// Load any project settings - a bit messy...
		if ($project) {
			if (!is_numeric($project)) {
				$project = ClassRegistry::init('Project')->findByName($project);
				if (empty($project)) {
					return $settings;
				}
				$project = $project['Project']['id'];
			}
			$model = ClassRegistry::init('ProjectSetting');
			$projectSettings = $model->find('all', array('conditions' => array('project_id' => $project), 'fields' => array('ProjectSetting.name', 'ProjectSetting.value')));
			foreach ($projectSettings as $dbSetting) {
				$name = $dbSetting['ProjectSetting']['name'];
				$value = $dbSetting['ProjectSetting']['value'];

				if (!$model->isValidName($name)){
					continue;
				}
				$settings = $this->mergeSetting("Project-specific settings", $settings, $name, $value);
			}
		}
		return $settings;

	}
}
