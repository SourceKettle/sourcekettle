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

/**
 * See: http://book.cakephp.org/2.0/en/models/callback-methods.html
 * @throws ForbiddenException
 */
	public function beforeSave($options = array()) {
		// Lock out those who are not allowed to write
		if ( !$this->Task->Project->hasWrite(User::get('id')) ) {
			throw new ForbiddenException(__('You do not have permissions to modify this project'));
		}
		return true;
	}

/**
 * open function.
 * open the comment for editing
 *
 * @param mixed $id the id of the comment
 * @param mixed $taskId the task
 * @param bool $ownershipRequired do we need write permissions
 * @param bool $allowAdmin are admins allowed
 * @throws NotFoundException
 * @throws ForbiddenException
 */
	public function open($id, $taskId = null, $ownershipRequired = false, $allowAdmin = false) {
		$this->id = $id;

		if (!$this->exists()) {
			throw new NotFoundException(__('Invalid ' . $this->name));
		}
		if ($taskId && $this->field('task_id') != $taskId) {
			throw new NotFoundException(__('Invalid ' . $this->name));
		}
		if ($ownershipRequired && $this->field('user_id') != User::get('id')) {
			if (!$allowAdmin || !User::get('is_admin')) {
				throw new ForbiddenException(__('Ownership required'));
			}
		}
		return $this->read();
	}
}
