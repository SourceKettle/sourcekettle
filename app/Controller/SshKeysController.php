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
            $this->request->data['SshKey']['user_id'] = $this->Auth->user('id'); //Set the key to belong to the current user
            if ($this->SshKey->save($this->request->data)){
                $this->Session->setFlash(__('Your key was added successfully.'), 'default', array(), 'success');
                
                // Update the sync required flag
                $sync_required = $this->Setting->find('first', array('conditions' => array('name' => 'sync_required')));
                $sync_required['Setting']['value'] = 1;
                $this->Setting->save($sync_required);
                
                $this->log("[UsersController.addkey] sshkey[".$this->SshKey->getLastInsertID()."] added to user[".$this->Auth->user('id')."]", 'devtrack');
                $this->redirect(array('action'=>'view'));
            } else {
                $this->Session->setFlash(__('There was a problem saving your key. Please try again.'), 'default', array(), 'error');
            }
        }

        //update the page
        $user = $this->Auth->user();
        $this->SshKey->User->id = $user['id'];
        $this->request->data = $this->SshKey->User->read();
        $this->request->data['User']['password'] = null;
    }

    /**
     * Deletes a ssh key of the current user
     * @param type $id The id of the key to delete
     */
    public function delete($id = null){
        if ($this->request->is('post') && $id != null){
            //Find the key object
            $key = $this->SshKey->find('first', array(
                'conditions' => array('SshKey.id' => $id)
            ));

            if ($key['SshKey']['user_id'] == $this->Auth->user('id')){ //check the key belongs to the current user
                if ($this->SshKey->delete($key['SshKey'])){
                    $this->Session->setFlash(__('Your key was removed successfully.'), 'default', array(), 'success');
                    $this->log("[UsersController.deletekey] sshkey[".$id."] deleted by user[".$this->Auth->user('id')."]", 'devtrack');
                    
                    // Update the sync required flag
                    $sync_required = $this->Setting->find('first', array('conditions' => array('name' => 'sync_required')));
                    $sync_required['Setting']['value'] = 1;
                    $this->Setting->save($sync_required);
                } else {
                    $this->Session->setFlash(__('There was a problem removing your key. Please try again.'), 'default', array(), 'error');
                }
            } else {
                $this->Session->setFlash(__('2There was a problem removing your key. Please try again.'), 'default', array(), 'error');
            }
        }
        $this->redirect(array('action'=>'view'));
    }

    /**
     * Displays the ssh keys of the current user
     */
    public function view() {
        //update the page
        $user = $this->Auth->user();
        $this->SshKey->User->id = $user['id'];
        $this->request->data = $this->SshKey->User->read();
        $this->request->data['User']['password'] = null;
    }

}
