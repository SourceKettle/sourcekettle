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

        $this->Milestone->id = $id;
        if (!$this->Milestone->exists()) {
            throw new NotFoundException(__('Invalid milestone'));
        }
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

        $this->set('milestone', $this->Milestone->read());

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
        $project = $this->_projectCheck($project, true);

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
