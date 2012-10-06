<?php
/**
*
* SetupController for the DevTrack system
* Controller for setup page
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

class SetupController extends AppController {

    public $name = 'Setup';

    public $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(); //does not require login to use all actions in this controller
    }

    public function index() {
    }
}
