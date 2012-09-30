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
App::uses('AppProjectController', 'Controller');

class TimesController extends AppProjectController {

    public $helpers = array('Time', 'GoogleChart.GoogleChart');

    /**
     * add
     * allows users to log time
     *
     * @access public
     * @param mixed $project
     * @return void
     */
    public function add($project) {
        $project = $this->_projectCheck($project, true);

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

            if ($this->Flash->C($this->Time->save($this->request->data))) {
                $this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
            } else {
                $this->request->data['Time']['mins'] = $origTime; // Show the user what they put in, its just nice
            }
        }
    }

    /**
     * delete function.
     *
     * @access public
     * @param mixed $project
     * @param mixed $id (default: null)
     * @return void
     */
    public function delete($project, $id = null) {
        $project = $this->_projectCheck($project, true);
        $time = $this->Time->open($id, true);

        if (!$this->request->is('post')) throw new MethodNotAllowedException();

        $this->Flash->setUp();
        $this->Flash->D($this->Time->delete());
        $this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
    }

    /**
     * edit function.
     *
     * @access public
     * @param mixed $project
     * @param mixed $id (default: null)
     * @return void
     */
    public function edit($project, $id = null) {
        $project = $this->_projectCheck($project, true);
        $time = $this->Time->open($id, true);

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Time']['user_id'] = $this->Time->_auth_user_id;
            $this->request->data['Time']['project_id'] = $project['Project']['id'];

            if ($this->Flash->U($this->Time->save($this->request->data))) {
                $this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
            }
        } else {
            $this->request->data = $time;
            $this->request->data['Time']['mins'] = $this->request->data['Time']['mins']['s'];
        }
    }

    /**
     * history
     * list the amount of time logged
     *
     * @access public
     * @param mixed $project (default: null)
     * @param mixed $week (default: null)
     * @return void
     */
    public function history($project = null, $week = null) {
        $project = $this->_projectCheck($project);

        if ($week) {
            if (!is_numeric($week) || $week < 1 || $week > 53) {
                $this->redirect(array('project'=>$project['Project']['name'], 'action'=>'history'));
            }
        } else {
            $week = date('W');
        }
        if ($week < 10) {
            $week = '0'.$week;
        }
        $week_start = date('Y-m-d', strtotime(date('Y').'W'.$week));
        $week_end   = date('Y-m-d', strtotime("$week_start +1 week"));

        $day = date('N');
        $week_items = array();

        $week_tasks = $this->Time->find('list', array(
            'fields'        => array('Time.task_id'),
            'group'         => array('Time.task_id'),
            'conditions'    => array(
                'Time.date BETWEEN ? AND ?' => array($week_start,$week_end),
                'Time.project_id'           => $project['Project']['id'],
                'Time.user_id'              => $this->Time->_auth_user_id
            )
        ));

        // Iterate over our week
        for($x = 1; $x <= 7; $x++) {
            // Real date for the day
            $date = date('Y-m-d', strtotime("$week_start +".($x-1)." days"));

            // Todal time for a day
            $_total = 0;

            // Iterate over the tasks that we found for this week
            foreach ($week_tasks as $m => $task) {
                // Total for the task for the day
                $_subTotal = 0;
                $_task_id = ($task) ? $task : 0;

                $week_items[$date][$_task_id] = $this->Time->find(
                    'all', array(
                    'conditions' => array(
                        'date'      => $date,
                        'user_id'   => $this->Time->_auth_user_id,
                        'project_id'=> $project['Project']['id'],
                        'task_id'   => $task
                    )
                ));

                $task = ($task) ? $task : 0;

                // If there are items, calculate the total time
                foreach ($week_items[$date][$_task_id] as $_time) {
                    $_subTotal += $_time['Time']['mins']['t'];
                }
                $_total += $_subTotal;

                // Change the total to a useful format
                $_subTotal = $this->Time->splitMins($_subTotal);
                $week_items[$date][$_task_id]['total'] = $_subTotal['h'] + round($_subTotal['m']/60, 1);
            }

            if (!isset($week_items[$date][0])) {
                $week_items[$date][0]['total'] = 0;
            }

            // Change the total to a useful format
            if ($_total) {
                $_total = $this->Time->splitMins($_total);
                $week_items[$date]['total'] = $_total['h'] + round($_total['m']/60, 1);
            } else {
                $week_items[$date]['total'] = '';
            }
        }

        $week_tasks = $this->Time->Project->Task->find('all', array(
            'conditions'    => array(
                'Task.id' => array_values($week_tasks),
            )
        ));
        $week_tasks[] = array('Task' => array('id' => 0, 'subject' => 'No associated task'));

        $this->set('week', $week_items);
        $this->set('tasks', $week_tasks);
        $this->set('dayOfWeek', $day);
        $this->set('weekStart', $week_start);
        $this->set('weekNo', $week);
    }

    /**
     * index function.
     *
     * @access public
     * @param mixed $project
     * @return void
     */
    public function index($project) {
        $this->redirect(array('project'=>$project,'controller'=>'times','action'=>'users'));
    }

    /**
     * users
     * list the amount of time each user has logged
     *
     * @access public
     * @param mixed $project
     * @return void
     */
    public function users($project) {
        $project = $this->_projectCheck($project);

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
     * view function.
     *
     * @access public
     * @param mixed $project
     * @param mixed $id (default: null)
     * @return void
     */
    public function view($project, $id = null) {
        $project = $this->_projectCheck($project);
        $this->set('time', $this->Time->open($id));
    }
}
