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
     * admin_index method
     *
     * @return void
     */
    public function admin_index() {
        $this->Setting->recursive = 0;
        $this->set('register', $this->Setting->field('value', array('name' => 'register_enabled')));
    }

    /**
     * admin_edit method
     *
     * @return void
     */
    public function admin_edit($change = null) {
        foreach ( json_decode($change, true) as $key => $value ) {

            if ( !($setting_id = $this->Setting->field('id', array('name' => $key))) ) {
                $this->log("ERROR: [SettingsController.admin_edit] user[".$this->Auth->user('id')."] tried to set setting[".$key."] which does not exist", 'devtrack');
            }
            $data = array();
            $data['Setting']['id'] = $setting_id;
            $data['Setting']['value'] = $value;

            if ($this->Setting->save($data)) {
                $this->Session->setFlash(__('Setting "'.$key.'" updated'), 'default', array(), 'success');
                $this->log("[SettingsController.admin_edit] user[".$this->Auth->user('id')."] changed setting[".$key."] to value \"".$value."\"", 'devtrack');
            } else {
                $this->Session->setFlash(__('Setting "'.$key.'" could not be saved. Please, try again.'), 'default', array(), 'error');
            }
        }
        $this->redirect(array('admin' => true, 'controller' => 'settings', 'action' => 'index'));
    }

}
