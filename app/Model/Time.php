<?php
/**
 *
 * Time model for the SourceKettle system
 * Represents a time segment
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle
 * @package       SourceKettle.Model
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppModel', 'Model');
App::uses('InvalidArgumentException', 'Exception');
App::uses('TimeString', 'Time');

class Time extends AppModel {

/**
 * __currentDate
 * The current date used for validating input.
 */
	private $__currentDate;

/**
 * __minimumAllowedYear
 * The minimum year which can be used for allocated time.
 */
	private $__minimumAllowedYear = 2010;

/**
 * __maximumAllowedYear
 * The maximum year which can be used for allocated time.
 */
	private $__maximumAllowedYear;

/**
 * name
 * Class name.
 */
	public $name = 'Time';

/**
 * displayField
 * The field used as a label for the model.
 */
	public $displayField = 'id';

/**
 * actsAs
 * List of Behaviors that apply to this Model.
 */
	public $actsAs = array(
		'ProjectComponent',
		'ProjectHistory'
	);

/**
 * Validation rules.
 * Ensure that saved data adheres to the defined rules.
 */
	public $validate = array(
		'project_id' => array(
			'rule' => 'numeric',
			'message' => 'A valid project id was not entered',
		),
		'user_id' => array(
			'rule' => array('numeric'),
			'message' => 'A valid user id was not entered',
		),
		// TODO why max of 3 days? Seems reasonable but hard coded...
		'mins' => array(
			'maximum' => array(
				'rule' => array('comparison', '<', 4320),
				'message' => 'Maximum allowed time is 3 days.',
			),
			'minimum' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Time logged must be greater than 0.',
			),
		),
		'date' => 'date',
	);

/**
 * belongsTo associations
 */
	public $belongsTo = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id'
		),
		'User' => array(
			'classname' => 'User',
			'foreignkey' => 'user_id'
		),
		'Task' => array(
			'classname' => 'Task',
			'foreignkey' => 'task_id'
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		// Set the current date
		$this->__currentDate = new DateTime(null, new DateTimeZone('UTC'));
		$this->__maximumAllowedYear = $this->currentYear(1);
	}

/**
 * See: http://book.cakephp.org/2.0/en/models/callback-methods.html
 * Pre-format the mins to prevent additional formatting later
 */
	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val['Time']['mins'])) {
				$results[$key]['Time']['minutes'] = TimeString::renderTime($val['Time']['mins']);
			}
		}
		return $results;
	}

/**
 * See: http://book.cakephp.org/2.0/en/models/callback-methods.html
 * Take the mins string with hours and mins in it (e.g. 1h 20m)
 * and turn it into a number of mins
 */
	public function beforeValidate($options = array()) {
		if (!isset($this->data['Time']['mins'])) {
			return true;
		}

		if (is_int($this->data['Time']['mins'])) {
			return true;
		}

		$this->data['Time']['mins'] = TimeString::parseTime($this->data['Time']['mins']);
		return true;
	}

/**
 * currentYear function.
 * Return the current year ± offset of years
 *
 * @param int $offset the difference between the current year
 */
	public function currentYear($offset = 0) {
		return date('Y', strtotime("+$offset year"));
	}

/**
 * currentWeek function.
 * Return the current week ± offset of years
 *
 * @param int $offset the difference between the current week
 */
	public function currentWeek($offset = 0) {
		return date('W', strtotime("+$offset week"));
	}

/**
 * dayOfWeek function.
 * Return the day of the week based on a year, week and day number
 *
 * @param mixed $year the year
 * @param mixed $week the week
 * @param mixed $day the day
 */
	public function dayOfWeek($year, $week, $day) {
		$this->__currentDate->setISODate($year, $week, $day);
		return $this->__currentDate->format('Y-m-d');
	}

/**
 * @OVERRIDE
 * @throws
 */
	public function fetchHistory($project = '', $number = 10, $offset = 0, $user = -1, $query = array()) {
		$events = $this->Project->ProjectHistory->fetchHistory($project, $number, $offset, $user, 'time');
		return $events;
	}

	public function fetchWeeklySummary($projectId = null, $year = null, $week = null, $userId = null) {
		$projectId = ($projectId == null) ? $this->project->id : $projectId;

		if ($projectId == null) {
			throw new InvalidArgumentException("Could not fetch times for unknown project");
		}

		if ($year === null) {
			$year = date('Y');
		}

		if ($week === null) {
			$week = date('W');
		}

		// Convert to date range
		$startDate = new DateTime(null, new DateTimeZone('UTC'));
		$startDate->setISODate($year, $week, 1);
		$startDate->setTime(0, 0, 0);
		$endDate = new DateTime(null, new DateTimeZone('UTC'));
		$endDate->setISODate($year, $week, 7);
		$endDate->setTime(0, 0, 0);

		$conditions = array(
			'Project.id' => $projectId,
			'Time.date >=' => $startDate->format('Y-m-d'),
			'Time.date <=' => $endDate->format('Y-m-d'),
		);

		if (isset($userId) && $userId != null) {
			$conditions['Time.user_id'] = $userId;
		}

		$weekTimes = $this->find('all', array(
			'fields' => array(
				'Task.id', 'Task.subject',
				'User.id', 'User.name', 'User.email',
				'Time.id', 'Time.date', 'Time.description', 'Time.mins' //'SUM(Time.mins) as total_mins'
			),
			'conditions' => $conditions,
			'order' => array('Task.subject', 'User.name', 'Time.date')
		));

		$summary = array('totals' => array(), 'tasks' => array(), 'dates' => array());
		$lastUid = 0;
		$lastTid = -1;
		$lastDate = null;
		$totals = array();

		for ($i = 1; $i <= 7; $i++) {
			$summary['totals'][$i] = 0;
			$day = clone $startDate;
			$day->add(new DateInterval('P' . ($i - 1) . 'D'));
			$summary['dates'][$i] = $day;

		}
		foreach ($weekTimes as $time) {

			// Make the variables a bit more managable...
			$task = $time['Task'];
			$user = $time['User'];
			$time = $time['Time'];
			$minutes = $time['mins'];
			$date = strtotime($time['date']);

			// Convert null task ID to 0
			$task['id'] = isset($task['id']) ? $task['id'] : 0;

			// ISO day of week, 1=Monday 7=Sunday
			$dow = date('N', $date);

			// Build Horrible Array of Doom... start ordered by task
			if ($lastTid != $task['id']) {
				$lastUid = 0;
				$summary['tasks'][ $task['id'] ] = array(
					'Task' => $task,
					'users' => array()
				);
			}
			$summary_task = $summary['tasks'][ $task['id'] ];

			// Now for each task, add a list of users; each user then has a breakdown of tasks by day
			if ($lastUid != $user['id']) {
				$summary_task['users'][ $user['id'] ] = array(
					'User' => $user,
					'times_by_day' => array()
				);
			}
			$summary_user = $summary_task['users'][ $user['id'] ];

			// Add time to the list for that day
			if (!isset($summary_user['times_by_day'][$dow])) {
				$summary_user['times_by_day'][$dow] = array();
			}
			$summary_user['times_by_day'][$dow][] = array('Time' => $time);

			// Store everything back in the Array of Doom
			$summary_task['users'][ $user['id'] ] = $summary_user;
			$summary['tasks'][ $task['id'] ] = $summary_task;


			// Keep a convenient 'total minutes for each day' entry for rendering
			$summary['totals'][$dow] += $minutes;

			// Track where we are in the list
			$lastUid = $user['id'];
			$lastTid = $task['id'];
			$lastDate = $date;
		}

		return $summary;
	}

/**
 * fetchTotalTimeForProject function.
 * Fetch all of the allocated time for a project
 *
 * @param mixed $projectId the project to check
 * @throws InvalidArgumentException
 */
	public function fetchTotalTimeForProject($projectId = null) {
		$projectId = ($projectId == null ? $this->Project->id : $projectId);

		if ($projectId == null) {
			throw new InvalidArgumentException(__("Could not fetch times for unknown project"));
		}

		$totalLoggedTime = $this->find('all', array(
			'conditions' => array('Time.project_id' => $projectId),
			'fields' => array('SUM(Time.mins) AS total_mins')
		));

		$totalLoggedTime = $totalLoggedTime[0][0]['total_mins'];

		try{
			$totalLoggedTime = TimeString::renderTime($totalLoggedTime);
		} catch (InvalidArgumentException $e) {
		}

		return $totalLoggedTime;
	}

/**
 * fetchUserTimesForProject function.
 * Fetch time for a project split among those who logged it
 *
 * @param mixed $projectId the project to check
 * @throws InvalidArgumentException
 */
	public function fetchUserTimesForProject($projectId = null) {
		$projectId = ($projectId == null) ? $this->Project->id : $projectId;

		if ($projectId == null) {
			throw new InvalidArgumentException("Could not fetch times for unknown project");
		}

		$userTimes = $this->find('all', array(
			'conditions' => array('Time.project_id' => $projectId),
			'group' => array('Time.user_id'),
			'fields' => array(
				'User.id',
				'User.name',
				'User.email',
				'SUM(Time.mins) AS total_mins'
			)
		));

		foreach ($userTimes as $key => $value) {
			$userTimes[$key]['Time']['time'] = TimeString::renderTime($value[0]["total_mins"]);
		}

		return $userTimes;
	}

/**
 * getMaxAllowedYear function.
 * returns the maximum allowed year for this model
 */
	public function getMaxAllowedYear() {
		return $this->__maximumAllowedYear;
	}

/**
 * getMinAllowedYear function.
 * returns the minimum allowed year for this model
 */
	public function getMinAllowedYear() {
		return $this->__minimumAllowedYear;
	}

/**
 * @OVERRIDE
 */
	public function getTitleForHistory($id) {
		$this->id = $id;
		if (!$this->exists()) {
			return null;
		} else {
			$mins = TimeString::renderTime($this->field('mins'));
			return $mins['s'];
		}
	}

/**
 * lastWeekOfYear function.
 * Find the last week of the year
 *
 * @param mixed $year The year to check
 */
	public function lastWeekOfYear($year = null) {
		$this->__currentDate->setISODate($year, 53);
		return ($this->__currentDate->format("W") === "53" ? '53' : '52');
	}

/**
 * setMaxAllowedYear function.
 * Sets the maximum allowed year for this model
 *
 * @param mixed $maxYear The new maxYear
 */
	public function setMaxAllowedYear($maxYear) {
		$this->__maximumAllowedYear = $maxYear;
	}

/**
 * setMinAllowedYear function.
 * Sets the minimum allowed year for this model
 *
 * @param mixed $minYear The new minYear
 */
	public function setMinAllowedYear($minYear) {
		$this->__minimumAllowedYear = $minYear;
	}

/**
 * startOfWeek function.
 * The start day of a week.
 *
 * @param mixed $year the year
 * @param mixed $week the week
 */
	public function startOfWeek($year, $week) {
		return $this->dayOfWeek($year, $week, 0);
	}

/**
 * tasksForWeek function.
 * Return a list of tasks worked on this week
 *
 * @param mixed $year the year
 * @param mixed $week the week
 * TODO unused?
 */
	public function tasksForWeek($year, $week, $current_user_only = true) {
		$conditions = array(
			'Time.date BETWEEN ? AND ?' => array(
				$this->startOfWeek($year, $week),
				$this->startOfWeek($year, $week + 1)
			),
			'Time.project_id' => $this->Project->id
		);
		if ($current_user_only) {
			$conditions['Time.user_id'] = User::get('id');
		}
		$tasksForWeek = $this->find(
			'list',
			array(
				'fields' => array('Time.task_id'),
				'group' => array('Time.task_id'),
				'conditions' => $conditions
			)
		);
		return array_values($tasksForWeek);
	}

/**
 * timesForWeek function.
 * Fetch the time logged in a week
 *
 * @param mixed $year the year
 * @param mixed $week the week
 * TODO unused?
 */
	public function timesForWeek($year, $week, $current_user_only = true) {
		$this->recursive = -1;
		$weekEvents = array();
		$dateToday = date('Y-m-d');

		// Iterate over our week
		for ($day = 1; $day <= 7; $day++) {

			// Real date for the day
			$today = $this->dayOfWeek($year, $week, $day);

			$weekEvents[$day] = array(
				'date' => $today,
				'times' => array(),
				'today' => ($today == $dateToday) ? true : false,
				'totalTimes' => array(),
				'totalTime' => 0
			);

			$conditions = array(
				'Time.date' => $today,
				'Time.project_id' => $this->Project->id,
			);
			if ($current_user_only) {
				$conditions['Time.user_id'] = User::get('id');
			}

			$todaysTimes = $this->find(
				'all', array(
				'conditions' => $conditions
			));

			foreach ($todaysTimes as $time) {
				if (!$time['Time']['task_id']) {
					$time['Time']['task_id'] = 0;
				}
				if (!isset($weekEvents[$day]['times'][$time['Time']['task_id']])) {
					$weekEvents[$day]['times'][$time['Time']['task_id']] = array();
					$weekEvents[$day]['totalTimes'][$time['Time']['task_id']] = 0;
				}
				$weekEvents[$day]['times'][$time['Time']['task_id']][] = $time;
				$weekEvents[$day]['totalTimes'][$time['Time']['task_id']] += $time['Time']['mins'];
				$weekEvents[$day]['totalTime'] += $time['Time']['mins'];
			}

			// Change the total to a useful format
			foreach ($weekEvents[$day]['totalTimes'] as $a => $b) {
				$b = TimeString::renderTime($b);
				$weekEvents[$day]['totalTimes'][$a] = $b['h'] + round($b['m'] / 60, 1);
			}
			$totalTime = TimeString::renderTime($weekEvents[$day]['totalTime']);
			$weekEvents[$day]['totalTime'] = $totalTime['h'] + round($totalTime['m'] / 60, 1);

			$weekEvents[$this->__currentDate->format('D')] = $weekEvents[$day];

			unset($weekEvents[$day]);
		}

		return $weekEvents;
	}

/**
 * toString function.
 *
 * @param mixed $id the optional id of the time element
 * @throws InvalidArgumentException
 */
	public function toString($id = null) {
		$id = ($id == null) ? $this->id : $id;

		if ($id == null) {
			throw new InvalidArgumentException("Could not print undefined time element");
		}

		$this->recursive = -1;
		$time = $this->findById($id);
		if (!array_key_exists('Time', $time)) {
			throw new InvalidArgumentException("Could not find time with ID '$id'");
		}
		return $time['Time']['minutes']['s'];
	}

/**
 * validateYear function.
 * Ensure a year is within our bounds
 *
 * @param mixed $year the year to validate
 * @throws InvalidArgumentException
 */
	public function validateYear($year = null) {
		if ($year == null) {
			return $this->currentYear();
		}
		if (!is_numeric($year) || $year < $this->__minimumAllowedYear || $year > $this->__maximumAllowedYear) {
			throw new InvalidArgumentException("Invalid Year (allowed 2010 - {$this->__maximumAllowedYear})");
		}
		return $year;
	}

/**
 * validateWeek function.
 * Ensure the week is a valid week
 *
 * @param mixed $week the week
 * @param mixed $year the year
 * @throws InvalidArgumentException
 */
	public function validateWeek($week = null, $year = null) {
		if ($week === null) {
			return $this->currentWeek();
		}
		if (!is_numeric($week) || $week < 1 || $week > $this->lastWeekOfYear($year)) {
			throw new InvalidArgumentException('Invalid Week of the Year');
		}
		return $week;
	}
}
