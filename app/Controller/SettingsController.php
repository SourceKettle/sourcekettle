<?php

/**
 *
 * SettingsController Controller for the DevTrack system
 * Provides the hard-graft control of the settings of the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 DevTrack Development Team 2012
 * @link			http://github.com/SourceKettle/devtrack
 * @package		DevTrack.Controller
 * @since		 DevTrack v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');

class SettingsController extends AppController {

	/**
	 * admin_index method
	 *
	 * @return void
	 */
	public function admin_index() {
		$this->Setting->recursive = 0;
		$this->set('register', $this->Setting->field('value', array('name' => 'register_enabled')));
		$this->set('sysadmin_email', $this->Setting->field('value', array('name' => 'sysadmin_email')));
		$this->set('repo_location', $this->Setting->field('value', array('name' => 'repo_location')));

		// Project options
		$this->set('features', array(
			'time'		=> $this->Setting->field('value', array('name' => 'feature_time_enabled')),
			'source'	 => $this->Setting->field('value', array('name' => 'feature_source_enabled')),
			'task'		=> $this->Setting->field('value', array('name' => 'feature_task_enabled')),
			'attachment' => $this->Setting->field('value', array('name' => 'feature_attachment_enabled'))
		));
	}

	public function admin_setEmail() {
		// Check form value provided and request is a post request
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Setting->id = $this->Setting->field('id', array('name' => 'sysadmin_email'));
		if ($this->Setting->exists() && Validation::email($this->request->data['Settings']['sysadmin_email'])) {
			$this->Setting->set('value', $this->request->data['Settings']['sysadmin_email']);
			$this->Setting->save();
		}
		$this->redirect(array('admin' => true, 'controller' => 'settings', 'action' => 'index'));
	}

	/**
	 * admin_setRegistration function.
	 *
	 * @access public
	 * @param mixed $value (default: null)
	 * @return void
	 */
	public function admin_setRegistration($value = null) {
		$this->admin_setField('register_enabled', $value);
	}

	/**
	 * admin_setFeatureTime function.
	 *
	 * @access public
	 * @param mixed $value (default: null)
	 * @return void
	 */
	public function admin_setFeatureTime($value = null) {
		$this->admin_setField('feature_time_enabled', $value);
	}

	/**
	 * admin_setFeatureTask function.
	 *
	 * @access public
	 * @param mixed $value (default: null)
	 * @return void
	 */
	public function admin_setFeatureTask($value = null) {
		$this->admin_setField('feature_task_enabled', $value);
	}

	/**
	 * admin_setFeatureSource function.
	 *
	 * @access public
	 * @param mixed $value (default: null)
	 * @return void
	 */
	public function admin_setFeatureSource($value = null) {
		$this->admin_setField('feature_source_enabled', $value);
	}

	/**
	 * admin_setFeatureAttachment function.
	 *
	 * @access public
	 * @param mixed $value (default: null)
	 * @return void
	 */
	public function admin_setFeatureAttachment($value = null) {
		$this->admin_setField('feature_attachment_enabled', $value);
	}

	/**
	 * admin_setField function.
	 *
	 * @access private
	 * @param mixed $field (default: null)
	 * @param mixed $value (default: null)
	 * @return void
	 */
	private function admin_setField($field = null, $value = null) {
		$this->Setting->id = $this->Setting->field('id', array('name' => $field));
		if (!$this->Setting->exists()) {
			$this->Flash->error('The specified setting does not exist in the database. Please create "'.$field.'" and try again.');
		} else if (in_array((int) $value, array(0,1))) {
			$this->Setting->set('value', $value);
			$this->Setting->save();
		} else {
			$this->Flash->error('Cannot set "'.$field.'" to a value other than 1 or 0. Please try again.');
		}
		$this->redirect(array('admin' => true, 'controller' => 'settings', 'action' => 'index'));
	}
}
