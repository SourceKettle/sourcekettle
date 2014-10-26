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
App::uses('TaskPriority', 'Model');

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
		'ProjectDeletable',
		'FilterValid' => array(
			'nameField' => 'subject',
		),
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
		),
		'MilestoneBurndownLog' => array(
			'className' => 'MilestoneBurndownLog',
			'foreignKey' => 'milestone_id',
			'dependent' => true,
		),
	);

/**
 * afterFind function.
 * See: http://book.cakephp.org/2.0/en/models/callback-methods.html
 */
	public function afterFind($results, $primary = false) {
		if (!$primary) {
			return $results;
		}

		foreach ($results as $a => $result) {
			if (isset($result['Milestone']) && isset($result['Milestone']['id'])) {
				$results[$a]['Tasks'] = $this->taskSummaryForMilestone($result['Milestone']['id']);
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
		$tasks = $this->Task->find('all', array(
			'conditions' => array(
				'milestone_id' => $this->id,
				'TaskStatus.name =' => array('open', 'in progress')
			),
			'recursive' => 1,
		));

		foreach ($tasks as $task) {
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
				'order' => 'TaskPriority.level DESC',
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

		if (!$this->exists()) return null;
		$tasks = $this->Task->find(
			'all',
			array(
				'fields' => array(
					'Milestone.id',
					'Task.*',
					'TaskPriority.name',
					'TaskStatus.name',
					'TaskType.name',
					'Assignee.email',
					'Assignee.name',
					'Project.name',
				),
				'conditions' => array(
					'TaskStatus.name =' => $status,
					'Milestone.id =' => $id
				),
				'order' => 'TaskPriority.level DESC',
				'recursive' => 0,
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
			)
		);
		return $tasks;
	}

	public function taskSummaryForMilestone($id = null) {
		$this->id = $id;

		if (!$this->exists()) return null;

		$tasks = $this->Task->find(
			'all',
			array(
				'fields' => array(
					'COUNT(Task.id) AS numTasks',
					'TaskStatus.name',
					'SUM(Task.story_points) AS totalPoints',
				),
				'conditions' => array(
					'milestone_id =' => $id
				),
				'group' => 'TaskStatus.name',
				'recursive' => 0,
			)
		);

		$summary = array();
		foreach ($tasks as $taskSummary) {
			$status = $taskSummary['TaskStatus']['name'];
			$count = $taskSummary[0]['numTasks'];
			$points = $taskSummary[0]['totalPoints'];
			if (!$points) $points = 0;
			$summary[$status] = array('numTasks' => $count, 'points' => $points);
		}

		foreach ($this->Task->TaskStatus->getLookupTable() as $id => $status) {
			if (!isset($summary[$status['name']])) {
				$summary[$status['name']] = array('numTasks' => 0, 'points' => 0);
			}
		}
		return $summary;
	}

	// Helpers to get open and closed milestone lists
	public function getOpenMilestones() {
		return $this->listMilestones(true);
	}

	public function getClosedMilestones() {
		return $this->listMilestones(false);
	}

	public function listMilestones($open = true) {
		$milestones = $this->find('all', array(
			'conditions' => array(
				'project_id' => $this->Project->id,
				'is_open' => $open,
			),
			'fields' => array(
				'Milestone.id',
				'Milestone.subject',
				'Milestone.description',
				'Milestone.is_open',
				'Milestone.due',
			),
			'recursive' => 0,
		));

		foreach ($milestones as $id => $milestone) {
			//debug($milestones[$id]);
			//$milestones[$id]['Tasks'] = $this->taskSummaryForMilestone($milestone['Milestone']['id']);

			$milestones[$id]['Progress'] = array(
				'pointsComplete' => 0,
				'pointsTotal' => 0,
				'pointsPct' => 0,
				'tasksComplete' => 0,
				'tasksTotal' => 0,
				'tasksPct' => 0,
			);

			foreach ($milestones[$id]['Tasks'] as $status => $summary) {
				if ($status != 'dropped') {
					$milestones[$id]['Progress']['pointsTotal'] += $summary['points'];
					$milestones[$id]['Progress']['tasksTotal'] += $summary['numTasks'];
				}

				if (in_array($status, array('closed', 'resolved'))) {
					$milestones[$id]['Progress']['pointsComplete'] += $summary['points'];
					$milestones[$id]['Progress']['tasksComplete'] += $summary['numTasks'];
				}
			}

			if ($milestones[$id]['Progress']['tasksTotal']) {
				$milestones[$id]['Progress']['tasksPct'] = ceil((
					$milestones[$id]['Progress']['tasksComplete'] /
					$milestones[$id]['Progress']['tasksTotal']
				) * 100);
			}

			if ($milestones[$id]['Progress']['pointsTotal']) {
				$milestones[$id]['Progress']['pointsPct'] = ceil((
					$milestones[$id]['Progress']['pointsComplete'] /
					$milestones[$id]['Progress']['pointsTotal']
				) * 100);
			}
		}
		return $milestones;

	}

	// Helper to get a list of milestone options e.g. for selecting milestones to
	// add a task to. This is a simplified set of data compared to listMilestones().
	public function listMilestoneOptions() {
		$milestones = array('open' => array(), 'closed' => array());

		$milestones = array(
			"No assigned milestone",
			'Open' => $this->find('list', array(
				'conditions' => array(
					'project_id' => $this->Project->id,
					'is_open' => 1,
				),
				'fields' => array(
					'Milestone.id',
					'Milestone.subject',
				),
				'recursive' => 0,
			)),

			'Closed' => $this->find('list', array(
				'conditions' => array(
					'project_id' => $this->Project->id,
					'is_open' => 0,
				),
				'fields' => array(
					'Milestone.id',
					'Milestone.subject',
				),
				'recursive' => 0,
			)),
		);
		return $milestones;
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
		if (empty($tasks)) {
			return true;
		}

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
