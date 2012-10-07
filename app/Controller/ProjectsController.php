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
    public $helpers = array('Time', 'GoogleChart.GoogleChart', 'Paginator');

    public $uses = array('Project', 'RepoType');

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

    public function history($project) {
        $project = $this->_projectCheck($project);

        $this->set('historyCount', 25);
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->Project->Collaborator->recursive = 0;

        $projects = $this->Project->Collaborator->find(
          'all', array(
            'conditions' => array('Collaborator.user_id' => $this->Project->_auth_user_id),
            'order' => array('Project.modified DESC')
          )
        );
        $this->set('projects', $projects);
    }

    public function public_projects() {
        $this->Project->recursive = -1;
        $this->paginate = array(
            'conditions' => array('Project.public' => true),
            'limit' => 15,
            'order' => 'Project.modified DESC'
        );
        $projects = $this->paginate('Project');

        $this->set('projects', $projects);
    }

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index() {
        if ($this->request->is('post') && isset($this->request->data['Project']['name']) && $project = $this->request->data['Project']['name']) {
            if ($project = $this->Project->findByName($project)) {
                $this->redirect(array('action' => 'view', $project['Project']['id']));
            } else {
                $this->Flash->error('The specified Project does not exist. Please try again.');
            }
        }
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

        $number_of_open_tasks = $this->Project->Task->find('count', array('conditions' => array('Task.task_status_id <' => 4, 'Task.project_id' => $project['Project']['id'])));
        $number_of_closed_tasks = $this->Project->Task->find('count', array('conditions' => array('Task.task_status_id' => 4, 'Task.project_id' => $project['Project']['id'])));
        $number_of_tasks = $number_of_closed_tasks + $number_of_open_tasks;

        $percent_of_tasks = 0;
        if($number_of_tasks > 0){
            $percent_of_tasks = round($number_of_closed_tasks / $number_of_tasks * 100, 1);
        }

        $_o_milestones = $this->Project->Milestone->getOpenMilestones();
        $_o_milestones = $this->Project->Milestone->find('all', array('conditions' => array('Milestone.id' => array_values($_o_milestones)), 'order' => 'Milestone.created ASC'));
        if (empty($_o_milestones)) {
            $milestone = null;
        } else {
            $milestone = $_o_milestones[0];
        }

        $numCollab = sizeof($this->Project->Collaborator->findAllByProjectId($project['Project']['id']));

        $this->set(compact('milestone', 'number_of_open_tasks', 'number_of_closed_tasks', 'number_of_tasks', 'percent_of_tasks', 'numCollab'));
        $this->set('historyCount', 8);
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
        $repoTypes = $this->Project->RepoType->find('list');

        if ($this->request->is('post')) {

            // Create the project object with its data
            $this->Project->create();

            // Lets vet the data coming in
            $_request_data = array(
                'Project' => $this->request->data['Project'],
                'Collaborator' => array(
                    array(
                        'user_id' => $this->Project->_auth_user_id,
                        'access_level' => 2 // Project admin
                    )
                )
            );

            if ($this->Project->saveAll($_request_data)) {
                // Need to know the repo type so we can skip repo creation if necessary...
                $repo_type = $repoTypes[$_request_data['Project']['repo_type']];

                $this->log("[ProjectController.add] project[".$this->Project->id."] added by user[".$this->Project->_auth_user_id."]", 'devtrack');
                $this->log("[ProjectController.add] user[".$this->Project->_auth_user_id."] added to project[".$this->Project->id."] automatically as an admin", 'devtrack');

                // Create the actual repository, if required - if it fails, delete the database content
                if (strtolower($repo_type) == 'none') {
                    $this->log("[ProjectController.add] project[".$this->Project->id."] does not require a repository", 'devtrack');
                } elseif (!$this->Project->Source->create()) {
                    $this->log("[ProjectController.add] project[".$this->Project->id."] repository creation failed - automatically removing project data", 'devtrack');
                    $this->Project->delete();
                    $this->Flash->C(false);
                } else {
                    $this->Flash->C(true);
                }

                $this->redirect(array('project' => $_request_data['Project']['name'], 'action' => 'view', $this->Project->_auth_user_id));
            } else {
                $this->Flash->C(false);
            }
        }

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
            if ($this->Flash->C($this->Project->save($this->request->data))) {
                $this->redirect(array('action' => 'index'));
            }
        }
        $this->set('repoTypes', $this->Project->RepoType->find('list'));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($project = null) {
        $project = $this->_projectCheck($project, true, true);

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Flash->U($this->Project->save($this->request->data))) {
                $this->log("[ProjectController.edit] user[".$this->Project->_auth_user_id."] edited project[".$this->Project->id."]", 'devtrack');
                $this->redirect(array('project' => $project['Project']['name'], 'action' => 'view'));
            }
        }
        $this->request->data = $project;
    }

    /**
     * admin_edit method
     *
     * @param string $id
     * @return void
     */
    public function admin_edit($project = null) {
        $project = $this->_projectCheck($project);

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Flash->U($this->Project->save($this->request->data))) {
                $this->redirect(array('action' => 'admin_view', $this->Project->id));
            }
        }
        $this->request->data = $project;
        $this->set('repoTypes', $this->Project->RepoType->find('list'));
    }

    /**
     * delete
     * remove a project and its sub components from the database
     *
     * @param string $project
     *
     * @return void
     */
    public function delete($project = null) {
        $project = $this->_projectCheck($project, true, true);

        $this->Flash->setUp();

        if ($this->request->is('post')) {
            if ($this->Flash->D($this->Project->delete())) {
                $this->redirect(array('action' => 'index'));
            }
        }
        $this->set('object', array('name'=>$project['Project']['name']));
        $this->set('objects', $this->Project->preDelete());
        $this->render('/Elements/Project/delete');
    }

    /**
     * admin_delete method
     *
     * @param string $id
     * @return void
     */
    public function admin_delete($name = null) {
        $project = $this->_projectCheck($name);

        if (!$this->request->is('post')) throw new MethodNotAllowedException();

        $this->Flash->D($this->Project->delete());
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

                $_part_of_project = $this->Project->hasRead();
                $_public_project  = $this->Project->field('public');
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

    /**
     * api_history function.
     * not strictly an API call, consider moving to ajax routing
     *
     * @access public
     * @param int $number (default: 0)
     * @return void
     */
    public function api_history($number = 0) {
        $project = $this->_projectCheck($this->request->params['named']['project']);
        $this->layout = 'ajax';

        if (!is_numeric($number) || $number < 1 || $number > 50) {
            $number = 8;
        }

        $this->set('events', $this->Project->fetchEventsForProject($number));
        $this->render('/Elements/history');
    }

    /**
     * api_autocomplete function.
     *
     * @access public
     * @return void
     */
    public function api_autocomplete() {
        $this->layout = 'ajax';

        $data = array('users' => array());

        if (isset($this->request->query['query'])
            && $this->request->query['query'] != null
            && strlen($this->request->query['query']) > 1) {

            $query = $this->request->query['query'];
            $projects = $this->Project->find(
                "all",
                array(
                    'conditions' => array(
                        'OR' => array(
                            'Project.name LIKE' => $query.'%',
                            'Project.description LIKE' => $query.'%'
                        ),
                    ),
                    'fields' => array(
                        'Project.name'
                    )
                )
            );
            foreach ($projects as $project) {
                $data['users'][] = $project['Project']['name'];
            }

        }
        $this->set('data',$data);
        $this->render('/Elements/json');
    }
}
