<?php
/**
 *
 * TaskDependency model for the DevTrack system
 * Stores dependency relationships between tasks
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.Model
 * @since         DevTrack v 0.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 *
App::uses('AppModel', 'Model');

class TaskDependency extends AppModel {

    /**
     * Display field
     *
     * @var string
     *
    public $displayField = 'subject';

    public $actsAs = array(
        'ProjectComponent',
        'ProjectHistory'
    );

    /**
     * Validation rules
     *
     * @var array
     *
    public $validate = array(
        'parent_task_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'child_task_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
    );

    /**
     * hasMany associations
     *
     * @var array
     *
    public $hasMany = array(
        'ChildTask' => array(
            'className'  => 'Task',
            'foreignKey' => 'child_task_id',
            'dependent'  => true,
        ),
        'ParentTask' => array(
            'className'  => 'Task',
            'foreignKey' => 'parent_task_id',
            'dependent'  => true,
        ),
    );


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
     *
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
     *
    public function getTitleForHistory($id) {
        $this->id = $id;
        if (!$this->exists()) {
            return null;
        } else {
            return '#'.$id;
        }
    }
}

*/
