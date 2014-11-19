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

	public function admin_set($locked = false) {

		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}

		$code = 200;
		$message = __("Settings updated.");
		if (!$this->Setting->saveSettingsTree($this->request->data, $locked)) {
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

}
