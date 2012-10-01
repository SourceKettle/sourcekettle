<?php
/**
 *
 * SshKeysController Controller for the DevTrack system
 * Provides the hard-graft control of the ssh keys for users
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

class SshKeysController extends AppController {

    public $uses = array('SshKey', 'Setting');

    /**
     * Add an SSH key for the current user
     */
    public function add(){
        if ($this->request->is('post')){

            $this->request->data['SshKey']['user_id'] = $this->SshKey->_auth_user_id; //Set the key to belong to the current user

            if ($this->Flash->C($this->SshKey->save($this->request->data))){
                $this->Setting->syncRequired(); // Update the sync required flag

                $this->log("[UsersController.addkey] sshkey[".$this->SshKey->getLastInsertID()."] added to user[".$this->SshKey->_auth_user_id."]", 'devtrack');
                $this->redirect(array('action'=>'view'));
            }
        }

        $this->SshKey->User->id = $this->SshKey->_auth_user_id;
        $this->request->data = $this->SshKey->User->read();
        $this->request->data['User']['password'] = null;
    }

    /**
     * Deletes a ssh key of the current user
     * @param type $id The id of the key to delete
     */
    public function delete($id = null){
        if ($this->request->is('post') && $id != null){
            $this->SshKey->id = $id;

            if (!$this->SshKey->exists()) {
                throw new NotFoundException(__('Invalid SSH Key'));
            }

            if ($this->SshKey->field('user_id') != $this->SshKey->_auth_user_id) {
                throw new ForbiddenException(__('Ownership required'));
            }

            $this->Flash->setUp();
            if ($this->Flash->D($this->SshKey->delete())){
                $this->log("[UsersController.deletekey] sshkey[".$id."] deleted by user[".$this->Auth->user('id')."]", 'devtrack');
                $this->Setting->syncRequired(); // Update the sync required flag
            }
        }
        $this->redirect(array('action'=>'view'));
    }

    /**
     * Displays the ssh keys of the current user
     */
    public function view() {
        $this->SshKey->User->id = $this->SshKey->_auth_user_id;
        $this->request->data = $this->SshKey->User->read();
        $this->request->data['User']['password'] = null;
    }

}
