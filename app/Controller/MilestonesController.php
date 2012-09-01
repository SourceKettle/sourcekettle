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
        foreach ($this->Milestone->find('all') as $x => $milestone) {
            $o_tasks = $this->Milestone->openTasksForMilestone($milestone['Milestone']['id']);
            $i_tasks = $this->Milestone->inProgressTasksForMilestone($milestone['Milestone']['id']);
            $r_tasks = $this->Milestone->resolvedTasksForMilestone($milestone['Milestone']['id']);
            $c_tasks = $this->Milestone->closedTasksForMilestone($milestone['Milestone']['id']);

            $milestone['Milestone']['c_tasks'] = sizeof($c_tasks);
            $milestone['Milestone']['i_tasks'] = sizeof($i_tasks);
            $milestone['Milestone']['r_tasks'] = sizeof($r_tasks);
            $milestone['Milestone']['o_tasks'] = sizeof($o_tasks);

            if (empty($o_tasks) && empty($c_tasks)) {
                // Add if new
                $milestones[$x] = $milestone;
            } else if (empty($o_tasks) && empty($r_tasks) && empty($i_tasks) && !empty($c_tasks)) {
                // Closed
            } else {
                // Add in progress
                $milestones[$x] = $milestone;
            }
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
        foreach ($this->Milestone->find('all') as $x => $milestone) {
            $o_tasks = $this->Milestone->openTasksForMilestone($milestone['Milestone']['id']);
            $i_tasks = $this->Milestone->inProgressTasksForMilestone($milestone['Milestone']['id']);
            $r_tasks = $this->Milestone->resolvedTasksForMilestone($milestone['Milestone']['id']);
            $c_tasks = $this->Milestone->closedTasksForMilestone($milestone['Milestone']['id']);

            $milestone['Milestone']['c_tasks'] = sizeof($c_tasks);
            $milestone['Milestone']['i_tasks'] = sizeof($i_tasks);
            $milestone['Milestone']['r_tasks'] = sizeof($r_tasks);
            $milestone['Milestone']['o_tasks'] = sizeof($o_tasks);

            $milestone['Milestone']['closed_tasks'] = sizeof($c_tasks);
            $milestone['Milestone']['open_tasks'] = sizeof($o_tasks);

            if (empty($o_tasks) && empty($r_tasks) && empty($i_tasks) && !empty($c_tasks)) {
                // Closed
                $milestones[$x] = $milestone;
            }
        }
        $this->set('milestones', $milestones);
        $this->render('open_closed');
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
