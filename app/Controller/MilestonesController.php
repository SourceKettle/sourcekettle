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

App::uses('AppProjectController', 'Controller');

class MilestonesController extends AppProjectController {

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
    public function index($project = null) {
        $this->redirect(array('project'=>$project,'action'=>'open'));
    }

    /**
     * index method
     *
     * @return void
     */
    public function open($project = null) {
        $project = $this->_projectCheck($project);

        $milestones = array();
        // Iterate over all milestones
        foreach ($this->Milestone->getOpenMilestones() as $x) {
            $o_tasks = $this->Milestone->openTasksForMilestone($x);
            $i_tasks = $this->Milestone->inProgressTasksForMilestone($x);
            $r_tasks = $this->Milestone->resolvedTasksForMilestone($x);
            $c_tasks = $this->Milestone->closedTasksForMilestone($x);

            $this->Milestone->id = $x;
            $milestone = $this->Milestone->read();

            $milestone['Milestone']['c_tasks'] = sizeof($c_tasks);
            $milestone['Milestone']['i_tasks'] = sizeof($i_tasks);
            $milestone['Milestone']['r_tasks'] = sizeof($r_tasks);
            $milestone['Milestone']['o_tasks'] = sizeof($o_tasks);


            $milestones[$x] = $milestone;
        }
        $this->set('milestones', $milestones);
        $this->render('open_closed');
    }

    /**
     * index method
     *
     * @return void
     */
    public function closed($project = null) {
        $project = $this->_projectCheck($project);

        $milestones = array();
        // Iterate over all milestones
        foreach ($this->Milestone->getClosedMilestones() as $x) {
            $o_tasks = $this->Milestone->openTasksForMilestone($x);
            $i_tasks = $this->Milestone->inProgressTasksForMilestone($x);
            $r_tasks = $this->Milestone->resolvedTasksForMilestone($x);
            $c_tasks = $this->Milestone->closedTasksForMilestone($x);

            $this->Milestone->id = $x;
            $milestone = $this->Milestone->read();

            $milestone['Milestone']['c_tasks'] = sizeof($c_tasks);
            $milestone['Milestone']['i_tasks'] = sizeof($i_tasks);
            $milestone['Milestone']['r_tasks'] = sizeof($r_tasks);
            $milestone['Milestone']['o_tasks'] = sizeof($o_tasks);

            $milestone['Milestone']['closed_tasks'] = sizeof($c_tasks);
            $milestone['Milestone']['open_tasks'] = sizeof($o_tasks);

            $milestones[$x] = $milestone;
        }
        $this->set('milestones', $milestones);
        $this->render('open_closed');
    }

    /**
     * view method
     *
     * @return void
     */
    public function view($project = null, $id = null) {
        $project = $this->_projectCheck($project);
        $milestone = $this->Milestone->open($id);

        $backlog = $this->Milestone->openTasksForMilestone($id);
        $inProgress = $this->Milestone->inProgressTasksForMilestone($id);
        $resolved = $this->Milestone->resolvedTasksForMilestone($id);
        $completed = $this->Milestone->closedTasksForMilestone($id);

        $iceBox = $this->Milestone->Task->find('all', array('conditions' => array('milestone_id' => NULL)));

        // Theres only 3 cols
        $completed = array_merge($completed, $resolved);

        // Sort function for tasks
        $cmp = function($a, $b) {
            if (strtotime($a['Task']['task_priority_id']) == strtotime($b['Task']['task_priority_id'])) return 0;
            if (strtotime($a['Task']['task_priority_id']) > strtotime($b['Task']['task_priority_id'])) return 1;
            return -1;
        };

        usort($completed, $cmp);

        // Final value is min size of the board
        $max = max(sizeof($backlog), sizeof($inProgress), sizeof($completed), 3);

        $this->set('milestone', $milestone);

        $this->set('backlog_empty', $max - sizeof($backlog));
        $this->set('inProgress_empty', $max - sizeof($inProgress));
        $this->set('completed_empty', $max - sizeof($completed));
        $this->set(compact('backlog', 'inProgress', 'completed', 'iceBox'));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add($project = null) {
        $project = $this->_projectCheck($project, true);

        if ($this->request->is('post')) {
            $this->Milestone->create();

            $this->request->data['Milestone']['project_id'] = $project['Project']['id'];

            if ($this->Milestone->save($this->request->data)) {
                $this->Session->setFlash(__('The milestone has been saved.'), 'default', array(), 'success');
                $this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
            } else {
                $this->Session->setFlash(__('The milestone could not be saved. Please, try again.'), 'default', array(), 'error');
            }
        }
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($project = null, $id = null) {
        $project = $this->_projectCheck($project, true);
        $milestone = $this->Milestone->open($id);

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Milestone']['project_id'] = $project['Project']['id'];

            if ($this->Milestone->save($this->request->data)) {
                $this->Session->setFlash(__('The milestone has been saved.'), 'default', array(), 'success');
                $this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
            } else {
                $this->Session->setFlash(__('The milestone could not be saved. Please, try again.'), 'default', array(), 'error');
            }
        } else {
            $this->request->data = $milestone;
        }
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($project = null, $id = null) {
        $project = $this->_projectCheck($project);

        if (!$this->request->is('post') && !$this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        $this->Milestone->id = $id;
        if (!$this->Milestone->exists()) {
            throw new NotFoundException(__('Invalid milestone'));
        }
        if ($this->Milestone->delete()) {
            $this->Session->setFlash(__('The milestone has been deleted.'), 'default', array(), 'success');
            $this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
        }
        $this->Session->setFlash(__('The milestone could not be deleted. Please, try again.'), 'default', array(), 'error');
        $this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
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

        $this->Milestone->recursive = -1;
        $this->Milestone->Task->recursive = -1;

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
            $this->Milestone->id = $id;

            if (!$this->Milestone->exists()) {
                $this->response->statusCode(404);
                $data['error'] = 404;
                $data['message'] = 'No milestone found of that ID.';
                $data['id'] = $id;
            } else {
                $milestone = $this->Milestone->read();

                $this->Milestone->Project->id = $milestone['Milestone']['project_id'];

                $_part_of_project = $this->Milestone->Project->hasRead($this->Auth->user('id'));
                $_public_project  = $this->Milestone->Project->field('public');
                $_is_admin = ($this->_api_auth_level() == 1);

                if ($_public_project || $_is_admin || $_part_of_project) {
                    $milestone['Milestone']['tasks'] = array_values($this->Milestone->Task->find('list', array('conditions' => array('milestone_id' => $id))));

                    $data = $milestone['Milestone'];
                } else {
                    $data['error'] = 401;
                    $data['message'] = 'Milestone found, but is not public.';
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

        $this->Milestone->recursive = -1;
        $data = array();

        switch ($this->_api_auth_level()) {
            case 1:
                foreach ($this->Milestone->find("all") as $milestone) {
                    $milestone['Milestone']['tasks'] = array_values($this->Milestone->Task->find('list', array('conditions' => array('milestone_id' => $milestone['Milestone']['id']))));

                    $data[] = $milestone['Milestone'];
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
