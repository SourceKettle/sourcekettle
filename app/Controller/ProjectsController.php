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
App::uses('AppProjectController', 'Controller');

class ProjectsController extends AppProjectController {

    /**
     * Project helpers
     * @var type
     */
    public $helpers = array('Time', 'GoogleChart.GoogleChart');

    public $uses = array('Project');

    /**
     * beforeFilter function.
     *
     * @access public
     * @return void
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
            'api_all',
            'api_view'
        );
    }

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
        $project = $this->_projectCheck($name);

        $number_of_open_tasks = $this->Project->Task->find('count', array('conditions' => array('Task.task_status_id' => 1, 'Project.id' => $project['Project']['id'])));
        $number_of_closed_tasks = $this->Project->Task->find('count', array('conditions' => array('Task.task_status_id' => 2, 'Project.id' => $project['Project']['id'])));
        $number_of_tasks = $number_of_closed_tasks + $number_of_open_tasks;
        $percent_of_tasks = $number_of_closed_tasks / $number_of_tasks * 100;

        $this->set(compact('number_of_open_tasks', 'number_of_closed_tasks', 'number_of_tasks', 'percent_of_tasks'));
        $this->set('events', $this->Project->fetchEventsForProject());
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

            // Create the project object with its data
            $this->Project->create();


            if ($this->Project->save($this->request->data)) {

                // Project has been saved
                // Now to add the creator as the first admin user on the project
                $data = array('Collaborator');
                $data['Collaborator']['user_id'] = $this->Auth->user('id');
                $data['Collaborator']['project_id'] = $this->Project->id;
                $data['Collaborator']['access_level'] = 2; //Project admin
                $this->Project->Collaborator->save($data);
                $this->log("[ProjectController.add] project[".$this->Project->id."] added by user[".$this->Auth->user('id')."]", 'devtrack');
                $this->log("[ProjectController.add] user[".$this->Auth->user('id')."] added to project[".$this->Project->id."] automatically as an admin", 'devtrack');


                // Create the actual repository - if it fails, delete the database content
                if(!$this->Project->Source->create()){
                    $this->log("[ProjectController.add] project[".$this->Project->id."] repository creation failed - automatically removing project data", 'devtrack');
                    $this->Project->delete();
                    $this->Session->setFlash(__('The project repository could not be created. Please try again.'), 'default', array(), 'error');
                } else {
                    $this->Session->setFlash(__('The project has been saved'), 'default', array(), 'success');
                }

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

    /***************************************************
    *                                                  *
    *            API SECTION OF CONTROLLER             *
    *             CAUTION: PUBLIC FACING               *
    *                                                  *
    ***************************************************/

    /**
     * api_view function.
     *
     * @access public
     * @param mixed $id (default: null)
     * @return void
     */
    public function api_view($id = null) {
        $this->layout = 'ajax';

        $this->Project->recursive = -1;
        $data = array();

        if ($id == null) {
            $this->response->statusCode(400);
            $data['error'] = 400;
            $data['message'] = 'Bad request, no project id specified.';
        }

        if ($id == 'all') {
            $this->api_all();
            return;
        }

        if (is_numeric($id)) {
            $this->Project->id = $id;

            if (!$this->Project->exists()) {
                $this->response->statusCode(404);
                $data['error'] = 404;
                $data['message'] = 'No project found of that ID.';
                $data['id'] = $id;
            } else {
                $project = $this->Project->read();

                // Collaborators
                $c = $this->Project->Collaborator->find('list', array('fields' => array('user_id'), 'conditions' => array('project_id' => $id)));
                $project['Project']['collaborators'] = array_values($c);

                // Repo Type
                $project['Project']['repo_type'] = $this->Project->RepoType->field('name');

                $_part_of_project = $this->Milestone->Project->hasRead($this->Auth->user('id'));
                $_public_project  = $this->Milestone->Project->field('public');
                $_is_admin = ($this->_api_auth_level() == 1);

                if ($_public_project || $_part_of_project || $_is_admin) {
                    $data = $project['Project'];
                } else {
                    $data['error'] = 401;
                    $data['message'] = 'Project found, but is not public.';
                    $data['id'] = $id;
                }
            }
        }

        $this->set('data',$data);
        $this->render('/Elements/json');
    }

    /**
     * api_all function.
     * ADMINS only
     *
     * @access public
     * @return void
     */
    public function api_all() {
        $this->layout = 'ajax';

        $this->Project->recursive = -1;
        $data = array();

        switch ($this->_api_auth_level()) {
            case 1:
                foreach ($this->Project->find("all") as $project) {
                    // Collaborators
                    $c = $this->Project->Collaborator->find('list', array('fields' => array('user_id'), 'conditions' => array('project_id' => $project['Project']['id'])));
                    $project['Project']['collaborators'] = array_values($c);

                    // Repo Type
                    $project['Project']['repo_type'] = $this->Project->RepoType->field('name', array('id' => $project['Project']['repo_type']));

                    $data[] = $project['Project'];
                }
                break;
            default:
                $this->response->statusCode(403);
                $data['error'] = 403;
                $data['message'] = 'You are not authorised to access this.';
        }

        $this->set('data',$data);
        $this->render('/Elements/json');
    }
}
