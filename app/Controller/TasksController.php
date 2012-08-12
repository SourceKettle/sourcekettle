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

App::uses('AppController', 'Controller');

class TasksController extends AppController {

    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = array('Time');

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->Task->recursive = 0;
        $this->set('tasks', $this->paginate());
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        $this->Task->id = $id;
        if (!$this->Task->exists()) {
            throw new NotFoundException(__('Invalid task'));
        }
        $this->set('task', $this->Task->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Task->create();
            if ($this->Task->save($this->request->data)) {
                $this->Session->setFlash(__('The task has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The task could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Task->Project->find('list');
        $owners = $this->Task->Owner->find('list');
        $taskTypes = $this->Task->TaskType->find('list');
        $taskStatuses = $this->Task->TaskStatus->find('list');
        $assignees = $this->Task->Assignee->find('list');
        $milestones = $this->Task->Milestone->find('list');
        $this->set(compact('projects', 'owners', 'taskTypes', 'taskStatuses', 'assignees', 'milestones'));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        $this->Task->id = $id;
        if (!$this->Task->exists()) {
            throw new NotFoundException(__('Invalid task'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Task->save($this->request->data)) {
                $this->Session->setFlash(__('The task has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The task could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Task->read(null, $id);
        }
        $projects = $this->Task->Project->find('list');
        $owners = $this->Task->Owner->find('list');
        $taskTypes = $this->Task->TaskType->find('list');
        $taskStatuses = $this->Task->TaskStatus->find('list');
        $assignees = $this->Task->Assignee->find('list');
        $milestones = $this->Task->Milestone->find('list');
        $this->set(compact('projects', 'owners', 'taskTypes', 'taskStatuses', 'assignees', 'milestones'));
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
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
}
