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
                )
            )
        );
        return $tasks;
    }
}
