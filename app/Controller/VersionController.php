<?php

/**
 *
 * Version Controller for the DevTrack system
 *
 * Exists solely to make ROOT/api/version return something.
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
App::uses('CakeEmail', 'Network/Email');

class VersionController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index');
    }

    /**
     * Register a user via an API call
     */
    public function api_index() {
        $this->set('data', array(
            'version' => '0.0.1',
            ));
        $this->layout = 'ajax';
        $this->render('/Elements/json');
    }
}
