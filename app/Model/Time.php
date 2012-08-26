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

App::uses('AppProjectModel', 'Model');

class Time extends AppProjectModel {

    /**
    * Display field
    *
    * @var string
    */
    public $displayField = 'id';

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
        foreach ($events as $x => $event) {
            $events[$x]['Subject']['title'] = 'allocated time';
        }
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
}
