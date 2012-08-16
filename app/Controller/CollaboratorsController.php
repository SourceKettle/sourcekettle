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
    public $helpers = array('Time', 'GoogleChart.GoogleChart');

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

        // Lock out those who arnt admins
        $this->Collaborator->Project->id = $project['Project']['id'];
        if ( !$this->Collaborator->Project->isAdmin($this->Auth->user('id')) ) throw new ForbiddenException(__('You are not a admin of this project'));

        $this->Collaborator->Project->recursive = 2;
        $this->set('project', $this->Collaborator->Project->read());
        $this->set('isAdmin', $this->Collaborator->Project->isAdmin($this->Auth->user('id')));
    }

    /**
     * add method
     *
     * @param string $name project name
     * @return void
     */
    public function add($name = null) {
        if (!$this->request->is('post')) throw new MethodNotAllowedException();

        // Check for existant project
        $project = $this->Collaborator->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        // Lock out those who arnt admins
        $this->Collaborator->Project->id = $project['Project']['id'];
        if ( !$this->Collaborator->Project->isAdmin($this->Auth->user('id')) ) throw new ForbiddenException(__('You are not an admin of this project'));

        // Check for existant user
        $this->Collaborator->User->recursive = -1;
        $user = $this->Collaborator->User->findByEmail($this->request->data['Collaborator']['name'], array('User.id', 'User.name'));

        if ( empty($user) ) {
            $this->Session->setFlash(__('The user specified does not exist. Please, try again.'), 'default', array(), 'error');
        } else {
            // Check for existing association with this project
            $collaborator = $this->Collaborator->findByUserIdAndProjectId ($user['User']['id'], $project['Project']['id'], array ('id'));
            if (!empty($collaborator)) {
                $this->Session->setFlash(__('The user specified is already collaborating in this project. Please, try again.'), 'default', array(), 'error');
            } else {
                // Create details to attach user to this project
                unset($this->request->data['Collaborator']['name']);
                $this->request->data['Collaborator']['project_id'] = $project['Project']['id'];
                $this->request->data['Collaborator']['user_id'] = $user['User']['id'];
                $this->request->data['Collaborator']['access_level'] = 1;

                // Save the new collaborator
                $this->Collaborator->create();
                if ($this->Collaborator->save($this->request->data)) {
                    $this->Session->setFlash(__($user['User']['name'] . ' has been added to the project'), 'default', array(), 'success');
                } else {
                    $this->Session->setFlash(__($user['User']['name'] . ' could not be added to the project. Please, try again.'), 'default', array(), 'error');
                }
            }
        }
        $this->redirect(array('project' => $name, 'action' => '.'));
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add() {
        if (!$this->request->is('post')) throw new MethodNotAllowedException();

        // Check for existant project
        $project = $this->Collaborator->Project->getProject($this->request->data['Project']['id']);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        // Check for existant user
        $this->Collaborator->User->recursive = -1;
        $user = $this->Collaborator->User->findByEmail($this->request->data['Collaborator']['name'], array('User.id', 'User.name'));

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
            if ($this->Collaborator->save($this->request->data, true, array('project_id', 'user_id', 'access_level'))) {
                $this->Session->setFlash(__($user['User']['name'] . ' has been added to the project'), 'default', array(), 'success');
            } else {
                $this->Session->setFlash(__($user['User']['name'] . ' could not be added to the project. Please, try again.'), 'default', array(), 'error');
            }
        }
        $this->redirect(array('controller' => 'projects', 'action' => 'admin_view', $project['Project']['id']));
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
     * admin_makeadmin
     * Allows admin to premote a user to an admin of
     * a project
     *
     * @param string $id collaborator to change
     * @return void
     */
    public function admin_makeadmin($id = null) {
        $this->_admin_changepermissionlevel($id, 2);
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
     * admin_makeuser
     * Allows admin to premote a user to a regular user
     *
     * @param string $name project name
     * @param string $id user to change
     * @return void
     */
    public function admin_makeuser($id = null) {
        $this->_admin_changepermissionlevel($id, 1);
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
     * admin_makeguest
     * Allows admin to premote a user to an observer
     *
     * @param string $name project name
     * @param string $id user to change
     * @return void
     */
    public function admin_makeguest($id = null) {
        $this->_admin_changepermissionlevel($id, 0);
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

        // Lock out those who arnt admins
        $this->Collaborator->Project->id = $project['Project']['id'];
        if ( !$this->Collaborator->Project->isAdmin($this->Auth->user('id')) ) throw new ForbiddenException(__('You are not a admin of this project'));

        // Check for existant collaborator for the user and this project
        $this->Collaborator->id = $this->Collaborator->field('id', array('user_id' => $id, 'project_id' => $project['Project']['id']));

        if (!$this->Collaborator->exists()) {
            $this->Session->setFlash(__('The user specified does not exist. Please, try again.'), 'default', array(), 'error');
        } else {
            $collaborator = $this->Collaborator->read();

            $current_access_level = $collaborator['Collaborator']['access_level'];
            $can_change = true;
            if ($newaccesslevel <= 1 && $current_access_level > 1) {
                // Count the number of admins
                $numAdmins = $this->Collaborator->find ('count', array (
                    'fields' => 'Collaborator.id',
                    'conditions' => array ('project_id' => $project['Project']['id'], 'access_level >' => '1')
                ));

                if ($numAdmins <= 1) {
                    $this->Session->setFlash(__('There must be at least one admin in the project.'), 'default', array(), 'error');
                    $can_change = false;
                }
            }

            if ($can_change) {
                // Find additional details for the user being changed - only needed for flash message
                $user_name = $this->Collaborator->User->field('name', array('User.id' => $id));

                $this->Collaborator->set('access_level', $newaccesslevel);

                // Save the changes to the user
                if ($this->Collaborator->save(null, true, array('access_level'))) {
                    $this->Session->setFlash(__("Permissions level successfully changed for '${user_name}'"), 'default', array(), 'success');
                } else {
                    $this->Session->setFlash(__("Permissions level for '${user_name}' not be updated. Please, try again."), 'default', array(), 'error');
                }
            }
        }
        $this->redirect(array('project' => $name, 'action' => '.'));
    }

    /**
     * admin_changepermissionlevel
     *
     * @param string $id collaborator to change
     * @param string $newaccesslevel new access level to assign
     * @return void
     */
    private function _admin_changepermissionlevel($id = null, $newaccesslevel = 0){
        // Check for existant collaborator for the user and this project
        $this->Collaborator->id = $id;

        if (!$this->Collaborator->exists()) {
            $this->Session->setFlash(__('The user specified does not exist. Please, try again.'), 'default', array(), 'error');
        } else {
            $collaborator = $this->Collaborator->read();

            // Find additional details for the user being changed - only needed for flash message
            $user_name = $this->Collaborator->User->field('name', array('User.id' => $collaborator['Collaborator']['user_id']));

            $this->Collaborator->set('access_level', $newaccesslevel);

            // Save the changes to the user
            if ($this->Collaborator->save(null, true, array('access_level'))) {
                $this->Session->setFlash(__("Permissions level successfully changed for '${user_name}'"), 'default', array(), 'success');
            } else {
                $this->Session->setFlash(__("Permissions level for '${user_name}' not be updated. Please, try again."), 'default', array(), 'error');
            }
        }
        $this->redirect(array('controller' => 'projects', 'action' => 'admin_view', $collaborator['Collaborator']['project_id']));
    }

    /**
     * delete method
     *
     * @param string $name project name
     * @param string $id
     * @return void
     */
    public function delete($name = null, $id = null) {
        if (!$this->request->is('post')) throw new MethodNotAllowedException();

        // Check for existant project
        $project = $this->Collaborator->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        $this->Collaborator->id = $id;
        if (!$this->Collaborator->exists()) throw new NotFoundException(__('Invalid collaborator'));

        // Lock out those who arnt admins
        $this->Collaborator->Project->id = $project['Project']['id'];
        if ( !$this->Collaborator->Project->isAdmin($this->Auth->user('id')) ) throw new ForbiddenException(__('You are not a admin of this project'));

        // Count the number of admins
        $numAdmins = $this->Collaborator->find ('count', array (
            'fields' => 'DISTINCT Collaborator.id',
            'conditions' => array ('project_id' => $project['Project']['id'], 'access_level >' => '1')
        ));

        if ($numAdmins <= 1) {
            $this->Session->setFlash(__('There must be at least one admin in the project.'), 'default', array(), 'error');
        } else if ($this->Collaborator->delete()) {
            $this->Session->setFlash(__('Collaborator deleted'), 'default', array(), 'success');
        } else {
            $this->Session->setFlash(__('Collaborator was not deleted'), 'default', array(), 'error');
        }
        $this->redirect(array('project' => $name, 'action' => '.'));
    }

    /**
     * admin_delete method
     *
     * @param string $name project name
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Collaborator->id = $id;
        $project_id = $this->Collaborator->field('project_id', array('id' => $this->Collaborator->id));
        if (!$this->Collaborator->exists()) {
            throw new NotFoundException(__('Invalid collaborator'));
        }
        if ($this->Collaborator->delete()) {
            $this->Session->setFlash(__('Collaborator deleted'), 'default', array(), 'success');
        } else {
            $this->Session->setFlash(__('Collaborator was not deleted'), 'default', array(), 'error');
        }
        $this->redirect(array('controller' => 'projects', 'action' => 'admin_view', $project_id));
    }
}
