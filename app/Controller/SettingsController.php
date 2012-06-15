<?php

/**
 *
 * SettingsController Controller for the DevTrack system
 * Provides the hard-graft control of the settings of the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.Controller
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');

class SettingsController extends AppController {

    /**
     * index method
     *
     * @return void
     */
    public function admin_index() {
        $this->Setting->recursive = 0;
        $this->set('settings', $this->paginate());
    }

}
