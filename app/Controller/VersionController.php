<?php

/**
 *
 * Version Controller for the SourceKettle system
 *
 * Exists solely to make ROOT/api/version return something.
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
App::uses('CakeEmail', 'Network/Email');

class VersionController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('api_index');
	}

/**
 * Get the API version via an API call - no login required
 */
	public function api_index() {
		$this->set('data', array(
			'version' => '0.0.1',
			));
		$this->layout = 'ajax';
		$this->render('/Elements/json');
	}
}
