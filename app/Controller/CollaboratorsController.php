<?php

/**
 *
 * CollaboratorsController Controller for the DevTrack system
 * Provides the hard-graft control of the colaborators contained within the system
 * Including CRUD, admin CRUD and API control
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

class CollaboratorsController extends AppController {

    /**
     * Project helpers
     * @var type
     */
    public $helpers = array('Time', 'GoogleChart.GoogleChart', 'ProjectActivity');

    /**
     * index method
     *
     * @param string $name project name
     * @return void
     */
    public function index($name = null) {
        // Check for existant project
        $project = $this->Collaborator->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        $this->Collaborator->Project->recursive = 2;
        $this->set('project', $this->Collaborator->Project->find('first', array('conditions' => array('Project.id' => $project['Project']['id']))));
    }

    /**
     * add method
     *
     * @param string $name project name
     * @return void
     */
    public function add($name = null) {
        // Check for existant project
        $project = $this->Collaborator->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        if ($this->request->is('post')) {
            // Check for existant user
            $user = $this->Collaborator->User->find('first', array('conditions' => array('User.email' => $this->request->data['Collaborator']['name']), 'fields' => array('User.id', 'User.name')));
            if ( empty($user) ) {
                $this->Session->setFlash(__('The user specified does not exist. Please, try again.'), 'default', array(), 'error');
            } else {

                // Create details to attach user to this project
                unset($this->request->data['Collaborator']['name']);
                $this->request->data['Collaborator']['project_id'] = $project['Project']['id'];
                $this->request->data['Collaborator']['user_id'] = $user['User']['id'];
                $this->request->data['Collaborator']['access_level'] = 1;

                // Save the new collaborator
                $this->Collaborator->create();
                if ($this->Collaborator->save($this->request->data)) {
                    $this->Session->setFlash(__($user['User']['name'] . ' has been added to the project', 'default', array(), 'success'));
                } else {
                    $this->Session->setFlash(__($user['User']['name'] . ' could not be added to the project. Please, try again.', 'default', array(), 'error'));
                }
            }
        }
        $this->redirect(array('project' => $name, 'action' => '.'));
    }

    /**
     * makeadmin
     * Allows users to premote a user to an admin
     *
     * @param string $name project name
     * @param string $id user to change
     * @return void
     */
    public function makeadmin($name = null, $id = null) {
        $this->changepermissionlevel($name, $id, 2);
    }

    /**
     * makeuser
     * Allows users to premote a user to a regular user
     *
     * @param string $name project name
     * @param string $id user to change
     * @return void
     */
    public function makeuser($name = null, $id = null) {
        $this->changepermissionlevel($name, $id, 1);
    }

    /**
     * makeguest
     * Allows users to premote a user to an observer
     *
     * @param string $name project name
     * @param string $id user to change
     * @return void
     */
    public function makeguest($name = null, $id = null) {
        $this->changepermissionlevel($name, $id, 0);
    }

    /**
     * changepermissionlevel
     *
     * @param string $name project name
     * @param string $id user to change
     * @param string $newaccesslevel new access level to assign
     * @return void
     */
    private function changepermissionlevel($name = null, $id = null, $newaccesslevel = 0){
        // Check for existant project
        $project = $this->Collaborator->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        // Check for existant collaborator for the user and this project
        $collaborator = $this->Collaborator->find('first', array('conditions' => array('Collaborator.user_id' => $id, 'Collaborator.project_id' => $project['Project']['id']), 'fields' => array('Collaborator.id', 'Collaborator.access_level')));
        $this->Collaborator->id = $collaborator['Collaborator']['id'];

        if (!$this->Collaborator->exists()) {
            $this->Session->setFlash(__('The user specified does not exist. Please, try again.'), 'default', array(), 'error');
        } else {
            // Find additional details for the user being changed - only needed for flash message
            $user = $this->Collaborator->User->find('first', array('conditions' => array('User.id' => $id), 'fields' => array('User.name')));

            // Create new details to paste over existing ones
            $newuser = array();
            $newuser['Collaborator']['id'] = $collaborator['Collaborator']['id'];
            $newuser['Collaborator']['project_id'] = $project['Project']['id'];
            $newuser['Collaborator']['user_id'] = $id;
            $newuser['Collaborator']['access_level'] = $newaccesslevel;

            // Save the changes to the user
            if ($this->Collaborator->save($newuser)) {
                $this->Session->setFlash(__("Permissions level successfully changed for '".$user['User']['name']."'.", 'default', array(), 'success'));
            } else {
                $this->Session->setFlash(__("Permissions level for '".$user['User']['name']."' not be updated. Please, try again.", 'default', array(), 'error'));
            }
        }
        $this->redirect(array('project' => $name, 'action' => '.'));
    }

    /**
     * delete method
     *
     * @param string $name project name
     * @param string $id
     * @return void
     */
    public function delete($name = null, $id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Collaborator->id = $id;
        if (!$this->Collaborator->exists()) {
            throw new NotFoundException(__('Invalid collaborator'));
        }
        if ($this->Collaborator->delete()) {
            $this->Session->setFlash(__('Collaborator deleted', 'default', array(), 'success'));
        } else {
            $this->Session->setFlash(__('Collaborator was not deleted', 'default', array(), 'error'));
        }
        $this->redirect(array('project' => $name, 'action' => '.'));
    }
}
