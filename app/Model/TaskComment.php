<?php
/**
 *
 * TaskComment model for the DevTrack system
 * Stores the Comments for Tasks in the system
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
 * @property Task $Task
 * @property User $User
 */

App::uses('AppModel', 'Model');

class TaskComment extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'comment';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'task_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'comment' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Comments cannot be empty',
            ),
        ),
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Task' => array(
            'className' => 'Task',
            'foreignKey' => 'task_id',
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        )
    );

    function beforeSave($options = array()) {
        // Lock out those who are not allowed to write
        if ( !$this->Task->Project->hasWrite($this->_auth_user_id) ) {
            throw new ForbiddenException(__('You do not have permissions to modifiy this project'));
        }
        return true;
    }
}
