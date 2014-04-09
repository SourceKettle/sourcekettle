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
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.Model
 * @since         DevTrack v 0.1
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
		'ProjectHistory'
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
			'inlist' => array(
				'rule' => array('inlist', array(1,2,3,4,5,6,7,8,'1','2','3','4','5','6','7','8')),
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

		// TODO this is a bit hacky and nicked from Time model, should be a library function?
		foreach ($results as $key => $val) {
			if (isset($val['Task']['time_estimate'])) {
				//$split = $this->splitMins($val['Task']['time_estimate']);
				$split = TimeString::renderTime($val['Task']['time_estimate']);
				$results[$key]['Task']['time_estimate'] = $split['s'];
			}
		}

		if (isset($results['id'])) {
			return $results;
		} else if (isset($results['Task'])) {
			return $this->__setDependenciesComplete($results);
		} else {
			foreach ($results as $key => $value) {
				$results[$key] = $this->__setDependenciesComplete($value);
			}
		}
		return $results;
	}

	private function __setDependenciesComplete($result) {
		if (isset($result['DependsOn'])) {
			if (!empty($result['DependsOn'][0])) {
				$completed = true;
				foreach ($result['DependsOn'] as $dependsOn) {
					if ($dependsOn['task_status_id'] < 3) {
						$completed = false;
						break;
					}
				}
				if ($completed) {
					if (isset($result['Task'])) {
						$result['Task']['dependenciesComplete'] = true;
					} else {
						$result['dependenciesComplete'] = true;
					}
				} else {
					if (isset($result['Task'])) {
						$result['Task']['dependenciesComplete'] = false;
					} else {
						$result['dependenciesComplete'] = false;
					}
				}
			} else {
				if (isset($result['Task'])) {
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
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->virtualFields = array(
			'public_id' => "(SELECT COUNT(`{$this->table}`.`id`) FROM `{$this->table}` WHERE `{$this->table}`.`id` <= `{$this->alias}`.`id` AND `{$this->table}`.`project_id` = `{$this->alias}`.`project_id`)"
		);
	}

/**
 * TODO needs consolidating with beforeSave
 */
	public function beforeValidate($options = array()) {
		if (!isset($this->data['Task']['time_estimate'])) {
			return true;
		}

		/*
		$string = $this->data['Task']['time_estimate'];

		if (is_int($string)) {
			return true;
		}

		preg_match("#(?P<weeks>[0-9]+)\s*w(eeks?)?#", $string, $weeks);
		preg_match("#(?P<days>[0-9]+)\s*d(ays?)?#", $string, $days);
		preg_match("#(?P<hours>[0-9]+)\s*h(rs?|ours?)?#", $string, $hours);
		preg_match("#(?P<mins>[0-9]+)\s*m(ins?)?#", $string, $mins);

		$time = (int)0;
		$time += ((isset($weeks['weeks'])) ? 7 * 24 * 60 * (int)$weeks['weeks'] : 0);
		$time += ((isset($days['days'])) ? 24 * 60 * (int)$days['days'] : 0);
		$time += ((isset($hours['hours'])) ? 60 * (int)$hours['hours'] : 0);
		$time += ((isset($mins['mins'])) ? (int)$mins['mins'] : 0);

		$this->data['Task']['time_estimate'] = $time;
		*/
		if(is_int($this->data['Task']['time_estimate'])){
			return true;
		}

		$this->data['Task']['time_estimate'] = TimeString::parseTime($this->data['Task']['time_estimate']);
		return true;
	}
/**
 * beforeSave function
 *
 * @access public
 * @param array $options (default: empty array)
 * @return bool True if the save was successful.
 */
	public function beforeSave($options = array()) {
		// Parse time estimate string back into minutes
		// TODO hacky
		/*if (isset($this->data['Task']['time_estimate']) && !is_int($this->data['Task']['time_estimate'])) {

			$string = $this->data['Task']['time_estimate'];

			preg_match("#(?P<weeks>[0-9]+)\s*w(eeks?)?#", $string, $weeks);
			preg_match("#(?P<days>[0-9]+)\s*d(ays?)?#", $string, $days);
			preg_match("#(?P<hours>[0-9]+)\s*h(rs?|ours?)?#", $string, $hours);
			preg_match("#(?P<mins>[0-9]+)\s*m(ins?)?#", $string, $mins);

			$time = (int)0;
			$time += ((isset($weeks['weeks'])) ? 7 * 24 * 60 * (int)$weeks['weeks'] : 0);
			$time += ((isset($days['days'])) ? 24 * 60 * (int)$days['days'] : 0);
			$time += ((isset($hours['hours'])) ? 60 * (int)$hours['hours'] : 0);
			$time += ((isset($mins['mins'])) ? (int)$mins['mins'] : 0);

			$this->data['Task']['time_estimate'] = $time;

		}*/

		if (isset($this->data['Task']['time_estimate']) && !is_int($this->data['Task']['time_estimate'])) {
			$this->data['Task']['time_estimate'] = TimeString::parseTime($this->data['Task']['time_estimate']);
		}

		if (isset($this->data['DependsOn']['DependsOn']) && is_array($this->data['DependsOn']['DependsOn'])) {
			foreach ($this->data['DependsOn']['DependsOn'] as $key => $dependsOn) {
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
 * Returns true if the current user is assigned to the task
 */
	public function isAssignee() {
		return User::get('id') == $this->field('assignee_id');
	}

/**
 * isOpen function.
 * Returns true if a task is open
 */
	public function isOpen() {
		return $this->field('task_status_id') == 1;
	}

/**
 * isInProgress function.
 * Returns true if a task is in progress
 * @throws
 */
	public function isInProgress() {
		return $this->field('task_status_id') == 2;
	}

	public function splitMins($in) {
		// TODO make this a library function
		if (!is_numeric($in)) {
			throw new InvalidArgumentException("Minutes must be an integer: ${in} given");
		}

		$weeks = 0;
		$days = 0;
		$hours = 0;
		$mins = (int)$in;

		while ($mins >= 60) {
			$hours += 1;
			$mins -= 60;
		}

		while ($hours >= 24) {
			$days += 1;
			$hours -= 24;
		}

		while ($days >= 7) {
			$weeks += 1;
			$days  -= 7;
		}

		$output = array(
			'w' => $weeks,
			'd' => $days,
			'h' => $hours,
			'm' => $mins,
			't' => (int)$in, //legacy TODO: remove
			's' => "${hours}h ${mins}m",
		);

		if ($weeks > 0) {
			$output['s'] = "${weeks}w ${days}d ${hours}h ${mins}m";
		} elseif ($days > 0) {
			$output['s'] = "${days}d ${hours}h ${mins}m";
		}

		return $output;
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
	public function getTitleForHistory($id) {
		$this->id = $id;
		if (!$this->exists()) {
			return null;
		} else {
			return '#' . $id;
		}
	}

	public function fetchLoggableTasks() {
		$myTasks = $this->find(
			'list',
			array(
				'conditions' => array(
					'Task.task_status_id <' => 4,
					'Task.project_id' => $this->Project->id,
					'Task.assignee_id' => User::get('id'),
				)
			)
		);
		$othersTasks = $this->find(
			'list',
			array(
				'conditions' => array(
					'Task.task_status_id <' => 4,
					'Task.project_id' => $this->Project->id,
					'Task.assignee_id !=' => User::get('id'),
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
