<?php
/**
 *
 * TasksController Controller for the DevTrack system
 * Provides the hard-graft control of the tasks for users
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

class TasksController extends AppProjectController {

    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = array('Time', 'Task');

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
        $project = $this->_projectCheck($project);

        $user = $this->Task->find('all', array(
            'conditions' => array(
                'Project.id' => $project['Project']['id'],
                'Assignee.id' => $this->Auth->user('id'),
                'TaskStatus.id' => array(1,2)
            ),
            'order' => 'TaskPriority.id DESC'
        ));
        $team = $this->Task->find('all', array(
            'conditions' => array(
                'Project.id' => $project['Project']['id'],
                'Assignee.id !=' => $this->Auth->user('id'),
                'TaskStatus.id' => array(1,2)
            ),
            'order' => 'TaskPriority.id DESC'
        ));
        $others = $this->Task->find('all', array(
            'conditions' => array(
                'Project.id' => $project['Project']['id'],
                'Assignee.id' => null,
                'TaskStatus.id' => array(1,2)
            ),
            'order' => 'TaskPriority.id DESC'
        ));

        // Final value is min size of the board
        $max = max(sizeof($user), sizeof($team), sizeof($others), 5);

        $events = $this->Task->fetchHistory($project['Project']['id'], round($max * 1.4));

        $this->set('user_empty', $max - sizeof($user));
        $this->set('team_empty', $max - sizeof($team));
        $this->set('others_empty', $max - sizeof($others));
        $this->set(compact('user', 'team', 'others', 'events'));
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view($project = null, $id = null) {
        $project = $this->_projectCheck($project);

        $this->Task->id = $id;
        if (!$this->Task->exists()) {
            throw new NotFoundException(__('Invalid task'));
        }

        // If a User has commented
        if ($this->request->is('post') && isset($this->request->data['TaskComment'])) {
            $user = $this->Auth->user('id');

            $this->Task->TaskComment->create();

            $this->request->data['TaskComment']['task_id'] = $id;
            $this->request->data['TaskComment']['user_id'] = $user;

            if ($this->Task->TaskComment->save($this->request->data)) {
                $this->Session->setFlash(__('The comment has been added successfully'), 'default', array(), 'success');
                unset($this->request->data['TaskComment']);
            } else {
                $this->Session->setFlash(__('The comment could not be saved. Please, try again.'), 'default', array(), 'error');
            }
        }

        // If a User has updated a comment
        if ($this->request->is('post') && isset($this->request->data['TaskCommentEdit'])) {

            $this->request->data['TaskComment'] = $this->request->data['TaskCommentEdit'];
            unset($this->request->data['TaskCommentEdit']);

            $this->Task->TaskComment->id = $this->request->data['TaskComment']['id'];

            if ($this->Task->TaskComment->exists() && $this->Task->TaskComment->save($this->request->data)) {
                $this->Session->setFlash(__('The comment has been updated successfully'), 'default', array(), 'success');
                unset($this->request->data['TaskComment']);
            } else {
                $this->Session->setFlash(__('The comment could not be updated. Please, try again.'), 'default', array(), 'error');
            }
        }

        // If a User has assigned someone
        if ($this->request->is('post') && isset($this->request->data['TaskAssignee'])) {

            preg_match('#[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}#', $this->request->data['TaskAssignee']['assignee'], $matches);
            unset($this->request->data['TaskAssignee']);

            if (!isset($matches[0]) || is_null($matches[0]) || !($user = $this->Task->Assignee->findByEmail($matches[0]))) {
                $this->Session->setFlash(__('The assignee could not be updated. Please, try again.'), 'default', array(), 'error');
            } else {
                $this->request->data['Task']['assignee_id'] = $user['Assignee']['id'];
                $this->Task->id = $id;

                if ($this->Task->save($this->request->data)) {
                    $this->Session->setFlash(__('The assignee has been updated successfully'), 'default', array(), 'success');
                } else {
                    $this->Session->setFlash(__('The assignee could not be updated. Please, try again.'), 'default', array(), 'error');
                }
            }
        }

        $this->set('task', $this->Task->read(null, $id));

        // Fetch the changes that will have happened
        $changes  = $this->Task->Project->ProjectHistory->find('all', array('conditions' => array('ProjectHistory.row_id' => $this->Task->id, 'ProjectHistory.row_field !=' => '+')));
        $comments = $this->Task->TaskComment->find('all', array('conditions' => array('Task.id' => $this->Task->id)));

        // They are in the wrong format for the sort function - so move the modified field
        foreach ( $changes as $x => $change ) {
            $changes[$x]['created'] = $change['ProjectHistory']['created'];
        }
        foreach ( $comments as $x => $comment ) {
            $comments[$x]['created'] = $comment['TaskComment']['created'];
        }

        // Fetch any additional users that may be needed
        $change_users = array();
        $this->Task->Assignee->recursive = -1;
        foreach ( $changes as $change ) {
            if ($change['ProjectHistory']['row_field'] == 'assignee_id') {
                $_old = $change['ProjectHistory']['row_field_old'];
                $_new = $change['ProjectHistory']['row_field_new'];

                if ($_old && !isset($change_users[$_old])) {
                    $this->Task->Assignee->id = $_old;
                    $_temp = $this->Task->Assignee->read();
                    $change_users[$_old] = array($_temp['Assignee']['name'], $_temp['Assignee']['email']);
                }
                if ($_new && !isset($change_users[$_new])) {
                    $this->Task->Assignee->id = $_new;
                    $_temp = $this->Task->Assignee->read();
                    $change_users[$_new] = array($_temp['Assignee']['name'], $_temp['Assignee']['email']);
                }
            }
        }

        // Merge the changes
        $changes = array_merge($changes, $comments);

        // Sort function for events
        // assumes $array{ $array{ 'modified' => 'date' }, ... }
        $cmp = function($a, $b) {
            if (strtotime($a['created']) == strtotime($b['created'])) return 0;
            if (strtotime($a['created']) > strtotime($b['created'])) return 1;
            return -1;
        };

        usort($changes, $cmp);
        $this->set('change_users', $change_users);
        $this->set('changes', $changes);
    }

    /**
     * add method
     *
     * @return void
     */
    public function add($project = null) {
        $project = $this->_projectCheck($project, true);

        if ($this->request->is('ajax')) {
            // Enable once we've figured out how to do two Ajax submit buttons
            // if ($this->request->data['submit'] == 1) {
                 $this->redirect(array('project' => $project['Project']['name'], 'action' => 'add'));
            // }

            $this->autoRender = false;
            $this->Task->create();

            $this->request->data['Task']['project_id'] = $project['Project']['id'];
            $this->request->data['Task']['owner_id'] = $this->Auth->user('id');
            $this->request->data['Task']['assignee_id'] = null;
            $this->request->data['Task']['milestone_id'] = null;
            $this->request->data['Task']['task_status_id'] = 1;
            $this->request->data['Task']['task_type_id'] = 3;
            $this->request->data['Task']['task_priority_id'] = 2;

            if ($this->Task->save($this->request->data)) {
                echo '<div class="alert alert-success"><a class="close" data-dismiss="alert">x</a>Time successfully logged.</div>';
            } else {
                echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">x</a>Could not log time to the project. Please, try again.</div>';
            }
        } else if ($this->request->is('post')) {
            $this->Task->create();

            $this->request->data['Task']['project_id'] = $project['Project']['id'];
            $this->request->data['Task']['owner_id'] = $this->Auth->user('id');
            $this->request->data['Task']['assignee_id'] = null;
            $this->request->data['Task']['milestone_id'] = ($this->request->data['Task']['milestone_id'] == 0) ? null : $this->request->data['Task']['milestone_id'];
            $this->request->data['Task']['task_type_id'] = (!isset($this->request->data['Task']['task_type_id'])) ? null : $this->request->data['Task']['task_type_id'];
            $this->request->data['Task']['task_status_id'] = 1;

            if ($this->Task->save($this->request->data)) {
                $this->Session->setFlash(__('The task has been added successfully'), 'default', array(), 'success');
                $this->redirect(array('project' => $project['Project']['name'], 'action' => 'view', $this->Task->id));
            } else {
                $this->Session->setFlash(__('The task could not be saved. Please, try again.'), 'default', array(), 'error');
            }
        }

        // Fetch all the variables for the view
        $taskPriorities = $this->Task->TaskPriority->find('list', array('order' => 'id DESC'));
        $milestonesOpen = $this->Task->Milestone->find('list');
        $milestonesClosed = $this->Task->Milestone->find('list');
        $milestones = array('No Assigned Milestone');
        if (!empty($milestonesOpen)) {
            $milestones['Open'] = $milestonesOpen;
        }
        if (!empty($milestonesClosed)) {
            $milestones['Closed'] = $milestonesClosed;
        }
        foreach ( $taskPriorities as $id => $p ) {
            $taskPriorities[$id] = ucfirst(strtolower($p));
        }
        $this->set(compact('taskPriorities', 'milestones'));

    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($project = null, $id = null) {
        $project = $this->_projectCheck($project, true);

        $this->Task->id = $id;
        if (!$this->Task->exists()) {
            throw new NotFoundException(__('Invalid task'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            unset($this->request->data['Task']['project_id']);
            unset($this->request->data['Task']['owner_id']);

            if ($this->Task->save($this->request->data)) {
                $this->Session->setFlash(__('The task has been saved'), 'default', array(), 'success');
                $this->redirect(array('project' => $project['Project']['name'], 'action' => 'view', $this->Task->id));
            } else {
                $this->Session->setFlash(__('The task could not be saved. Please, try again.'), 'default', array(), 'error');
            }
        } else {
            $this->request->data = $this->Task->read(null, $id);

            // Fetch all the variables for the view
            $taskPriorities = $this->Task->TaskPriority->find('list', array('order' => 'id DESC'));
            $milestonesOpen = $this->Task->Milestone->find('list');
            $milestonesClosed = $this->Task->Milestone->find('list');
            $milestones = array('No Assigned Milestone');
            if (!empty($milestonesOpen)) {
                $milestones['Open'] = $milestonesOpen;
            }
            if (!empty($milestonesClosed)) {
                $milestones['Closed'] = $milestonesClosed;
            }
            foreach ( $taskPriorities as $id => $p ) {
                $taskPriorities[$id] = ucfirst(strtolower($p));
            }
            $this->set(compact('taskPriorities', 'milestones'));
        }
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($project = null, $id = null) {
        $project = $this->_projectCheck($project, true);

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Task->id = $id;
        if (!$this->Task->exists()) {
            throw new NotFoundException(__('Invalid task'));
        }
        if ($this->Task->delete()) {
            $this->Session->setFlash(__('Task deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Task was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

    /**
     * starttask function.
     *
     * @access public
     * @param mixed $project (default: null)
     * @param mixed $id (default: null)
     * @return void
     */
    public function starttask($project = null, $id = null) {
        $this->Task->id = $id;
        if (!$this->Task->exists()) {
            throw new NotFoundException(__('Invalid task'));
        }

        // check assigned
        if (!$this->Task->isAssignee()) {
            $this->Session->setFlash(__('You can not start work on a task not assigned to you.'), 'default', array(), 'error');
            $this->redirect(array('project' => $project, 'action' => 'view', $id));
        }

        //check open
        if (!$this->Task->isOpen()) {
            $this->Session->setFlash(__('You can not start work on a task that is not open.'), 'default', array(), 'error');
            $this->redirect(array('project' => $project, 'action' => 'view', $id));
        }

        $this->_update_task_status($project, $id, 2);
        $this->redirect(array('project' => $project, 'action' => 'view', $id));
    }

    /**
     * stoptask function.
     *
     * @access public
     * @param mixed $project (default: null)
     * @param mixed $id (default: null)
     * @return void
     */
    public function stoptask($project = null, $id = null) {
        $this->Task->id = $id;
        if (!$this->Task->exists()) {
            throw new NotFoundException(__('Invalid task'));
        }

        // check assigned
        if (!$this->Task->isAssignee()) {
            $this->Session->setFlash(__('You can not stop work on a task not assigned to you.'), 'default', array(), 'error');
            $this->redirect(array('project' => $project, 'action' => 'view', $id));
        }

        //check inProgress
        if (!$this->Task->isInProgress()) {
            $this->Session->setFlash(__('You can not stop work on a task that is not in progress.'), 'default', array(), 'error');
            $this->redirect(array('project' => $project, 'action' => 'view', $id));
        }

        $this->_update_task_status($project, $id, 1);
        $this->redirect(array('project' => $project, 'action' => 'view', $id));
    }

    /**
     * opentask function.
     *
     * @access public
     * @param mixed $project (default: null)
     * @param mixed $id (default: null)
     * @return void
     */
    public function opentask($project = null, $id = null) {
        $success = $this->_update_task_status($project, $id, 1);
        $this->redirect(array('project' => $project, 'action' => 'view', $id));
    }

    /**
     * closetask function.
     *
     * @access public
     * @param mixed $project (default: null)
     * @param mixed $id (default: null)
     * @return void
     */
    public function closetask($project = null, $id = null) {
        $success = $this->_update_task_status($project, $id, 4);

        // If a User has commented
        if (isset($this->request->data['TaskComment']['comment']) && $this->request->data['TaskComment']['comment'] != '') {
            $this->Task->TaskComment->create();

            $this->request->data['TaskComment']['task_id'] = $id;
            $this->request->data['TaskComment']['user_id'] = $this->Auth->user('id');

            if ($this->Task->TaskComment->save($this->request->data)) {
                $this->Session->setFlash(__('The comment has been added successfully'), 'default', array(), 'success');
                unset($this->request->data['TaskComment']);
            } else {
                $this->Session->setFlash(__('The comment could not be saved. Please, try again.'), 'default', array(), 'error');
            }
        }
        $this->redirect(array('project' => $project, 'action' => 'view', $id));
    }

    /**
     * _update_task_status function.
     *
     * @access public
     * @param mixed $project (default: null)
     * @param mixed $id (default: null)
     * @return void
     */
    private function _update_task_status($project = null, $id = null, $status = null) {
        $project = $this->_projectCheck($project, true);

        $this->Task->id = $id;

        //if (!$this->request->is('post')) throw new MethodNotAllowedException();
        if (!$this->Task->exists()) throw new NotFoundException(__('Invalid task'));

        $this->Task->set('task_status_id', $status);

        if ($this->Task->save()) {
            $this->Session->setFlash(__('The tasks status has been updated successfully'), 'default', array(), 'success');
            return true;
        }
        $this->Session->setFlash(__('The task status could not be updated. Please, try again.'), 'default', array(), 'error');
        return false;
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

        $this->Task->recursive = 0;

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
            $this->Task->id = $id;

            if (!$this->Task->exists()) {
                $this->response->statusCode(404);
                $data['error'] = 404;
                $data['message'] = 'No task found of that ID.';
                $data['id'] = $id;
            } else {
                $task = $this->Task->read();

                $this->Task->Project->id = $task['Task']['project_id'];

                $_part_of_project = $this->Task->Project->hasRead($this->Auth->user('id'));
                $_public_project  = $this->Task->Project->field('public');
                $_is_admin = ($this->_api_auth_level() == 1);

                if ($_public_project || $_is_admin || $_part_of_project) {
                    //task_type_id
                    unset($task['Task']['task_type_id']);
                    $task['Task']['type'] = $task['TaskType']['name'];
                    //task_status_id
                    unset($task['Task']['task_status_id']);
                    $task['Task']['status'] = $task['TaskStatus']['name'];
                    //task_priority_id
                    unset($task['Task']['task_priority_id']);
                    $task['Task']['priority'] = $task['TaskPriority']['name'];

                    $data = $task['Task'];
                } else {
                    $data['error'] = 401;
                    $data['message'] = 'Task found, but is not public.';
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

        $this->Task->recursive = 0;
        $data = array();

        switch ($this->_api_auth_level()) {
            case 1:
                foreach ($this->Task->find("all") as $task) {
                    //task_type_id
                    unset($task['Task']['task_type_id']);
                    $task['Task']['type'] = $task['TaskType']['name'];
                    //task_status_id
                    unset($task['Task']['task_status_id']);
                    $task['Task']['status'] = $task['TaskStatus']['name'];
                    //task_priority_id
                    unset($task['Task']['task_priority_id']);
                    $task['Task']['priority'] = $task['TaskPriority']['name'];

                    $data[] = $task['Task'];
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
