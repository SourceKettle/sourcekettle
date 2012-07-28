<?php
/**
 *
 * TimesController Controller for the DevTrack system
 * Provides the hard-graft control of the time segments
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

class TimesController extends AppController {

    public $helpers = array('Gravatar', 'Time', 'GoogleChart.GoogleChart');

    /*
     * _projectCheck
     * Space saver to ensure user can view content
     * Also sets commonly needed variables related to the project
     *
     * @param $name string Project name
     */
    private function _projectCheck($name) {
        // Check for existent project
        $project = $this->Time->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));
        $this->Time->Project->id = $project['Project']['id'];

        $user = $this->Auth->user('id');

        // Lock out those who are not guests
        if ( !$this->Time->Project->hasRead($user) ) throw new ForbiddenException(__('You are not a member of this project'));

        $this->set('project', $project);
        $this->set('isAdmin', $this->Time->Project->isAdmin($user));

        return $project;
    }

    /**
     * index method
     *
     * @return void
     */
    public function index($name) {
        $this->redirect(array('project'=>$name,'controller'=>'times','action'=>'users'));
    }

    /**
     * users
     * list the amount of time each user has logged
     *
     * @param name string the project name
     */
    public function users($name) {
        $project = $this->_projectCheck($name);

        // Collect Users
        $users = array();

        $times = $this->Time->findAllByProjectId($project['Project']['id']);
        foreach ($times as $time) {
            $user = $time['User']['id'];

            if (!isset($users[$user])) {
                $users[$user]['User']['name'] = $time['User']['name'];
                $users[$user]['User']['email'] = $time['User']['email'];
                $users[$user]['Time'] = 0;
            }
            $users[$user]['Time'] += (int) $time['Time']['mins'];
        }

        $this->set('users', $users);
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        $this->Time->id = $id;
        if (!$this->Time->exists()) {
            throw new NotFoundException(__('Invalid time'));
        }
        $this->set('time', $this->Time->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Time->create();
            if ($this->Time->save($this->request->data)) {
                $this->Session->setFlash(__('The time has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The time could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Time->Project->find('list');
        $users = $this->Time->User->find('list');
        $this->set(compact('projects', 'users'));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        $this->Time->id = $id;
        if (!$this->Time->exists()) {
            throw new NotFoundException(__('Invalid time'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Time->save($this->request->data)) {
                $this->Session->setFlash(__('The time has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The time could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Time->read(null, $id);
        }
        $projects = $this->Time->Project->find('list');
        $users = $this->Time->User->find('list');
        $this->set(compact('projects', 'users'));
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
        $this->Time->id = $id;
        if (!$this->Time->exists()) {
            throw new NotFoundException(__('Invalid time'));
        }
        if ($this->Time->delete()) {
            $this->Session->setFlash(__('Time deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Time was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}
