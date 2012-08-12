<?php

/**
 *
 * ProjectsController Controller for the DevTrack system
 * Provides the hard-graft control of the projects contained within the system
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

class ProjectsController extends AppController {

    /**
     * Project helpers
     * @var type
     */
    public $helpers = array('Time', 'GoogleChart.GoogleChart');

    public $uses = array('Project', 'GitCake.GitCake');

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->Project->Collaborator->recursive = 0;
        $this->set('projects', $this->Project->Collaborator->find('all', array('conditions' => array('Collaborator.user_id' => $this->Auth->user('id')))));
    }

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index() {
        $this->Project->recursive = 0;
        $this->set('projects', $this->paginate());
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view($name = null) {
        // Check for valid project name
        $project = $this->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        $this->Project->id = $project['Project']['id'];

        // Lock out those who arnt members
        if ( !$this->Project->hasRead($this->Auth->user('id')) ) throw new ForbiddenException(__('You are not a member of this project'));

        $this->set('events', $this->Project->fetchEventsForProject());
        $this->set('isAdmin', $this->Project->isAdmin($this->Auth->user('id')));
        $this->set('project', $project);
    }

    /**
     * admin_view method
     *
     * @param string $id
     * @return void
     */
    public function admin_view($name = null) {
        // Check for valid project name
        $project = $this->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        $this->Project->recursive = 2;
        $this->Project->id = $project['Project']['id'];
        $this->request->data = $this->Project->read();
    }

    /**
     * add
     * Create a new project for the current user
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Project->create();
            if ($this->Project->save($this->request->data)) {

                // Project has been saved
                // Now to add the creator as the first admin user on the project
                $data = array('Collaborator');
                $data['Collaborator']['user_id'] = $this->Auth->user('id');
                $data['Collaborator']['project_id'] = $this->Project->id;
                $data['Collaborator']['access_level'] = 2; //Project admin
                $this->Project->Collaborator->save($data);

                $this->Session->setFlash(__('The project has been saved'), 'default', array(), 'success');
                $this->log("[ProjectController.add] project[".$this->Project->id."] added by user[".$this->Auth->user('id')."]", 'devtrack');
                $this->log("[ProjectController.add] user[".$this->Auth->user('id')."] added to project[".$this->Project->id."] automatically as an admin", 'devtrack');

                // Create a repo
                $this->Project->Source->create();

                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The project could not be saved. Please, try again.'), 'default', array(), 'error');
            }
        }
        $repoTypes = $this->Project->RepoType->find('list');
        $this->set(compact('repoTypes'));
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            $this->Project->create();
            if ($this->Project->save($this->request->data)) {
                $this->Session->setFlash(__('The project has been saved'), 'default', array(), 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The project could not be saved. Please, try again.'), 'default', array(), 'error');
            }
        }
        $repoTypes = $this->Project->RepoType->find('list');
        $this->set(compact('repoTypes'));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($name = null) {
        // Check for valid project name
        $project = $this->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        $this->Project->id = $project['Project']['id'];

        // Lock out those who arnt admins
        if ( !$this->Project->isAdmin($this->Auth->user('id')) ) throw new ForbiddenException(__('You are not an admin of this project'));

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Project->save($this->request->data)) {
                $this->Session->setFlash(__('The project has been saved'), 'default', array(), 'success');
                $this->log("[ProjectController.edit] user[".$this->Auth->user('id')."] edited project[".$this->Project->id."]", 'devtrack');

                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The project could not be saved. Please, try again.'), 'default', array(), 'error');
            }
        } else {
            $this->set('project', $project);
            $this->set('isAdmin', $this->Project->isAdmin($this->Auth->user('id')));
            $this->request->data = $this->Project->read(null, $this->Project->id);
        }
    }

    /**
     * admin_edit method
     *
     * @param string $id
     * @return void
     */
    public function admin_edit($name = null) {
        $project = $this->Project->getProject($name);
        $id = $project['Project']['id'];
        $this->Project->id = $id;
        if (!$this->Project->exists()) {
            throw new NotFoundException(__('Invalid project'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Project->save($this->request->data)) {
                $this->Session->setFlash(__('The project has been saved'), 'default', array(), 'success');
                $this->redirect(array('action' => 'admin_view', $this->Project->id));
            } else {
                $this->Session->setFlash(__('The project could not be saved. Please, try again.'), 'default', array(), 'error');
            }
        } else {
            $this->request->data = $this->Project->read(null, $id);
        }
        $repoTypes = $this->Project->RepoType->find('list');
        $this->set(compact('repoTypes'));
    }

    /**
     * delete
     * remove a project and its sub components from the database
     *
     * @param string $name
     *
     * @return void
     */
    public function delete($name = null) {
        // Check for valid project name
        $project = $this->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        $this->Project->id = $project['Project']['id'];

        // Lock out those who arnt admins
        if ( !$this->Project->isAdmin($this->Auth->user('id')) ) throw new ForbiddenException(__('You are not an admin of this project'));

        // Only allow form submissions
        if ( !$this->request->is('post') ) throw new MethodNotAllowedException();

        if ($this->Project->delete()) {
            $this->Session->setFlash(__('Project deleted'), 'default', array(), 'success');
            $this->log("[ProjectController.delete] project[".$this->Project->id."] was deleted by user[".$this->Auth->user('id')."]", 'devtrack');
        } else {
            $this->Session->setFlash(__('Project was not deleted'), 'default', array(), 'error');
        }
        $this->redirect(array('action' => 'index'));
    }

    /**
     * admin_delete method
     *
     * @param string $id
     * @return void
     */
    public function admin_delete($name = null) {
        $project = $this->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        $this->Project->id = $project['Project']['id'];

        if (!$this->request->is('post')) throw new MethodNotAllowedException();

        if (!$this->Project->exists()) throw new NotFoundException(__('Invalid project'));

        if ($this->Project->delete()) {
            $this->Session->setFlash(__('Project deleted'), 'default', array(), 'success');
            $this->redirect(array('action' => 'admin_index'));
        }
        $this->Session->setFlash(__('Project was not deleted'), 'default', array(), 'error');
        $this->redirect(array('action' => 'admin_index'));
    }

}
