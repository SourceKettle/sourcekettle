<?php
/**
 *
 * Task model for the DevTrack system
 * Stores the Tasks for a project in the system
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
 * @property Owner $Owner
 * @property TaskType $TaskType
 * @property TaskStatus $TaskStatus
 * @property TaskPriority $TaskPriority
 * @property Assignee $Assignee
 * @property Milestone $Milestone
 * @property TaskComment $TaskComment
 */
App::uses('AppModel', 'Model');

class Task extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'subject';

    public $actsAs = array(
        'ProjectComponent',
        'ProjectHistory'
    );

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
        'owner_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'task_type_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
            'notempty' => array(
                'rule' => array('notempty'),
            ),
            'inlist' => array(
                'rule' => array('inlist', array(1,2,3,4,5,6,'1','2','3','4','5','6')),
                'message' => 'Select a task type',
            ),
        ),
        'task_status_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
            'notempty' => array(
                'rule' => array('notempty'),
            ),
            'inlist' => array(
                'rule' => array('inlist', array(1,2,3,4,'1','2','3','4')),
                'message' => 'Select a task status',
            ),
        ),
        'task_priority_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
            'notempty' => array(
                'rule' => array('notempty'),
            ),
            'inlist' => array(
                'rule' => array('inlist', array(1,2,3,4,'1','2','3','4')),
                'message' => 'Select a task priority',
            ),
        ),
        'subject' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Subject cannot be empty',
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
        ),
        'Owner' => array(
            'className' => 'User',
            'foreignKey' => 'owner_id',
        ),
        'TaskType' => array(
            'className' => 'TaskType',
            'foreignKey' => 'task_type_id',
        ),
        'TaskStatus' => array(
            'className' => 'TaskStatus',
            'foreignKey' => 'task_status_id',
        ),
        'TaskPriority' => array(
            'className' => 'TaskPriority',
            'foreignKey' => 'task_priority_id',
        ),
        'Assignee' => array(
            'className' => 'User',
            'foreignKey' => 'assignee_id',
        ),
        'Milestone' => array(
            'className' => 'Milestone',
            'foreignKey' => 'milestone_id',
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'TaskComment' => array(
            'className'  => 'TaskComment',
            'foreignKey' => 'task_id',
            'dependent'  => true,
        ),
    );

    public $hasAndBelongsToMany = array(
        'DependsOn' => array(
            'className'  => 'Task',
            'joinTable'  => 'task_dependencies',
            'foreignKey' => 'child_task_id',
            'associationForeignKey' => 'parent_task_id',
        ),
        'DependedOnBy' => array(
            'className'  => 'Task',
            'joinTable'  => 'task_dependencies',
            'foreignKey' => 'parent_task_id',
            'associationForeignKey' => 'child_task_id',
        ),
    );


    /**
     * isAssignee function.
     *
     * @access public
     * @return void
     */
    public function isAssignee() {
        return $this->_auth_user_id == $this->field('assignee_id');
    }

    /**
     * isOpen function.
     *
     * @access public
     * @return void
     */
    public function isOpen() {
        return $this->field('task_status_id') == 1;
    }

    /**
     * isInProgress function.
     *
     * @access public
     * @return void
     */
    public function isInProgress() {
        return $this->field('task_status_id') == 2;
    }

    /**
     * @OVERRIDE
     *
     * fetchHistory function.
     *
     * @access public
     * @param string $project (default: '')
     * @param int $number (default: 10)
     * @param int $offset (default: 0)
     * @param float $user (default: -1)
     * @param array $query (default: array())
     * @return void
     */
    public function fetchHistory($project = '', $number = 10, $offset = 0, $user = -1, $query = array()) {
        $events = $this->Project->ProjectHistory->fetchHistory($project, $number, $offset, $user, 'task');
        return $events;
    }

    /**
     * @OVERRIDE
     *
     * getTitleForHistory function.
     *
     * @access public
     * @param mixed $id
     * @return void
     */
    public function getTitleForHistory($id) {
        $this->id = $id;
        if (!$this->exists()) {
            return null;
        } else {
            return '#'.$id;
        }
    }
}
