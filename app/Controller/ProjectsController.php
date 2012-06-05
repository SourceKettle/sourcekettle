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

    /**
     * fetch_id_from_name
     * Translate all Project names to ids
     * If onld style URL is requested, ensure redirect to the new format
     *
     * @param $name type id or name of project
     * @param $redirect boolean true if a redirect is desired
     *
     * @return int project id
     */
    private function fetch_id_from_name($name = null, $redirect = false) {
        // Allow for dual routing
        if ( is_numeric($name) ) {
            // Ensure all Project URLs are in the new format, if not, redirect based on id
            if ($redirect) {
                $project = $this->Project->find('first', array('conditions' => array('Project.id' => $name)));
                $this->redirect(array('action' => $this->params['action'], 'controller' => 'projects', 'project' => $project['Project']['name']));
            }
            return $name;
        }
        $project = $this->Project->find('first', array('conditions' => array('Project.name' => $name)));
        return $project['Project']['id'];
    }
    
    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->Project->Collaborator->recursive = 0;
        $this->set('projects', $this->Project->Collaborator->find('all', array('conditions' => array('Collaborator.user_id' => $this->Auth->user('id')))));
        $this->paginate();
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
        $id = $this->fetch_id_from_name($name, true);
        
        $this->Project->id = $id;
        if (!$this->Project->exists()) {
            throw new NotFoundException(__('Invalid project'));
        }
        $this->set('project', $this->Project->read(null, $id));
    }
        
    /**
     * admin_view method
     *
     * @param string $id
     * @return void
     */
    public function admin_view($name = null) {
        $id = $this->fetch_id_from_name($name);

        $this->Project->id = $id;
        if (!$this->Project->exists()) {
            throw new NotFoundException(__('Invalid project'));
        }
        $this->set('project', $this->Project->read(null, $id));
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
        $id = $this->fetch_id_from_name($name, true);
        
        $this->Project->id = $id;
        if (!$this->Project->exists()) {
            throw new NotFoundException(__('Invalid project'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Project->save($this->request->data)) {
                $this->Session->setFlash(__('The project has been saved'), 'default', array(), 'success');
                $this->redirect(array('action' => 'index'));
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
     * admin_edit method
     *
     * @param string $id
     * @return void
     */
    public function admin_edit($name = null) {
        $id = $this->fetch_id_from_name($name);

        $this->Project->id = $id;
        if (!$this->Project->exists()) {
            throw new NotFoundException(__('Invalid project'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Project->save($this->request->data)) {
                $this->Session->setFlash(__('The project has been saved'), 'default', array(), 'success');
                $this->redirect(array('action' => 'index'));
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
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($name = null) {
        $id = $this->fetch_id_from_name($name);

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Project->id = $id;
        if (!$this->Project->exists()) {
            throw new NotFoundException(__('Invalid project'));
        }
        if ($this->Project->delete()) {
            $this->Session->setFlash(__('Project deleted'), 'default', array(), 'success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Project was not deleted'), 'default', array(), 'error');
        $this->redirect(array('action' => 'index'));
    }

    /**
     * admin_delete method
     *
     * @param string $id
     * @return void
     */
    public function admin_delete($name = null) {
        $id = $this->fetch_id_from_name($name);

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Project->id = $id;
        if (!$this->Project->exists()) {
            throw new NotFoundException(__('Invalid project'));
        }
        if ($this->Project->delete()) {
            $this->Session->setFlash(__('Project deleted'), 'default', array(), 'success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Project was not deleted'), 'default', array(), 'error');
        $this->redirect(array('action' => 'index'));
    }
}
