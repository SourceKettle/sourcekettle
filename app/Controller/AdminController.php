<?php

/**
 *
 * AdminController Controller for the SourceKettle system
 * Provides the hard-graft overview of the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	SourceKettle Development Team 2012
 * @link		http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.Controller
 * @since		SourceKettle v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');

class AdminController extends AppController {

	public $useTable = false;

/**
 * index method
 *
 * @return void
 */
	public function admin_index() {
	}

	// This is the same as the ode in AppController, but we should make sure
	// that ONLY sysadmins have access, even if we later change the defaults in AppController.
	// So, don't call parent function.
	public function isAuthorized($user) {
		if (@$user['is_admin'] == 1) {
			return true;
		}
		return false;
	}

}
