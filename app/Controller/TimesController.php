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

    public $helpers = array('Time', 'GoogleChart.GoogleChart');

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
        $this->redirect(array('project'=>$name,'controller'=>'times','action'=>'history'));
    }

    /**
     * users
     * list the amount of time each user has logged
     *
     * @param name string the project name
     */
    public function users($name) {
        $project = $this->_projectCheck($name);

        $tTime = $this->Time->find('all', array(
            'conditions' => array('Time.project_id' => $project['Project']['id']),
            'fields' => array('SUM(Time.mins)')
        ));
        $users = $this->Time->find('all', array(
            'conditions' => array('Time.project_id' => $project['Project']['id']),
            'group' => array('Time.user_id'),
            'fields' => array('User.id', 'User.name', 'User.email', 'SUM(Time.mins)')
        ));

        foreach ($users as $a => $user) {
            $users[$a]['Time']['time'] = $this->Time->splitMins($user[0]["SUM(`Time`.`mins`)"]);
        }
        $this->set('total_time', $this->Time->splitMins($tTime[0][0]['SUM(`Time`.`mins`)']));
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

        $page = 1;
        $num_per_page = 25;
        $user_id = '*';

        $filterErrors = array();
        $filterError = '';

        $conditions = array(
            'Time.project_id' => $project['Project']['id'],
        );

        if(isset($this->params['named']['page'])) {
            $_page = (int) $this->params['named']['page'];
            if ($_page > 1 and $_page < 100) {
                $page = $_page;
            }
        }

        // Lets check the user filter
        if(isset($this->params['named']['user_id'])) {
            $this->Time->User->id = $this->params['named']['user_id'];
            if ($this->Time->User->exists()) {
                $conditions['Time.user_id'] = $this->Time->User->id;
            } else {
                $filterErrors[] = 'user_id [user does not exist]';
            }
        }

        $tTime = $this->Time->find('all', array(
            'conditions' => array('Time.project_id' => $project['Project']['id']),
            'fields' => array('SUM(Time.mins)')
        ));
        $times = $this->Time->fetchHistory($project['Project']['id'], $num_per_page+1, (($page-1)*$num_per_page), $this->Auth->user('id'), array('conditions'=>$conditions));

        if (sizeof($times) == 0 && $page > 1) {
            $this->redirect(array('project'=>$name,'controller'=>'times','action'=>'history'));
        }

        if (sizeof($times) == $num_per_page+1) {
            unset($times[$num_per_page]);
            $this->set("more_pages", true);
        } else {
            $this->set("more_pages", false);
        }

        foreach ($filterErrors as $err) {
            $filterError .= "<br>$err";
        }
        if ($filterError != '')
            $this->Session->setFlash(__('<strong>The following filters were ignored:</strong>'.$filterError), 'default', array(), 'error');

        $this->set('times', $times);
        $this->set('total_time', $this->Time->splitMins($tTime[0][0]['SUM(`Time`.`mins`)']));
        $this->set('page', $page);
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
     public function view($name, $id = null) {
        $project = $this->_projectCheck($name);

        $this->Time->id = $id;
        if (!$this->Time->exists()) {
            throw new NotFoundException(__('Invalid time'));
        }
        $this->set('time', $this->Time->read(null, $id));
    }

    /**
     * add
     * allows users to log ime
     *
     * @param name string the project name
     */
    public function add($name) {
        $project = $this->_projectCheck($name);

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->Time->create();

            $this->request->data['Time']['user_id'] = $this->Auth->user('id');
            $this->request->data['Time']['project_id'] = $project['Project']['id'];

            if ($this->Time->save($this->request->data)) {
                echo '<div class="alert alert-success"><a class="close" data-dismiss="alert">x</a>Time successfully logged.</div>';
            } else {
                echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">x</a>Could not log time to the project. Please, try again.</div>';
            }
        } else if ($this->request->is('post')) {
            $this->Time->create();
            $origTime = $this->request->data['Time']['mins'];

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
        $this->set('id', $id);

        // Double check that the user is allowed to edit this time slice
        if ($this->Time->field('user_id') != $user && !$this->Time->Project->isAdmin($user)) {
            throw new ForbiddenException(__('You are not the owner of this logged time.'));
        }

        if (!$this->Time->exists()) {
            throw new NotFoundException(__('Invalid time'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $origTime = $this->request->data['Time']['mins'];

            $this->request->data['Time']['user_id'] = $user;
            $this->request->data['Time']['project_id'] = $project['Project']['id'];

            if ($this->Time->save($this->request->data)) {
                $this->Session->setFlash(__('Time successfully updated.'), 'default', array(), 'success');
                $this->redirect(array('project' => $name, 'action' => 'index'));
            } else {
                // Show the user what they put in, its just nice
                $this->request->data['Time']['mins'] = $origTime;
                $this->Session->setFlash(__('Could not update the logged time. Please, try again.'), 'default', array(), 'error');
            }
        } else {
            $this->request->data = $this->Time->read(null, $id);
            $this->request->data['Time']['mins'] = $this->request->data['Time']['mins']['s'];
        }
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($name, $id = null) {
        $project = $this->_projectCheck($name);

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Time->id = $id;
        if (!$this->Time->exists()) {
            throw new NotFoundException(__('Invalid time'));
        }
        $user = $this->Auth->user('id');
        $this->Time->id = $id;

        // Double check that the user is allowed to edit this time slice
        if ($this->Time->field('user_id') != $user && !$this->Time->Project->isAdmin($user)) {
            throw new ForbiddenException(__('You are not the owner of this logged time.'));
        }
        if ($this->Time->delete()) {
            $this->Session->setFlash(__('Time successfully deleted.'), 'default', array(), 'success');
            $this->redirect(array('project' => $name, 'action' => 'index'));
        }
        $this->Session->setFlash(__('Could not delete the logged time. Please, try again.'), 'default', array(), 'error');
        $this->redirect(array('project' => $name, 'action' => 'index'));
    }
}
