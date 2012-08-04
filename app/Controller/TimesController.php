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
                $users[$user]['Time']['mins'] = 0;
            }
            $users[$user]['Time']['mins'] += (int) $time['Time']['mins'];
        }

        foreach ($users as $a => $user) {
            $users[$a]['Time'] = $this->normaliseTime($user['Time']['mins']);
        }

        $this->set('users', $users);
    }

    /**
     * history
     * list the amount of time logged
     *
     * @param name string the project name
     */
    public function history($name) {
        $project = $this->_projectCheck($name);

        $times = $this->Time->findAllByProjectId($project['Project']['id']);

        foreach ($times as $a => $time) {
            $times[$a]['Time']['mins'] = $this->normaliseTime($time['Time']['mins']);
        }

        $this->set('times', $times);
    }

    /**
     * add
     * allows users to log ime
     *
     * @param name string the project name
     */
    public function add($name) {
        $project = $this->_projectCheck($name);

        if ($this->request->is('post')) {
            $this->Time->create();
            $origTime = $this->request->data['Time']['mins'];

            $this->request->data['Time']['mins'] = $this->stringToTime($origTime);
            $this->request->data['Time']['user_id'] = $this->Auth->user('id');
            $this->request->data['Time']['project_id'] = $project['Project']['id'];

            if ($this->Time->save($this->request->data)) {
                $this->Session->setFlash(__('Time successfully logged.'), 'default', array(), 'success');
                $this->redirect(array('project' => $name, 'action' => 'index'));
            } else {
                // Show the user what they put in, its just nice
                $this->request->data['Time']['mins'] = $origTime;
                $this->Session->setFlash(__('Could not log time to the project. Please, try again.'), 'default', array(), 'error');
            }
        }
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($name, $id = null) {
        $project = $this->_projectCheck($name);
        $user = $this->Auth->user('id');
        $this->Time->id = $id;

        // Double check that the user is allowed to edit this time slice
        if ($this->Time->field('user_id') != $user && !$this->Time->Project->isAdmin($user)) {
            throw new ForbiddenException(__('You are not the owner of this logged time.'));
        }

        if (!$this->Time->exists()) {
            throw new NotFoundException(__('Invalid time'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $origTime = $this->request->data['Time']['mins'];

            $this->request->data['Time']['mins'] = $this->stringToTime($origTime);
            $this->request->data['Time']['user_id'] = $user;
            $this->request->data['Time']['project_id'] = $project['Project']['id'];

            if ($this->Time->save($this->request->data)) {
                $this->Session->setFlash(__('Time successfully updated.'), 'default', array(), 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                // Show the user what they put in, its just nice
                $this->request->data['Time']['mins'] = $origTime;
                $this->Session->setFlash(__('Could not update the logged time. Please, try again.'), 'default', array(), 'error');
            }
        } else {
            $this->request->data = $this->Time->read(null, $id);
            $nTime = $this->normaliseTime($this->request->data['Time']['mins']);
            $this->request->data['Time']['mins'] = $nTime['hours']."h ".$nTime['mins']."m";
        }
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

    /**
     * normaliseTime
     * Take a number of mins and turn it into an array of hours and mins
     *
     * @param int $mins
     * @return array array('mins'=>X,'hours'=>X)
     */
    private function normaliseTime($mins = 0) {
        $hours = 0;

        while ($mins >= 60) {
            $hours += 1;
            $mins -= 60;
        }

        return array('hours'=>$hours, 'mins'=>$mins);
    }

    /**
     * stringToTime
     * Take a string with hours and mins in it (e.g. 1h 20m)
     * and turn it into a number of mins
     *
     * @param string $string the string to parse
     * @return int the amount of mins
     */
    private function stringToTime($string = "") {
        if (is_int($string)) {
            return (int) $string;
        }

        preg_match("#(?P<hours>[0-9]+)\s?h(rs?|ours?)?#", $string, $hours);
        preg_match("#(?P<mins>[0-9]+)\s?m(ins?)?#", $string, $mins);

        $time = (int) 0;
        $time += ((isset($hours['hours'])) ? 60*(int)$hours['hours'] : 0);
        $time += ((isset($mins['mins'])) ? (int)$mins['mins'] : 0);

        return $time;
    }
}
