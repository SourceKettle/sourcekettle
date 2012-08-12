<?php
/**
 *
 * MilestonesController Controller for the DevTrack system
 * Provides the hard-graft control of the Milestones for projects
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

class MilestonesController extends AppController {

    /*
     * _projectCheck
     * Space saver to ensure user can view content
     * Also sets commonly needed variables related to the project
     *
     * @param $name string Project name
     */
    private function _projectCheck($name) {
        // Check for existent project
        $project = $this->Milestone->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));
        $this->Milestone->Project->id = $project['Project']['id'];

        $user = $this->Auth->user('id');

        // Lock out those who are not guests
        if ( !$this->Milestone->Project->hasRead($user) ) throw new ForbiddenException(__('You are not a member of this project'));

        $this->set('project', $project);
        $this->set('isAdmin', $this->Milestone->Project->isAdmin($user));

        return $project;
    }

    /**
     * index method
     *
     * @return void
     */
    public function index($project = null) {
        $project = $this->_projectCheck($project);

        $this->Milestone->recursive = 0;
        $this->set('milestones', $this->paginate());
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view($project = null, $id = null) {
        $project = $this->_projectCheck($project);

        $this->Milestone->id = $id;
        if (!$this->Milestone->exists()) {
            throw new NotFoundException(__('Invalid milestone'));
        }
        $this->set('milestone', $this->Milestone->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add($project = null) {
        $project = $this->_projectCheck($project);

        if ($this->request->is('post')) {
            $this->Milestone->create();
            if ($this->Milestone->save($this->request->data)) {
                $this->Session->setFlash(__('The milestone has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The milestone could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Milestone->Project->find('list');
        $this->set(compact('projects'));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($project = null, $id = null) {
        $project = $this->_projectCheck($project);

        $this->Milestone->id = $id;
        if (!$this->Milestone->exists()) {
            throw new NotFoundException(__('Invalid milestone'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Milestone->save($this->request->data)) {
                $this->Session->setFlash(__('The milestone has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The milestone could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Milestone->read(null, $id);
        }
        $projects = $this->Milestone->Project->find('list');
        $this->set(compact('projects'));
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($project = null, $id = null) {
        $project = $this->_projectCheck($project);

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Milestone->id = $id;
        if (!$this->Milestone->exists()) {
            throw new NotFoundException(__('Invalid milestone'));
        }
        if ($this->Milestone->delete()) {
            $this->Session->setFlash(__('Milestone deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Milestone was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}
