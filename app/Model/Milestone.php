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

    public $actsAs = array(
        'ProjectComponent',
        'ProjectHistory',
        'ProjectDeletable'
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
     * afterFind function.
     *
     * @access public
     * @param mixed $results
     * @param bool $primary (default: false)
     * @return void
     */
    public function afterFind($results, $primary = false) {
        foreach ($results as $a => $result) {
            if (isset($result['Milestone']) && isset($result['Milestone']['id'])) {
                $this->Task->recursive = -1;
                $o = $results[$a]['Tasks']['open'] = $this->openTasksForMilestone($result['Milestone']['id']);
                $i = $results[$a]['Tasks']['in_progress'] = $this->inProgressTasksForMilestone($result['Milestone']['id']);
                $r = $results[$a]['Tasks']['resolved'] = $this->resolvedTasksForMilestone($result['Milestone']['id']);
                $c = $results[$a]['Tasks']['completed'] = $this->closedTasksForMilestone($result['Milestone']['id']);

                if ((sizeof($o) + sizeof($i) + sizeof($r) + sizeof($c)) > 0) {
                    $results[$a]['Milestone']['percent'] = sizeof($c) / (sizeof($o) + sizeof($i) + sizeof($r) + sizeof($c)) * 100;
                } else {
                    $results[$a]['Milestone']['percent'] = 0;
                }
                $this->Task->recursive = 1;
            }
        }
        return $results;
    }

    /**
     * beforeDelete function.
     * Dis-associate all of the incomplete tasks and delete the done ones
     *
     * @access public
     * @return void
     */
    public function beforeDelete($cascade = false) {
        foreach ($this->Task->find('all', array('conditions' => array('milestone_id' => $this->id, 'task_status_id <' => 3))) as $task) {
            $this->Task->id = $task['Task']['id'];
            $this->Task->set('milestone_id', null);
            $this->Task->save();
        }
        $this->Task->deleteAll(array('milestone_id' => $this->id), false);

        if ($this->Task->findByMilestoneId($this->id)) {
            return false;
        }
        return true;
    }

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

    public function closedOrResolvedTasksForMilestone($id = null){
        $this->id = $id;

        if (!$this->exists()) return null;

        $tasks = $this->Task->find(
            'all',
            array(
                'field' => array('milestone_id'),
                'conditions' => array(
                    'OR' => array(
                        'task_status_id ' => 3,
                        'task_status_id ' => 4,
                    ),
                    'AND' => array(
                        'milestone_id =' => $id
                    )
                ),
                'order' => 'task_priority_id DESC'
            )
        );
        return $tasks;

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

    public function getOpenMilestones($assoc = false) {
        // Fetch a list of milestones for the project
        $_milestones = $this->find(
            'list',
            array(
                'fields' => array('id'),
                'conditions'=> array('project_id' => $this->Project->id)
            )
        );
        // If we require an associated result (with names)
        if ($assoc) {
            $open = $this->find(
                'list',
                array(
                    'fields' => array('id', 'subject'),
                    'conditions' => array(
                        'project_id' => $this->Project->id,
                        'id' => array_diff(array_values($_milestones), array_keys($this->getClosedMilestones($assoc)))
                    )
                )
            );
        } else {
            $open = array_diff(array_values($_milestones), array_values($this->getClosedMilestones($assoc)));
        }
        return $open;
    }

    public function getClosedMilestones($assoc = false) {
        // Fetch a list of milestones for the project
        $_milestones = $this->find(
            'list',
            array(
                'fields' => array('id'),
                'conditions'=> array('project_id' => $this->Project->id)
            )
        );
        // Fetch the milestone ids for open tasks for this project
        $_open_tasks = $this->Task->find(
            'list',
            array(
                'project_id' => $this->Project->id,
                'group' => array('milestone_id'),
                'fields' => array('milestone_id'),
                'conditions' => array(
                    'milestone_id NOT' => NULL,
                    'task_status_id <' => 4)
            )
        );
        $_diff = array_diff(array_values($_milestones), array_values($_open_tasks));
        // If we require an associated result (with names)
        if ($assoc) {
            $closed = $this->find(
                'list',
                array(
                    'fields' => array('id', 'subject'),
                    'conditions' => array(
                        'project_id' => $this->Project->id,
                        'id' => $_diff
                    )
                )
            );
        } else {
            $closed = $_diff;
        }
        return $closed;
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
        $events = $this->Project->ProjectHistory->fetchHistory($project, $number, $offset, $user, 'milestone');
        return $events;
    }
}
