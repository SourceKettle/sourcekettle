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
        'Time' => array(
            'className'  => 'Time',
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
     * Determine whether the dependencies of a task are met
     */
    public function afterFind($results, $primary = false){
        parent::afterFind($results, $primary);

        if (isset($results['Task'])){
            return $this->setDependenciesComplete($results);
        } else {
            foreach ($results as $key => $value) {
                $results[$key] = $this->setDependenciesComplete($value); 
            }
        }
        return $results;
    }

    private function setDependenciesComplete($result){
        if (isset($result['DependsOn'])){

            

            if (!empty($result['DependsOn'][0])){
                $completed = true;
                foreach ($result['DependsOn'] as $dependsOn) {
                    if ($dependsOn['task_status_id'] < 3){
                        $completed = false;
                        break;
                    }
                }
                if ($completed){
                    if (isset($result['Task'])){
                        $result['Task']['dependenciesComplete'] = true;
                    } else {
                        $result['dependenciesComplete'] = true;
                    }
                } else {
                    if (isset($result['Task'])){
                        $result['Task']['dependenciesComplete'] = false;
                    } else {
                        $result['dependenciesComplete'] = false;
                    }
                }
            } else {
                if (isset($result['Task'])){
                    $result['Task']['dependenciesComplete'] = false;
                } else {
                    $result['dependenciesComplete'] = false;
                }
            }
        }
        return $result;
    }




    /**
     * __construct function.
     *
     * @access public
     * @param bool $id (default: false)
     * @param mixed $table (default: null)
     * @param mixed $ds (default: null)
     * @return void
     */
    public function __construct($id = false, $table = null, $ds = null){
        parent::__construct($id, $table, $ds);

        $this->virtualFields = array(
            'public_id' => "(SELECT COUNT(`{$this->table}`.`id`) FROM `{$this->table}` WHERE `{$this->table}`.`id` <= `{$this->alias}`.`id` AND `{$this->table}`.`project_id` = `{$this->alias}`.`project_id`)"
        );
    }

    /**
     * beforeSave function
     *
     * @access public
     * @param array $options (default: empty array)
     * @return bool True if the save was successful.
     */
    public function beforeSave(array $options = array()) {
        if(isset ($this->data['DependsOn']['DependsOn']) && is_array($this->data['DependsOn']['DependsOn'])) {
            foreach($this->data['DependsOn']['DependsOn'] as $key => $dependsOn) {
                if ($dependsOn == $this->id) {
                    unset ($this->data['DependsOn'][$key]);
                    break;
                }
            }
        }

        return true;
    }

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

    public function fetchLoggableTasks() {
        $myTasks = $this->find(
            'list',
            array(
                'conditions' => array(
                    'Task.task_status_id <' => 4,
                    'Task.project_id' => $this->Project->id,
                    'Task.assignee_id' => $this->_auth_user_id,
                )
            )
        );
        $othersTasks = $this->find(
            'list',
            array(
                'conditions' => array(
                    'Task.task_status_id <' => 4,
                    'Task.project_id' => $this->Project->id,
                    'Task.assignee_id !=' => $this->_auth_user_id,
                )
            )
        );
        return array(
            'No Assigned Task',
            'Your Tasks' => $myTasks,
            'Others Tasks' => $othersTasks
        );
    }
}
