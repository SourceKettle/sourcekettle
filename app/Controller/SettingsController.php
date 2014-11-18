<?php

/**
 *
 * SettingsController Controller for the SourceKettle system
 * Provides the hard-graft control of the settings of the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link			http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.Controller
 * @since		 SourceKettle v 0.1
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
		// Slight hack to make theme form work properly...
		$settings = $this->Setting->loadConfigSettings();
		$this->request->data = array('Setting' => array('UserInterface' => array('theme' => $settings['UserInterface']['theme']['value'])));
	}

	public function admin_set() {

		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}

		$code = 200;
		$message = __("Settings updated.");
		if (!$this->Setting->saveSettingsTree($this->request->data)) {
			$code = 500;
			$message = __("Failed to change settings");
		}

		if ($this->request->is('ajax')) {
			$this->set('data', array('code' => $code, 'message' => $message));
			$this->render('/Elements/json');
			return;
		} elseif($code == 200) {
			$this->Flash->info(__('Settings updated.'));
		} else {
			$this->Flash->error(__('There was a problem updating the settings.'));
		}
		return $this->redirect (array ('action' => 'index'));
	}

	function admin_setLock() {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}

		$code = 200;
		$message = '';

		foreach ($this->request->data as $name => $value) {
			if (strtolower($value) == 'true') {
				$value = 1;
			} else {
				$value = 0;
			}

			$name = preg_replace('/,/', '.', $name);
			$this->Setting->id = $this->Setting->field('id', array('name' => $name));
			if ($this->Setting->exists()) {
				$this->Setting->set('locked', $value);
				if (!$this->Setting->save()) {
					$code = 500;
					$message .= __('Failed to update setting "%s";', $name);
				}
			} else {
				$code = 404;
				$message .= __('Unknown setting "%s";', $name);
			}
		}

		if (empty($message)) {
			$message = __('Settings updated OK');
		}

		$this->set('data', array('code' => $code, 'message' => $message));
		$this->render('/Elements/json');
	}

}
