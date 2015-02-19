<?php
/**
 *
 * Task model for the SourceKettle system
 * Stores the Tasks for a project in the system
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
App::uses('TimeString', 'Time');

class Task extends AppModel {

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
		),
		'task_status_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'task_priority_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'time_estimate' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Number of minutes, or use format e.g. "2w 3d 6h 9m" (weeks, days, hours and minutes)',
				'allowEmpty' => true,
			),
		),
		'story_points' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please enter a number',
				'allowEmpty' => true,
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
		),
		'Story' => array(
			'className' => 'Story',
			'foreignKey' => 'story_id',
		)
	);

/**
 * hasMany associations
 */
	public $hasMany = array(
		'TaskComment' => array(
			'className' => 'TaskComment',
			'foreignKey' => 'task_id',
			'dependent' => true,
		),
		'Time' => array(
			'className' => 'Time',
			'foreignKey' => 'task_id',
			'dependent' => true,
		),
	);

/**
 * hasAndBelongsToMany associations
 */
	public $hasAndBelongsToMany = array(
		'DependsOn' => array(
			'className' => 'Task',
			'joinTable' => 'task_dependencies',
			'foreignKey' => 'child_task_id',
			'associationForeignKey' => 'parent_task_id',
		),
		'DependedOnBy' => array(
			'className' => 'Task',
			'joinTable' => 'task_dependencies',
			'foreignKey' => 'parent_task_id',
			'associationForeignKey' => 'child_task_id',
		),
	);

/**
 * Determine whether the dependencies of a task are met
 * See: http://book.cakephp.org/2.0/en/models/callback-methods.html
 */
	public function afterFind($results, $primary = false) {
		parent::afterFind($results, $primary);

		foreach ($results as $key => $val) {
			if (isset($val['Task']['time_estimate'])) {
				$split = TimeString::renderTime($val['Task']['time_estimate']);
				$results[$key]['Task']['time_estimate'] = $split['s'];
			}
		}
		return $results;
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
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		// Get the DB table prefix from our database config, for if
		// we have multiple systems in the same DB or fixtures have a prefix
		$db =& ConnectionManager::getDataSource($this->useDbConfig);
		$table_prefix = $db->config['prefix'];

		$this->virtualFields = array(
			'public_id' => "(SELECT ".
				"COUNT(`{$table_prefix}{$this->table}`.`id`) ".
			"FROM ".
				"`{$table_prefix}{$this->table}` ".
			"WHERE ".
				"`{$table_prefix}{$this->table}`.`id` <= `{$this->alias}`.`id` ".
			"AND ".
				"`{$table_prefix}{$this->table}`.`project_id` = `{$this->alias}`.`project_id`)",

			'dependenciesComplete' => "(SELECT ".
				"(COUNT(`DepTasks`.`id`) = 0) ".
			"FROM ".
				"`{$table_prefix}task_dependencies` TaskDeps ".
			"INNER JOIN `{$table_prefix}{$this->table}` DepTasks ".
				"ON `DepTasks`.`id` = `TaskDeps`.`parent_task_id` ".
			"INNER JOIN `{$table_prefix}task_statuses` TaskStatuses ".
				"ON `TaskStatuses`.`id` = `DepTasks`.`task_status_id` ".
			"WHERE ".
				"`TaskDeps`.`child_task_id` = `{$this->alias}`.`id` ".
			"AND ".
				"`TaskStatuses`.`name` NOT IN ('resolved', 'closed'))",
		);
	}

/**
 */
	public function beforeValidate($options = array()) {

		if (empty($this->data)) {
			return false;
		}

		if (!isset($this->data['Task']['id']) && !isset($this->id) && !isset($this->data['Task']['project_id'])) {
			return false;
		}

		if (isset($this->data['Task']['time_estimate']) && !is_int($this->data['Task']['time_estimate'])) {
			$this->data['Task']['time_estimate'] = TimeString::parseTime($this->data['Task']['time_estimate']);
		}

		// If we have priority and status names, replace them with IDs for saving
		if (isset($this->data['Task']['type'])) {
			$this->data['Task']['task_type_id'] = $this->TaskType->nameToID($this->data['Task']['type']);
			unset($this->data['Task']['type']);
		}
		if (isset($this->data['Task']['priority'])) {
			$this->data['Task']['task_priority_id'] = $this->TaskPriority->nameToID($this->data['Task']['priority']);
			unset($this->data['Task']['priority']);
		}
		if (isset($this->data['Task']['status'])) {
			$this->data['Task']['task_status_id'] = $this->TaskStatus->nameToID($this->data['Task']['status']);
			unset($this->data['Task']['status']);
		}

		if (array_key_exists('milestone_id', $this->data['Task']) && $this->data['Task']['milestone_id'] === null) {
			$this->data['Task']['milestone_id'] = 0;
		}
		return true;
	}

	// Given a list of tasks' public IDs, convert to private IDs, remove duplicates
	// and remove the current task's ID if present.
	// TODO a horrible, horrible fudge that should not exist.
	private function __sanitiseDependencies($taskId, $projectId, $depList) {
		
		// We'll normally have a list of public IDs, but if we get a list of objects,
		// crunch them down
		$depList = array_map(function($a) {
			if (is_numeric($a)) {
				return $a;
			} elseif (is_array($a) && isset($a['public_id'])) {
				return $a['public_id'];
			} elseif (is_array($a) && isset($a['id'])) {
				return $a['id'];
			}
			return null;
		}, $depList);

		// Now get unique values only...
		$depList = array_unique(array_values($depList));

		// Find query doesn't work with an array of length 1 for some reason
		if (count($depList) == 1) {
			$depList = $depList[0];
		}

		// Convert public IDs to real IDs
		$depList = array_keys($this->find('list', array(
			'conditions' => array(
				'Task.project_id' => $projectId,
				'Task.public_id' => $depList
			),
		)));

		// Make sure the task doesn't depend on itself!
		foreach ($depList as $key => $id) {
			if (isset($taskId) && $id == $taskId) {
				unset ($depList[$key]);
			}
		}

		return $depList;
	}


/**
 * beforeSave function
 *
 * @access public
 * @param array $options (default: empty array)
 * @return bool True if the save was successful.
 */
 	private $__burndownLog = array('milestone' => array(), 'project' => array());

	public function beforeSave($options = array()) {
		// Find the existing task if there is one
		$task = $this->find('first', array(
			'conditions' => array('Task.id' => $this->id),
			'fields' => array('Task.project_id', 'Task.milestone_id'),
			//'recursive' => -1,
		));

		// Creating the task...
		// TODO I have a horrible feeling that this is a terrible idea - what happens with saveMany()?
		if (empty($task)) {
			$taskId = 0;
			$projectId = $this->data['Task']['project_id'];
			$milestoneId = @$this->data['Task']['milestone_id'];
		
		// Updating the task...
		} else {
			$taskId = $this->id;
			$projectId = $task['Task']['project_id'];
			$milestoneId = @$task['Task']['milestone_id'];
		}

		// Remember the milestone and project ID for our burndown logging
		// NB this is because the milestone ID may have changed by the time we log it!
		$this->__burndownLog[$taskId] = array(
			'milestone_id' => $milestoneId, 
			'project_id' => $projectId, 
		);

		// Update dependency list
		if (isset($this->data['DependsOn']) && is_array($this->data['DependsOn'])) {
			$this->data['DependsOn'] = $this->__sanitiseDependencies($taskId, $projectId, $this->data['DependsOn']);
		}
		if (isset($this->data['DependedOnBy']) && is_array($this->data['DependedOnBy'])) {
			$this->data['DependedOnBy'] = $this->__sanitiseDependencies($taskId, $projectId, $this->data['DependedOnBy']);
		}

		return true;
	}

/**
 * afterSave function - logs project/milestone burndown chart updates
 */
	public function afterSave($created, $options = array()) {
		if ($created) {
			$project_id = $this->__burndownLog[0]['project_id'];
			$milestone_id = $this->__burndownLog[0]['milestone_id'];
		} else {
			$project_id = $this->__burndownLog[$this->id]['project_id'];
			$milestone_id = $this->__burndownLog[$this->id]['milestone_id'];
		}

		$counts = $this->__getBurndownCounts(array("Task.project_id" => $project_id));
		$counts['project_id'] = $project_id;
		$counts['timestamp'] = DboSource::expression('NOW()');
		$this->Project->ProjectBurndownLog->save(array(
			'ProjectBurndownLog' => $counts,
		));

		// If the task is part of a milestone, get the aggregate milestone data and log it
		if ($milestone_id) {

			$counts = $this->__getBurndownCounts(array("Task.milestone_id" => $milestone_id));
			$counts['milestone_id'] = $milestone_id;
			$counts['timestamp'] = DboSource::expression('NOW()');

			$this->Milestone->MilestoneBurndownLog->save(array(
				'MilestoneBurndownLog' => $counts,
			));
		}
	}

/**
 * Gets the current count of tasks and their estimates in an "open" state (i.e. "tasks
 * that still need to be done") and a "closed" state (i.e. "tasks that have been done or postponed").
 */
	private function __getBurndownCounts($conditions) {
	
		$db_conditions = array_merge($conditions, array("TaskStatus.name" => array("open", "in progress")));

		$data = $this->find("first", array(
			'conditions' => $db_conditions,
			'fields' => array(
				'COUNT(Task.id) AS open_task_count',
				'SUM(Task.time_estimate) AS open_minutes_count',
				'SUM(Task.story_points) AS open_points_count',
			),
		));
		$counts = $data[0];

		$db_conditions = array_merge($conditions, array("TaskStatus.name !=" => array("open", "in progress")));
		$data = $this->find("first", array(
			'conditions' => $db_conditions,
			'fields' => array(
				'COUNT(Task.id) AS closed_task_count',
				'SUM(Task.time_estimate) AS closed_minutes_count',
				'SUM(Task.story_points) AS closed_points_count',
			),
		));
		$counts = array_merge($counts, $data[0]);

		// Sanity check: make sure nulls become zeroes, and everything's a number
		foreach (array_keys($counts) as $k) {
			if (!$counts[$k]) {
				$counts[$k] = 0;
			} else {
				$counts[$k] = (int)$counts[$k];
			}
		}
		return $counts;
	}

/**
 * isAssignee function.
 * Returns true if the current user is assigned to the task
 */
	public function isAssignee($userId) {
		return $userId == $this->field('assignee_id');
	}

/**
 * isOpen function.
 * Returns true if a task is open
 */
	public function isOpen() {
		$this->TaskStatus->id = $this->field('task_status_id');
		return $this->TaskStatus->field('name') == 'open';
	}

/**
 * isInProgress function.
 * Returns true if a task is in progress
 * @throws
 */
	public function isInProgress() {
		$this->TaskStatus->id = $this->field('task_status_id');
		return $this->TaskStatus->field('name') == 'in progress';
	}

/**
 * TODO: Remove
 */
	public function fetchHistory($project = '', $number = 10, $offset = 0, $user = -1, $query = array()) {
		$events = $this->Project->ProjectHistory->fetchHistory($project, $number, $offset, $user, 'task');
		return $events;
	}

/**
 * TODO: Remove
 */
	public function getTitleForHistory($id = null) {
		if ($id == null) {
			$id = $this->id;
		}
		$this->id = $id;
		if (!$this->exists()) {
			return null;
		} else {
			return '#' . $this->field('public_id') . " (" . $this->field('subject') . ")";
		}
	}

	public function fetchLoggableTasks($userId) {

		$myTasks = $this->find(
			'list',
			array(
				'conditions' => array(
					'TaskStatus.name !=' => array('closed', 'dropped'),
					'Task.project_id' => $this->Project->id,
					'Task.assignee_id' => $userId,
				),
				'fields' => array('Task.public_id', 'Task.subject'),
				'recursive' => 1,
			)
		);

		$othersTasks = $this->find(
			'list',
			array(
				'conditions' => array(
					'TaskStatus.name !=' => array('closed', 'dropped'),
					'Task.project_id' => $this->Project->id,
					'Task.assignee_id !=' => $userId,
				),
				'fields' => array('Task.public_id', 'Task.subject'),
				'recursive' => 1,
			)
		);

		return array(
			'No Assigned Task',
			'Your Tasks' => $myTasks,
			'Others Tasks' => $othersTasks
		);
	}

	public function listTasksOfStatusFor($status = 'open', $relatedClass = 'Milestone', $id = null, $maxAgeDays = null) {

		$conditions = array(
			'TaskStatus.name =' => $status,
			$relatedClass.'.id =' => $id
		);

		if ($maxAgeDays != null) {
			$minDate = new DateTime("$maxAgeDays days ago");
			$conditions['Task.modified >'] = $minDate->format('Y-m-d');
		}

		return $this->find(
			'all',
			array(
				'fields' => array(
					'Milestone.id',
					'Milestone.subject',
					'Task.*',
					'TaskPriority.name',
					'TaskStatus.name',
					'TaskType.name',
					'Assignee.email',
					'Assignee.name',
					'Project.name',
				),
				'conditions' => $conditions,
				'order' => 'TaskPriority.level DESC',
				'recursive' => 0,
			)
		);

	}

	public function listTasksOfPriorityFor($priority = 'major', $relatedClass = 'Milestone', $id = null) {

		return $this->find(
			'all',
			array(
				'fields' => array(
					'Milestone.id',
					'Milestone.subject',
					'Task.*',
					'TaskPriority.name',
					'TaskStatus.name',
					'TaskType.name',
					'Assignee.email',
					'Assignee.name',
					'Project.name',
				),
				'conditions' => array(
					'TaskPriority.name =' => $priority,
					$relatedClass.'.id =' => $id
				),
				'order' => 'TaskPriority.level DESC',
				'recursive' => 0,
			)
		);
	}

	public function getTree($projectId, $publicId, $seen = array()) {
		$primary = $this->find('first', array(
			'conditions' => array('Task.project_id' => $projectId, 'Task.public_id' => $publicId),
		));

		if (empty($primary)) {
			return array();
		}
	
		$subTasks = $primary['DependsOn'];
		$primary['Task']['subTasks'] = array();

		// Loop detection - if the subtask has already been seen, just add it and mark it as a dupe
		if (in_array($publicId, $seen)) {
			$primary['Task']['loop'] = true;
			return $primary['Task'];
		}

		// Otherwise remember we've seen this task, then add its subtree
		$seen[] = $primary['Task']['public_id'];
		$primary['Task']['loop'] = false;

		foreach ($subTasks as $subTask) {
			$primary['Task']['subTasks'][] = $this->getTree($projectId, $subTask['public_id'], $seen);
		}

		return $primary['Task'];
	}

	// Does a user ID have write access to a task?
	// Basically the same as "do they have write access to the project", except they
	// can also modify tasks they created or they are assigned to (so guests can do more things)
	public function hasWrite($userId, $task) {
		return (
			$task['Task']['owner_id'] == $userId ||
			$task['Task']['assignee_id'] == $userId ||
			$this->Project->hasWrite($userId, $task['Task']['project_id'])
		);
	}
}
