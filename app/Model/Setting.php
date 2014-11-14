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
		$setting = $this->findByName('Status.sync_required', array('id'));
		$this->id = $setting['Setting']['id'];
		$this->set('value', '1');
		$this->save();
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

/**
 * Merges any settings from our config files with settings from the database,
 * which take priority.
 */
	public function loadConfigSettings() {

		// Start with the defaults - this also provides a complete list of all settings that are valid
		$settings = $this->getDefaultSettings();

		// Override with database settings
		$dbSettings = $this->find('all', array('fields' => array('Setting.name', 'Setting.value', 'Setting.locked')));
		foreach ($dbSettings as $dbSetting) {
			$name = $dbSetting['Setting']['name'];
			$value = $dbSetting['Setting']['value'];
			$locked = $dbSetting['Setting']['locked'];

			// Key can be e.g. foo.bar.baz, corresponding to $settings['foo']['bar']['baz']
			$path = explode('.', $name);
			$current = &$settings;

			// Eat key parts one at a time
			while(($key = array_shift($path))) {

				// Not a valid setting - skip it
				if(!isset($current[$key])) {
					continue 2;

				// If we're on the last key part, set the value if it's overridable
				} elseif (empty($path) && !$current[$key]['locked']) {
					if (preg_match('/_enabled$/', $key)) {
						$value = (boolean) $value;
					}
					$current[$key] = array('value' => $value, 'locked' => $locked, 'source' => 'System settings');
				}

				// Keep track of progress through the settings array
				$current = &$current[$key];
			}
		}
		return $settings;
	}
}
