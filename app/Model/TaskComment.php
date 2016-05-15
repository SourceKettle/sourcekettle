<?php
/**
 *
 * TaskComment model for the SourceKettle system
 * Stores the Comments for Tasks in the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.Model
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * @property Task $Task
 * @property User $User
 */
App::uses('AppModel', 'Model');

class TaskComment extends AppModel {

	public $actsAs = array(
		//'CakeDCUtils.SoftDelete',
	);
/**
 * Display field
 */
	public $displayField = 'comment';

/**
 * Validation rules
 */
	public $validate = array(
		'task_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
			'notblank' => array(
				'rule' => array('notblank'),
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
			'notblank' => array(
				'rule' => array('notblank'),
			),
		),
		'comment' => array(
			'notblank' => array(
				'rule' => array('notblank'),
				'message' => 'Comments cannot be empty',
			),
		),
	);

/**
 * belongsTo associations
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

}
