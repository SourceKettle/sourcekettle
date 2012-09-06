<?php
/**
 *
 * Milestone model for the DevTrack system
 * Stores the Milestones for Projects in the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.Model
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * @property Project $Project
 * @property Task $Task
 */

App::uses('AppModel', 'Model');

class Milestone extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'subject';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'project_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'subject' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 50),
                'message' => 'Short names must be less than 50 characters long',
            ),
        ),
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Project' => array(
            'className' => 'Project',
            'foreignKey' => 'project_id',
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Task' => array(
            'className' => 'Task',
            'foreignKey' => 'milestone_id',
            'dependent' => false,
        )
    );

    /**
     * openTasksForMilestone function.
     *
     * @access public
     * @param mixed $id (default: null)
     * @return void
     */
    public function openTasksForMilestone($id = null) {
        return $this->tasksOfStatusForMilestone($id, 1);
    }

    /**
     * inProgressTasksForMilestone function.
     *
     * @access public
     * @param mixed $id (default: null)
     * @return void
     */
    public function inProgressTasksForMilestone($id = null) {
        return $this->tasksOfStatusForMilestone($id, 2);
    }

    /**
     * resolvedTasksForMilestone function.
     *
     * @access public
     * @param mixed $id (default: null)
     * @return void
     */
    public function resolvedTasksForMilestone($id = null) {
        return $this->tasksOfStatusForMilestone($id, 3);
    }

    /**
     * closedTasksForMilestone function.
     *
     * @access public
     * @param mixed $id (default: null)
     * @return void
     */
    public function closedTasksForMilestone($id = null) {
        return $this->tasksOfStatusForMilestone($id, 4);
    }

    /**
     * tasksOfStatusForMilestone function.
     *
     * @access public
     * @param mixed $id (default: null)
     * @param mixed $status
     * @return void
     */
    public function tasksOfStatusForMilestone($id = null, $status) {
        $this->id = $id;

        if (!$this->exists()) return null;

        $tasks = $this->Task->find(
            'all',
            array(
                'field' => array('milestone_id'),
                'conditions' => array(
                    'task_status_id ' => $status,
                    'milestone_id =' => $id
                ),
                'order' => 'task_priority_id DESC'
            )
        );
        return $tasks;
    }

    public function getOpenMilestones() {
        $list_of_milestones = array_values($this->find('list', array('fields' => array('id'))));
        return array_diff($list_of_milestones, $this->getClosedMilestones());
    }

    public function getClosedMilestones() {
        $list_of_milestones = array_values($this->find('list', array('fields' => array('id'))));
        $open_task_milestones = array_values($this->Task->find(
            'list',
            array(
                'group' => array('milestone_id'),
                'fields' => array('milestone_id'),
                 'conditions' => array(
                    'milestone_id NOT' => NULL,
                    'task_status_id <' => 4)
            )
        ));
        return array_diff($list_of_milestones, $open_task_milestones);
    }
}
