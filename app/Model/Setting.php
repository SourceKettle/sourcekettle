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
			'notblank' => array(
				'rule' => array('notblank'),
			),
		),
		// NB values may be empty.
	);

	// Helper function to flatten out the settings tree into a flat 'foo.bar.baz' => 'quux' format
	public static function flattenTree($data, $soFar = null, &$output = array()) {

		if (!is_array($data)) {
			// Flags for featuers etc. - convert to boolean from string true/false
			if (preg_match('/[_.]enabled$/i', $soFar)) {
				$data = self::boolify($data);
			}
			$output[$soFar] = $data;
		} else {
			foreach($data as $key => $value) {
				if($soFar) {
					$newKey = "$soFar.$key";
				} else {
					$newKey = $key;
				}
				self::flattenTree($value, $newKey, $output);
			}
		}

		return $output;
	}

	public function saveSettingsTree($data, $locked = false) {

		if (!isset($data['Setting'])) {
			return false;
		}

		// Flatten out the settings tree into dot-separated key => value
		$settings = self::flattenTree($data['Setting']);

		// Get defaults and flatten; note that it will also have the 'value', 'locked' etc. on the end...
		$defaults = self::flattenTree($this->getDefaultSettings());

		$ok = true;

		// Save each setting in turn...
		foreach ($settings as $name => $value) {
			// Ensure true/false strings are booleanised
			if ($locked) {
				$value = self::boolify($value);
			}
			// Default data to save
			$save = array('Setting' => array('name' => $name, 'value' => $value));

			// Not a valid setting (not in defaults) - skip it
			if (!isset($defaults["$name.value"])) {
				continue;
			}

			// Find the setting's ID in the database, if present
			$id = $this->findByName($name);

			if (!empty($id) && isset($id['Setting']['id'])) {
				$save['Setting']['id'] = $id['Setting']['id'];

			// No existing setting and we're trying to lock it: fail
			} elseif ($locked) {
				$ok = false;
				continue;
			}

			// If we're just updating the lock status, set that
			if ($locked) {
				$save['Setting']['locked'] = $value;
				unset($save['Setting']['value']);

			// If it's not in the DB, get a locked status from the defaults
			} elseif(!$id) {
				$save['Setting']['locked'] = $defaults["$name.locked"];
			}
			unset($this->id);
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
	public function syncRequired($required = true) {
		$id = $this->findByName('Status.sync_required');
		$data = array('Setting' => array('name' => 'Status.sync_required', 'value' => $required));
		if (!empty($id) && isset($id['Setting']['id'])) {
			$data['Setting']['id'] = $id['Setting']['id'];
		}
		return $this->save($data);
	}

	// Hard coded default settings, for when we don't have anything in the database
	public function getDefaultSettings() {
		return array(

			// User-related settings
			'Users' => array(
				// Can users register?
				'register_enabled' => array('source' => 'Defaults', 'locked' => false, 'value' => true),
				// Contact address for problems
				'sysadmin_email' => array('source' => 'Defaults', 'locked' => false, 'value' => 'sysadmin@example.com'),
				// From: address for any emails sent by the system
				'send_email_from' => array('source' => 'Defaults', 'locked' => false, 'value' => 'sysadmin@example.com'),
				// Maximum age of lost password and confirmation keys (5 hours)
				'max_activation_key_age' => array('source' => 'Defaults', 'locked' => false, 'value' => 5 * 60 * 60),
				// Can normal users add external users
				'invites_enabled' => array('source' => 'Defaults', 'locked' => false, 'value' => false),
			),

			// LDAP authentication settings
			'Ldap' => array(
				// Use LDAP?
				'enabled' => array('source' => 'Defaults', 'locked' => false, 'value' => false),
				// ldap:// or ldaps:// URL to connect to the system
				'url' => array('source' => 'Defaults', 'locked' => false, 'value' => 'ldaps://ldap.example.com'),
				// Credentials for connecting to LDAP
				'bind_dn' => array('source' => 'Defaults', 'locked' => false, 'value' => 'cn=some_user,ou=Users,dc=example,dc=com'),
				'bind_pw' => array('source' => 'Defaults', 'locked' => false, 'value' => 'some_password'),
				// Base DN for user accounts
				'base_dn' => array('source' => 'Defaults', 'locked' => false, 'value' => 'ou=Users,dc=example,dc=com'),
				// Filter for finding user accounts
				'filter' => array('source' => 'Defaults', 'locked' => false, 'value' => 'mail=%USERNAME%'),
			),

			// Features that are enabled
			'Features' => array(
				// Allow time tracking/logging
				'time_enabled' => array('source' => 'Defaults', 'locked' => false, 'value' => true),
				// Allow source code management repositories and browsing
				'source_enabled' => array('source' => 'Defaults', 'locked' => false, 'value' => true),
				// Allow task tracking
				'task_enabled' => array('source' => 'Defaults', 'locked' => false, 'value' => true),
				// Allow user stories
				'story_enabled' => array('source' => 'Defaults', 'locked' => false, 'value' => false),
				// Allow epics, for grouping stories
				'epic_enabled' => array('source' => 'Defaults', 'locked' => false, 'value' => false),
				// Allow attachment uploads
				'attachment_enabled' => array('source' => 'Defaults', 'locked' => false, 'value' => true),
				// Use 4-column kanban chart?
				'4col_kanban_enabled' => array('source' => 'Defaults', 'locked' => false, 'value' => false),
			),

			// UI-related settings - appearance etc.
			'UserInterface' => array(
				// What to call the system, if you don't want to call it 'SourceKettle'
				'alias' => array('source' => 'Defaults', 'locked' => false, 'value' => 'SourceKettle'),
				// The theme to use
				'theme' => array('source' => 'Defaults', 'locked' => false, 'value' => 'default'),
				// Terminology for projects - e.g. do you call it a 'Milestone', a 'Sprint', a 'Timebox'...?
				'terminology' => array('source' => 'Defaults', 'locked' => false, 'value' => 'default'),
			),

			// Status flags
			'Status' => array(
				// Used to indicate that SSH keys should be updated, etc.
				'sync_required' => array('source' => 'Defaults', 'locked' => false, 'value' => '0'),
			),

			// Source code management settings
			'SourceRepository' => array(
				// User account for SSH repository access
				'user' => array('source' => 'Defaults', 'locked' => false, 'value' => 'nobody'),
				// Where repositories are stored
				'base' => array('source' => 'Defaults', 'locked' => false, 'value' => '/var/sourcekettle/repositories'),
				// Default repository type
				'default' => array('source' => 'Defaults', 'locked' => false, 'value' => 'Git'),
			),

			// Default options for new items
			'Defaults' => array(
				// Task defaults
				'task_type' => array('source' => 'Defaults', 'locked' => false, 'value' => 'enhancement'),
				'task_priority' => array('source' => 'Defaults', 'locked' => false, 'value' => 'major'),
				'task_status' => array('source' => 'Defaults', 'locked' => false, 'value' => 'open'),
				'task_assignee_id' => array('source' => 'Defaults', 'locked' => false, 'value' => '0'),
			),
		);
	}

	// Ensures boolean values end up as 1 or 0 for compatibility with CakePHP
	// Note that it won't automagically do this for us as the fields are free-text
	private static function boolify($thing){
		if (strtolower($thing) === 'true') {
			return 1;
		}
		if (strtolower($thing) === 'false') {
			return 0;
		}
		return ((bool)$thing)? 1: 0;
	}

	// Given an array of settings, a dotted-path name and a value, merge the value into the settings tree
	private function mergeSetting($source, $currentSettings, $name, $value, $locked = false) {

		// Make sure true/false strings are booleanised
		if ($locked) {
			$locked = self::boolify($locked);
		}
		if (preg_match('/[_.]enabled$/i', $name)) {
			$value = self::boolify($value);
		}

		// Key can be e.g. foo.bar.baz, corresponding to $settings['foo']['bar']['baz']
		$path = explode('.', $name);
		$current = &$currentSettings;
		
		// Eat key parts one at a time
		while(($key = array_shift($path))) {

			// Not a valid setting - skip it
			if(!isset($current[$key])) {
				continue;

			// If we're on the last key part, set the value if it's overridable
			// NB if the value is 'default', simply ignore it and use the *system* default
			} elseif (empty($path) && !$current[$key]['locked'] && ($source == 'System settings' || $value !== 'default')) {
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
			$project = ClassRegistry::init('Project')->find('first', array(
				'conditions' => array(
					'OR' => array('name' => $project, 'id' => $project),
				),
				'contain' => false,
			));

			if (empty($project)) {
				return $settings;
			}

			$project = $project['Project']['id'];
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
