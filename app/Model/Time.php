<?php
/**
*
* Time model for the DevTrack system
* Represents a time segment
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright     DevTrack Development Team 2012
* @link          http://github.com/chrisbulmer/devtrack
* @package       DevTrack.Model
* @since         DevTrack v 0.1
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

App::uses('AppModel', 'Model');

class Time extends AppModel {

    private $Date;
    private $minYear;
    private $maxYear;

    public function __construct(){
        parent::__construct();

        $this->Date = new DateTime;

        $this->minYear = 2010;
        $this->maxYear = $this->currentYear(1);
    }

    /**
    * Display field
    *
    * @var string
    */
    public $displayField = 'id';

    public $actsAs = array(
        'ProjectComponent',
        'ProjectHistory'
    );

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'project_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'A valid project id was not entered',
            ),
        ),
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'A valid user id was not entered',
            ),
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
        'date' => array(
            'rule' => array('date','ymd'),
            'message' => 'Enter a valid date in YYYY-MM-DD format.',
        ),
    );

    /**
    * belongsTo associations
    *
    * @var array
    */
    public $belongsTo = array(
        'Project' => array(
            'className' => 'Project',
            'foreignKey' => 'project_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * getMaxAllowedYear function.
     * returns the maximum allowed year for this model
     *
     * @access public
     * @return void
     */
    public function getMaxAllowedYear() {
        return $this->maxYear;
    }

    /**
     * getMinAllowedYear function.
     * returns the minimum allowed year for this model
     *
     * @access public
     * @return void
     */
    public function getMinAllowedYear() {
        return $this->minYear;
    }

    /**
     * setMaxAllowedYear function.
     * Sets the maximum allowed year for this model
     *
     * @access public
     * @param mixed $maxYear
     * @return void
     */
    public function setMaxAllowedYear($maxYear) {
        $this->maxYear = $maxYear;
    }

    /**
     * setMinAllowedYear function.
     * Sets the minimum allowed year for this model
     *
     * @access public
     * @param mixed $minYear
     * @return void
     */
    public function setMinAllowedYear($minYear) {
        $this->minYear = $minYear;
    }

    public function afterFind($results, $primary = false) {
        foreach ($results as $a => $result) {
            if (isset($result['Time']['mins'])) {
                $results[$a]['Time']['mins'] = $this->splitMins($result['Time']['mins']);
            }
        }
        return $results;
    }

    public function splitMins($in) {
        $hours = 0;
        $mins = (int) $in;

        while ($mins >= 60) {
            $hours += 1;
            $mins -= 60;
        }

        return array('h' => $hours, 'm' => $mins, 's' => "${hours}h ${mins}m", 't' => (int) $in);
    }

    /**
     * beforeValidate
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

        $time = (int) 0;
        $time += ((isset($hours['hours'])) ? 60*(int)$hours['hours'] : 0);
        $time += ((isset($mins['mins'])) ? (int)$mins['mins'] : 0);

        $this->data['Time']['mins'] = $time;

        return true;
    }

    /**
     * @OVERRIDE
     *
     * fetchHistory function.
     *
     * @access public
     * @param string $project (default: '')
     * @param int $number (default: 10)
     * @param int $offset (default: 0)
     * @param float $user (default: -1)
     * @param array $query (default: array())
     * @return void
     */
    public function fetchHistory($project = '', $number = 10, $offset = 0, $user = -1, $query = array()) {
        $events = $this->Project->ProjectHistory->fetchHistory($project, $number, $offset, $user, 'time');
        return $events;
    }

    /**
     * @OVERRIDE
     *
     * getTitleForHistory function.
     *
     * @access public
     * @param mixed $id
     * @return void
     */
    public function getTitleForHistory($id) {
        $this->id = $id;
        if (!$this->exists()) {
            return null;
        } else {
            $mins = $this->field('mins');
            return $mins['s'];
        }
    }

    /**
     * currentYear function.
     *
     * @access public
     * @param int $offset (default: 0)
     * @return void
     */
    public function currentYear($offset = 0) {
        return date('Y', strtotime("+$offset year"));
    }

    /**
     * validateYear function.
     *
     * @access public
     * @param mixed $year (default: null)
     * @return void
     */
    public function validateYear($year = null) {
        if ($year == null) {
            return $this->currentYear();
        }
        if (!is_numeric($year) || $year < $this->minYear || $year > $this->maxYear) {
            throw new NotFoundException(__("Invalid Year (allowed 2010 - {$this->maxYear})"));
        }
        return $year;
    }

    /**
     * currentWeek function.
     *
     * @access public
     * @param int $offset (default: 0)
     * @return void
     */
    public function currentWeek($offset = 0) {
        return date('W', strtotime("+$offset week"));
    }

    /**
     * validateWeek function.
     *
     * @access public
     * @param mixed $week (default: null)
     * @return void
     */
    public function validateWeek($week = null, $year = null) {
        if ($week == null) {
            return $this->currentWeek();
        }
        if (!is_numeric($week) || $week < 1 || $week > $this->lastWeekOfYear($year)) {
            throw new NotFoundException(__('Invalid Week of the Year'));
        }
        return $week;
    }

    /**
     * lastWeekOfYear function.
     *
     * @access public
     * @param mixed $year (default: null)
     * @return string last week of year
     */
    public function lastWeekOfYear($year = null) {
        $this->Date->setISODate($year, 53);
        return ($this->Date->format("W") === "53" ? '53' : '52');
    }

    /**
     * startOfWeek function.
     *
     * @access public
     * @param mixed $year
     * @param mixed $week
     * @return void
     */
    public function startOfWeek($year, $week) {
        return $this->dayOfWeek($year, $week, 0);
    }

    /**
     * dayOfWeek function.
     *
     * @access public
     * @param mixed $year
     * @param mixed $week
     * @param mixed $day
     * @return void
     */
    public function dayOfWeek($year, $week, $day) {
        $this->Date->setISODate($year, $week, $day);
        return $this->Date->format('Y-m-d');
    }

// REMOVE
    public function tasksForWeek($year, $week) {
        return array_values($this->find(
            'list',
            array(
                'fields'     => array('Time.task_id'),
                'group'      => array('Time.task_id'),
                'conditions' => array(
                    'Time.date BETWEEN ? AND ?' => array(
                        $this->startOfWeek($year, $week),
                        $this->startOfWeek($year, $week +1)
                    ),
                    'Time.project_id' => $this->Project->id,
                    'Time.user_id'    => $this->_auth_user_id
                )
            )
        ));
    }

    public function timesForWeek($year, $week) {
        $this->recursive = -1;
        $weekEvents = array();
        $dateToday = date('Y-m-d');

        // Iterate over our week
        for($day = 1; $day <= 7; $day++) {

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
                    'Time.date'      => $today,
                    'Time.user_id'   => $this->_auth_user_id,
                    'Time.project_id'=> $this->Project->id,
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
                $weekEvents[$day]['totalTimes'][$time['Time']['task_id']] += $time['Time']['mins']['t'];
                $weekEvents[$day]['totalTime'] += $time['Time']['mins']['t'];
            }

            // Change the total to a useful format
            foreach ($weekEvents[$day]['totalTimes'] as $a => $b) {
                $b = $this->splitMins($b);
                $weekEvents[$day]['totalTimes'][$a] = $b['h'] + round($b['m']/60, 1);
            }
            $totalTime = $this->splitMins($weekEvents[$day]['totalTime']);
            $weekEvents[$day]['totalTime'] = $totalTime['h'] + round($totalTime['m']/60, 1);

            $weekEvents[$this->Date->format('D')] = $weekEvents[$day];

            unset($weekEvents[$day]);
        }

        return $weekEvents;
    }
}
