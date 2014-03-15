<?php
/**
 *
 * Time model for the CodeKettle system
 * Represents a time segment
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/CodeKettle
 * @package       CodeKettle.Model
 * @since         CodeKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppModel', 'Model');
App::uses('InvalidArgumentException', 'Exception');

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
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	public function __construct() {
		parent::__construct();

		// Set the current date
		$this->__currentDate = new DateTime();
		$this->__maximumAllowedYear = $this->currentYear(1);
	}

/**
 * See: http://book.cakephp.org/2.0/en/models/callback-methods.html
 * Pre-format the mins to prevent additional formatting later
 */
	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val['Time']['mins'])) {
				$results[$key]['Time']['minutes'] = $this->splitMins($val['Time']['mins']);
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

		$string = $this->data['Time']['mins'];

		if (is_int($string)) {
			return true;
		}

		preg_match("#(?P<hours>[0-9]+)\s?h(rs?|ours?)?#", $string, $hours);
		preg_match("#(?P<mins>[0-9]+)\s?m(ins?)?#", $string, $mins);

		$time = (int)0;
		$time += ((isset($hours['hours'])) ? 60 * (int)$hours['hours'] : 0);
		$time += ((isset($mins['mins'])) ? (int)$mins['mins'] : 0);

		$this->data['Time']['mins'] = $time;

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
 */
	public function fetchHistory($project = '', $number = 10, $offset = 0, $user = -1, $query = array()) {
		$events = $this->Project->ProjectHistory->fetchHistory($project, $number, $offset, $user, 'time');
		return $events;
	}

/**
 * fetchTotalTimeForProject function.
 * Fetch all of the allocated time for a project
 *
 * @param mixed $projectId the project to check
 * @throws InvalidArgumentException
 */
	public function fetchTotalTimeForProject($projectId = null) {
		$projectId = ($projectId == null) ? $this->Project->id : $projectId;

		if ($projectId == null) {
			throw new InvalidArgumentException("Could not fetch times for unknown project");
		}

		$totalLoggedTime = $this->find('all', array(
			'conditions' => array('Time.project_id' => $projectId),
			'fields' => array('SUM(Time.mins)')
		));

		$totalLoggedTime = $totalLoggedTime[0][0]['SUM(`Time`.`mins`)'];

		try{
			$totalLoggedTime = $this->splitMins($totalLoggedTime);
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
				'SUM(Time.mins)'
			)
		));

		foreach ($userTimes as $key => $value) {
			$userTimes[$key]['Time']['time'] = $this->splitMins($value[0]["SUM(`Time`.`mins`)"]);
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
			$mins = $this->splitMins($this->field('mins'));
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
 * splitMins function.
 * Take a number of minutes and convert it to D-H-M
 *  TODO make this a library function
 *
 * @param mixed $in the number of minutes
 * @throws InvalidArgumentException
 */
	public function splitMins($in) {
		if (!is_numeric($in)) {
			throw new InvalidArgumentException("Minutes must be an integer: ${in} given");
		}

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

		$output = array(
			'd' => $days,
			'h' => $hours,
			'm' => $mins,
			't' => (int)$in, //legacy TODO: remove
			's' => "${hours}h ${mins}m",
		);

		if ($days > 0) {
			$output['s'] = "${days}d ${hours}h ${mins}m";
		}

		return $output;
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
 */
	public function tasksForWeek($year, $week) {
		$tasksForWeek = $this->find(
			'list',
			array(
				'fields' => array('Time.task_id'),
				'group' => array('Time.task_id'),
				'conditions' => array(
					'Time.date BETWEEN ? AND ?' => array(
						$this->startOfWeek($year, $week),
						$this->startOfWeek($year, $week + 1)
					),
					'Time.project_id' => $this->Project->id,
					'Time.user_id' => User::get('id')
				)
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
 */
	public function timesForWeek($year, $week) {
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

			$todaysTimes = $this->find(
				'all', array(
				'conditions' => array(
					'Time.date' => $today,
					'Time.user_id' => User::get('id'),
					'Time.project_id' => $this->Project->id,
				)
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
				$b = $this->splitMins($b);
				$weekEvents[$day]['totalTimes'][$a] = $b['h'] + round($b['m'] / 60, 1);
			}
			$totalTime = $this->splitMins($weekEvents[$day]['totalTime']);
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
		if ($week == null) {
			return $this->currentWeek();
		}
		if (!is_numeric($week) || $week < 1 || $week > $this->lastWeekOfYear($year)) {
			throw new InvalidArgumentException('Invalid Week of the Year');
		}
		return $week;
	}
}
