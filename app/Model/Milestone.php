<?php
/**
 *
 * Milestone model for the SourceKettle system
 * Stores the Milestones for Projects in the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.Model
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppModel', 'Model');

class Milestone extends AppModel {

/**
 * Display field
 */
	public $displayField = 'subject';

/**
 * actsAs behaviours
 */
	public $actsAs = array(
		'ProjectComponent',
		'ProjectHistory',
		'ProjectDeletable'
	);

/**
 * Validation rules
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
		'is_open' => array(
			'boolean' => array(
				'rule' => array('boolean'),
			)
		)
	);

/**
 * belongsTo associations
 */
	public $belongsTo = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id',
		)
	);

/**
 * hasMany associations
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
 * See: http://book.cakephp.org/2.0/en/models/callback-methods.html
 */
	public function afterFind($results, $primary = false) {
		foreach ($results as $a => $result) {
			if (isset($result['Milestone']) && isset($result['Milestone']['id'])) {
				$this->Task->recursive = -1;
				$o = $results[$a]['Tasks']['open'] = $this->openTasksForMilestone($result['Milestone']['id']);
				$i = $results[$a]['Tasks']['in_progress'] = $this->inProgressTasksForMilestone($result['Milestone']['id']);
				$r = $results[$a]['Tasks']['resolved'] = $this->resolvedTasksForMilestone($result['Milestone']['id']);
				$c = $results[$a]['Tasks']['completed'] = $this->closedTasksForMilestone($result['Milestone']['id']);
				$d = $results[$a]['Tasks']['dropped'] = $this->droppedTasksForMilestone($result['Milestone']['id']);

				if ((count($o) + count($i) + count($r) + count($c)) > 0) {
					$results[$a]['Milestone']['percent'] = (count($r) + count($c)) / (count($o) + count($i) + count($r) + count($c)) * 100;
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
 * See: http://book.cakephp.org/2.0/en/models/callback-methods.html
 */
	public function beforeDelete($cascade = false) {
		foreach ($this->Task->find('all', array('conditions' => array('milestone_id' => $this->id, 'TaskStatus.name =' => array('open', 'in progress')))) as $task) {
			$this->Task->id = $task['Task']['id'];
			$this->Task->set('milestone_id', null);
			$this->Task->save();
		}
		$this->Task->deleteAll(array('milestone_id' => $this->id), false);

		return true;
	}

/**
 * openTasksForMilestone function.
 * Return the open tasks for a given milestone
 *
 * @param mixed $id the id of the milestone
 */
	public function openTasksForMilestone($id = null) {
		return $this->tasksOfStatusForMilestone($id, 'open');
	}

/**
 * inProgressTasksForMilestone function.
 * Return the in progress tasks for a given milestone
 *
 * @param mixed $id the id of the milestone
 */
	public function inProgressTasksForMilestone($id = null) {
		return $this->tasksOfStatusForMilestone($id, 'in progress');
	}

/**
 * resolvedTasksForMilestone function.
 * Return the resolved tasks for a given milestone
 *
 * @param mixed $id the id of the milestone
 */
	public function resolvedTasksForMilestone($id = null) {
		return $this->tasksOfStatusForMilestone($id, 'resolved');
	}

/**
 * closedTasksForMilestone function.
 * Return the closed tasks for a given milestone
 *
 * @param mixed $id the id of the milestone
 */
	public function closedTasksForMilestone($id = null) {
		return $this->tasksOfStatusForMilestone($id, 'closed');
	}

/**
 * closedOrResolvedTasksForMilestone function.
 * Return the closed or resolved tasks for a given milestone
 *
 * @param mixed $id the id of the milestone
 */
	public function closedOrResolvedTasksForMilestone($id = null) {
		$this->id = $id;

		if (!$this->exists()) return null;

		$tasks = $this->Task->find(
			'all',
			array(
				'field' => array('milestone_id'),
				'conditions' => array(
					'AND' => array(
						array(
							'OR' => array(
								array('TaskStatus.name ' => 'resolved'),
								array('TaskStatus.name ' => 'closed'),
							),
						),
						'milestone_id =' => $id
					)
				),
				'order' => 'task_priority_id DESC'
			)
		);
		return $tasks;
	}

/**
 * droppedTasksForMilestone function.
 * Return the dropped tasks for a given milestone
 *
 * @param mixed $id the id of the milestone
 */
	public function droppedTasksForMilestone($id = null) {
		return $this->tasksOfStatusForMilestone($id, 'dropped');
	}

/**
 * tasksOfStatusForMilestone function.
 * Return the tasks for a given milestone
 *
 * @param mixed $id the id of the milestone
 * @param mixed $status the status
 */
	public function tasksOfStatusForMilestone($id = null, $status = 'open') {
		$this->id = $id;

		$statusId = $this->Task->TaskStatus->nameToId($status);

		if (!$this->exists()) return null;

		$tasks = $this->Task->find(
			'all',
			array(
				'field' => array('milestone_id'),
				'conditions' => array(
					'task_status_id =' => $statusId,
					'milestone_id =' => $id
				),
				'order' => 'task_priority_id DESC',
			)
		);
		return $tasks;
	}

	public function blockerTasksForMilestone($id = null) {
		return $this->tasksOfPriorityForMilestone($id, 'blocker');
	}
	public function urgentTasksForMilestone($id = null) {
		return $this->tasksOfPriorityForMilestone($id, 'urgent');
	}
	public function majorTasksForMilestone($id = null) {
		return $this->tasksOfPriorityForMilestone($id, 'major');
	}
	public function minorTasksForMilestone($id = null) {
		return $this->tasksOfPriorityForMilestone($id, 'minor');
	}
/**
 * tasksOfPriorityForMilestone function.
 * Return the tasks for a given milestone
 *
 * @param mixed $id the id of the milestone
 * @param mixed $status the status
 */
	public function tasksOfPriorityForMilestone($id = null, $priority = 'minor') {
		$this->id = $id;

		$priorityId = $this->Task->TaskPriority->nameToId($priority);

		if (!$this->exists()) return null;

		$tasks = $this->Task->find(
			'all',
			array(
				'field' => array('milestone_id'),
				'conditions' => array(
					'task_priority_id =' => $priorityId,
					'milestone_id =' => $id
				),
				'order' => 'task_priority_id DESC'
			)
		);
		return $tasks;
	}

/**
 * getOpenMilestones function.
 * Get all the open milestones
 *
 * @param bool $assoc true if names needed
 */
	public function getOpenMilestones($assoc = false) {
		if ($assoc) {
			$fields = array('id', 'subject');
		} else {
			$fields = array('id');
		}

		// Fetch a list of milestones for the project
		return $this->find(
			'list',
			array(
				'fields' => $fields,
				'conditions' => array(
					'project_id' => $this->Project->id,
					'is_open' => true,
				)
			)
		);
	}

/**
 * getClosedMilestones function.
 * Get all the closed milestones
 *
 * @param bool $assoc true if names needed
 */
	public function getClosedMilestones($assoc = false) {
		if ($assoc) {
			$fields = array('id', 'subject');
		} else {
			$fields = array('id');
		}

		// Fetch a list of milestones for the project
		return $this->find(
			'list',
			array(
				'fields' => $fields,
				'conditions' => array(
					'project_id' => $this->Project->id,
					'is_open' => false,
				)
			)
		);
	}

/**
 * shiftTasks function
 * When closing or deleting a milestone, detach a set of its tasks and
 * assign them to another milestone (or no milestone if the new ID is zero).
 * NB this should be wrapped in a transaction when closing/deleting a milestone.
 * 
 * @param $id Milestone ID to remove tasks from
 * @param $newId Milestone ID to attach tasks to
 * @param $allTasks True if all the milestone's tasks should be moved (i.e. delete milestone), false if only non-resolved/closed tasks should be moved (i.e. close milestone)
 */
	public function shiftTasks($id = null, $newId = null, $allTasks = false, $options = array()) {
		if ($id == null) {
			return false;
		}

		// Retrieve Milestone; recurse to 2 models so we get TaskStatuses
		// so we can check the status by name
		$this->recursive = 2;
		$milestone = $this->open($id);

		// Now update all related tasks to attach them to the new milestone (or no milestone)
		$tasks = array();
		foreach ($milestone['Task'] as $task) {
			if ($allTasks || !in_array($task['TaskStatus']['name'], array('resolved', 'closed'))) {
				$task['milestone_id'] = $newId;
				$tasks[] = $task;
			}
		}

		// Save all the tasks
		return $this->Task->saveMany($tasks, $options);
	}

/**
 * TODO: Remove
 */
	public function fetchHistory($project = '', $number = 10, $offset = 0, $user = -1, $query = array()) {
		$events = $this->Project->ProjectHistory->fetchHistory($project, $number, $offset, $user, 'milestone');
		return $events;
	}
}
