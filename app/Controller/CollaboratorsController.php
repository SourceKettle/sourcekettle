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
App::uses('AppProjectController', 'Controller');

class CollaboratorsController extends AppProjectController {

    /**
     * Project helpers
     * @var type
     */
    public $helpers = array('Time', 'GoogleChart.GoogleChart');

    /**
     * index method
     *
     * @param string $project project name
     * @return void
     */
    public function index($project = null) {
        $project = $this->_projectCheck($project, true, true);

        $collaborators = array();
        foreach (array(0, 1, 2) as $x) {
            $collaborators[$x] = $this->Collaborator->find(
                'all',
                array(
                    'conditions' => array(
                        'access_level' => $x,
                        'project_id' => $project['Project']['id']
                    )
                )
            );
        }

        $this->set('collaborators', $collaborators);
    }

    /**
     * add method
     *
     * @param string $name project name
     * @return void
     */
    public function add($project = null) {
        $project = $this->_projectCheck($project, true, true);
        $this->_add($project, $this->request->data, array('project' => $project['Project']['name'], 'action' => '.'));
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add() {
        $project = $this->_projectCheck($this->request->data['Project']['id']);
        $this->_add($project, $this->request->data, array('controller' => 'projects', 'action' => 'admin_view', $project['Project']['id']));
    }

    /**
     * _add function.
     * Alias for admin_add and add
     *
     * @access private
     * @param mixed $project
     * @param mixed $data
     * @param mixed $redirect
     * @return void
     */
    private function _add($project, $data, $redirect) {
        if (!$this->request->is('post')) throw new MethodNotAllowedException();

        // Check for existant user
        $this->Collaborator->User->recursive = -1;
        $user = $this->Collaborator->User->findByEmail($data['Collaborator']['name'], array('User.id', 'User.name'));

        if (empty($user)) {
            $this->Flash->error('The user specified does not exist. Please, try again.');
            $this->redirect($redirect);
        }

        // Check for existing association with this project
        if ($this->Collaborator->findByUserIdAndProjectId($user['User']['id'], $project['Project']['id'], array('id'))) {
            $this->Flash->error('The user specified is already collaborating in this project.');
            $this->redirect($redirect);
        }

        // Create details to attach user to this project
        $this->Collaborator->create();
        $collaborator = array(
            'project_id'   => $project['Project']['id'],
            'user_id'      => $user['User']['id'],
            'access_level' => 1
        );

        // Save the new collaborator
        if ($this->Collaborator->save($collaborator)) {
            $this->Flash->info($user['User']['name'].' has been added to the project');
        } else {
            $this->Flash->error($user['User']['name'].' could not be added to the project. Please, try again.');
        }

        $this->redirect($redirect);
    }

    /**
     * makeadmin
     * Allows users to premote a user to an admin
     *
     * @param string $project project name
     * @param string $id user to change
     * @return void
     */
    public function makeadmin($project = null, $id = null) {
        $this->changepermissionlevel($project, $id, 2);
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
     * @param string $project project name
     * @param string $id user to change
     * @return void
     */
    public function makeuser($project = null, $id = null) {
        $this->changepermissionlevel($project, $id, 1);
    }

    /**
     * admin_makeuser
     * Allows admin to premote a user to a regular user
     *
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
     * @param string $project project name
     * @param string $id user to change
     * @return void
     */
    public function makeguest($project = null, $id = null) {
        $this->changepermissionlevel($project, $id, 0);
    }

    /**
     * admin_makeguest
     * Allows admin to premote a user to an observer
     *
     * @param string $id user to change
     * @return void
     */
    public function admin_makeguest($id = null) {
        $this->_admin_changepermissionlevel($id, 0);
    }

    /**
     * changepermissionlevel
     *
     * @param string $project project name
     * @param string $id user to change
     * @param string $newaccesslevel new access level to assign
     * @return void
     */
    private function changepermissionlevel($project = null, $id = null, $newaccesslevel = 0){
        $project = $this->_projectCheck($project, true, true);
        $collaborator = $this->Collaborator->open($id);

        if ($newaccesslevel <= 1 && $collaborator['Collaborator']['access_level'] > 1) {
            // Count the number of admins
            $numAdmins = $this->Collaborator->find ('count', array (
                'fields' => 'Collaborator.id',
                'conditions' => array (
                    'project_id' => $project['Project']['id'],
                    'access_level >' => '1'
                )
            ));

            if ($numAdmins <= 1) {
                $this->Flash->errorReason('There must be at least one admin in the project');
                $this->redirect(array('project' => $project['Project']['name'], 'action' => '.'));
            }
        }

        // Save the changes to the user
        if ($this->Collaborator->set('access_level', $newaccesslevel) && $this->Collaborator->save()) {
            $this->Flash->info("Permissions level successfully changed for '".$collaborator['User']['name']."'");
        } else {
            $this->Flash->error("Permissions level for '".$collaborator['User']['name']."' not be updated. Please, try again.");
        }

        $this->redirect(array('project' => $project['Project']['name'], 'action' => '.'));
    }

    /**
     * admin_changepermissionlevel
     *
     * @param string $id collaborator to change
     * @param string $newaccesslevel new access level to assign
     * @return void
     */
    private function _admin_changepermissionlevel($id = null, $newaccesslevel = 0){
        $collaborator = $this->Collaborator->open($id);

        // Save the changes to the user
        if ($this->Collaborator->set('access_level', $newaccesslevel) && $this->Collaborator->save()) {
            $this->Flash->info("Permissions level successfully changed for '".$collaborator['User']['name']."'");
        } else {
            $this->Flash->error("Permissions level for '".$collaborator['User']['name']."' not be updated. Please, try again.");
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
    public function delete($project = null, $id = null) {
        $project = $this->_projectCheck($project, true, true);
        $collaborator = $this->Collaborator->open($id);

        $this->Flash->setUp();

        // Count the number of admins
        $numAdmins = $this->Collaborator->find('count', array (
            'fields' => 'DISTINCT Collaborator.id',
            'conditions' => array (
                'Collaborator.project_id' => $project['Project']['id'],
                'Collaborator.access_level >' => '1',
                'Collaborator.id !=' => $id
            )
        ));

        // If the user cannot delete due to no other admins
        if (!$numAdmins) {
            $this->Flash->errorReason('There must be at least one admin in the project');
            $this->redirect(array('project' => $project['Project']['name'], 'action' => '.'));
        }

        // If the user has confimed deletion
        if ($this->request->is('post') && $this->Flash->D($this->Collaborator->delete())) {
            $this->redirect(array('project' => $project['Project']['name'], 'action' => '.'));
        }

        $this->set('object', array(
            'name' => $collaborator['User']['name'],
            'id'   => $collaborator['Collaborator']['id']
        ));
        $this->set('objects', $this->Collaborator->preDelete());
        $this->render('/Elements/Project/delete');
    }

    /**
     * admin_delete method
     *
     * @param string $name project name
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null) {
        $collaborator = $this->Collaborator->open($id);

        if (!$this->request->is('post')) throw new MethodNotAllowedException();

        $this->Flash->setUp();
        $this->Flash->D($this->Collaborator->delete());
        $this->redirect(array('controller' => 'projects', 'action' => 'admin_view', $collaborator['Project']['id']));
    }
}
