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
		'CakeDCUtils.SoftDelete',
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
			),
		),
		'starts' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
			'checkRange' => array(
				'rule' => array('checkRange'),
				'message' => 'Start date must be before due date',
			),
		),
		'due' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
			'checkRange' => array(
				'rule' => array('checkRange'),
				'message' => 'Due date must be after start date',
			),
		),
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

	// Checks that the start/end date are the correct way round
	public function checkRange($value) {
		if (!isset($this->data['Milestone']['starts']) || !isset($this->data['Milestone']['due'])) {
			return false;
		}
		return (strtotime($this->data['Milestone']['starts']) < strtotime($this->data['Milestone']['due']));
	}

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
/*
	public function beforeValidate($options = array()) {

		// Need a start date
		if (!isset($this->data['starts'])) {
			return false;
		}

		// ...and a due date
		if (!isset($this->data['due'])) {
			return false;
		}

		// ...and it can't be due before it starts.
		if (strtotime($this->data['starts']) >= strtotime($this->data['due'])) {
			return false;
		}
	}*/

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
			'contain' => array('TaskStatus'),
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
 * tasksOfStatusForMilestone function.
 * Return the tasks for a given milestone
 *
 * @param mixed $id the id of the milestone
 * @param mixed $status the status
 */
	public function tasksOfStatusForMilestone($id = null, $status = 'open') {
		return $this->Task->listTasksOfStatusFor($status, 'Milestone', $id);
	}

/**
 * tasksOfPriorityForMilestone function.
 * Return the tasks for a given milestone
 *
 * @param mixed $id the id of the milestone
 * @param mixed $status the status
 */
	public function tasksOfPriorityForMilestone($id = null, $priority = 'minor') {
		return $this->Task->listTasksOfPriorityFor($priority, 'Milestone', $id);
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
				'contain' => array('TaskStatus'),
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
				'Milestone.starts',
				'Milestone.due',
			),
			'contain' => false,
		));

		foreach ($milestones as $id => $milestone) {

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

		$milestones = array(
			__('No assigned milestone'),
			__('Open') => $this->find('list', array(
				'conditions' => array(
					'project_id' => $this->Project->id,
					'is_open' => 1,
				),
				'fields' => array(
					'Milestone.id',
					'Milestone.subject',
				),
				'contain' => false,
			)),

			__('Closed') => $this->find('list', array(
				'conditions' => array(
					'project_id' => $this->Project->id,
					'is_open' => 0,
				),
				'fields' => array(
					'Milestone.id',
					'Milestone.subject',
				),
				'contain' => false,
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

		// Make sure we pull in the TaskStatus so we can check by name instead of status ID
		$milestone = $this->open($id, array('Task' => array('TaskStatus')));

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

	// Find logged changes for a milestone between two dates
	public function fetchBurndownLog($id, $start, $end) {

		$log = array();

		$entries = $this->MilestoneBurndownLog->find('all', array(
			'conditions' => array(
				'milestone_id' => $id,
				'timestamp <=' => $end->format('Y-m-d 23:59:59'),
				'timestamp >=' => $start->format('Y-m-d 00:00:00'),
			),
			'fields' => array(
				'timestamp',
				'DATE(timestamp) AS day',
				'open_task_count',
				'open_minutes_count',
				'open_points_count',
				'closed_task_count',
				'closed_minutes_count',
				'closed_points_count',
			),
			'order' => array('timestamp'),
			'contain' => false,
		));

		foreach ($entries as $entry) {
			$day = $entry[0]['day'];

			// Add in all the counts - note that if we get multiple counts for a single day, we'll
			// overwrite until we only have the latest count for the day.
			$log[$day] = array(
				'open' => array(
					'points'  => $entry['MilestoneBurndownLog']['open_points_count'],
					'tasks'   => $entry['MilestoneBurndownLog']['open_task_count'],
					'minutes' => $entry['MilestoneBurndownLog']['open_minutes_count'],
				),
				'closed' => array(
					'points'  => $entry['MilestoneBurndownLog']['closed_points_count'],
					'tasks'   => $entry['MilestoneBurndownLog']['closed_task_count'],
					'minutes' => $entry['MilestoneBurndownLog']['closed_minutes_count'],
				),
			);
		}
	
		// Pad out any missing days between the start and end date
		// Note that there may (should!) be logged entries before the milestone start date
		// (from the planning phase), so get the latest counts to start us off
		$startingLog = $this->MilestoneBurndownLog->find('first', array(
			'conditions' => array(
				'milestone_id' => $id,
				'timestamp <' => $start->format('Y-m-d 00:00:00'),
			),
			'fields' => array(
				'open_task_count',
				'open_minutes_count',
				'open_points_count',
				'closed_task_count',
				'closed_minutes_count',
				'closed_points_count',
			),
			'order' => array('timestamp DESC'),
			'contain' => false,
		));

		if (!empty($startingLog)) {
			$startingLog = $startingLog['MilestoneBurndownLog'];
			$last_open_tasks = $startingLog['open_task_count'];
			$last_open_minutes = $startingLog['open_minutes_count'];
			$last_open_points = $startingLog['open_points_count'];
			$last_closed_tasks = $startingLog['closed_task_count'];
			$last_closed_minutes = $startingLog['closed_minutes_count'];
			$last_closed_points = $startingLog['closed_points_count'];
		}

		// Start with 2 days before milestone start, and add a day at the start of the loop.
		// This means we get everything from the start to the end date inclusive.
		// Note we also get the counts from before the milestone start date; the idea is
		// that we spend some time planning in advance, and the totals show up as the start point.
		$current = clone($start);
		$current->sub(new DateInterval('P2D'));
		$fakeEnd = clone $end;

		// Go one day over the end too
		$fakeEnd->add(new DateInterval('P1D'));

		while ($fakeEnd->diff($current)->days > 0) {
			$current->add(new DateInterval('P1D'));
			$day = $current->format('Y-m-d');
			if (isset($log[$day])) {
				$last_open_points = $log[$day]['open']['points'];
				$last_open_tasks = $log[$day]['open']['tasks'];
				$last_open_minutes = $log[$day]['open']['minutes'];
				$last_closed_points = $log[$day]['closed']['points'];
				$last_closed_tasks = $log[$day]['closed']['tasks'];
				$last_closed_minutes = $log[$day]['closed']['minutes'];
			} else { 
				$log[$day] = array(
					'open' => array(
						'points'  => @$last_open_points ?: 0,
						'tasks'   => @$last_open_tasks ?: 0,
						'minutes' => @$last_open_minutes ?: 0,
					),
					'closed' => array(
						'points'  => @$last_closed_points ?: 0,
						'tasks'   => @$last_closed_tasks ?: 0,
						'minutes' => @$last_closed_minutes ?: 0,
					),
				);
			}

		}

		ksort($log);
		return $log;

	}

	function storiesForMilestone($milestoneId) {
	
		$storyIds = $this->Task->find("list", array(
			'conditions' => array(
				'Task.milestone_id' => $milestoneId,
				'Task.story_id !=' => null,
			),
			'fields' => array('Task.story_id'),
		));

		return $this->Task->Story->find("all", array(
			'conditions' => array('Story.id' => $storyIds),
			'order' => array('id'),
			'contain' => array(
				'Project' => array(
					'name',
				),
				'Task' => array(
					'id', 'public_id', 'subject', 'story_points', 'story_id', 'milestone_id',
					'TaskStatus' => array('id', 'name'),
					'TaskType' => array('id', 'name'),
					'TaskPriority' => array('id', 'name', 'level'),
				),
			),
		));
	}
}
