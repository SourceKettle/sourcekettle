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
     * fetchHistory function.
     * Lists the history of Time elements.
     *
     * @access public
     * @param string $project (default: '')
     * @param int $number (default: 10)
     * @param int $offset (default: 0)
     * @param float $user (default: -1)
     * @return array
     */
    public function fetchHistory($project = '', $number = 10, $offset = 0, $user = -1, $query = array()) {
        $search = array(
            'conditions' => array(),
            'limit' => $number+$offset,
            'offset' => $offset
        );

        // Decant query values in
        foreach ($search as $s => $v) {
            if (isset($query[$s])) {
                $search[$s] = $query[$s];

            }
        }

        if ($project != null && $project = $this->Project->getProject($project)) {
            $search['conditions']['Project.id'] = $project['Project']['id'];
        }

        $times = $this->find('all', $search);
        $events = array();

        // Collect time events
        foreach ( $times as $a => $time ) {
            // Store wanted details
            $events[$a] = array(
                'Type' => 'Time',
                'Actioner' => array(
                    'name' => $time['User']['name'],
                    'id' => $time['User']['id'],
                    'email' => $time['User']['email'],
                ),
                'Project' => array(
                    'id' => $time['Project']['id'],
                    'name' => $time['Project']['name']
                ),
                'url' => array(
                    'project' => $time['Project']['name'],
                    'controller' => 'times',
                    'action' => 'view',
                    $time['Time']['id']
                ),
                'modified' => $time['Time']['modified'],
                'detail' => $time['Time']['mins']['s'],
                'permissions' => array('view'),
            );
            // Calculate last access action
            if ($time['Time']['modified'] != $time['Time']['created']) {
                $events[$a]['action'] = 'updated';
            } else {
                $events[$a]['action'] = 'created';
            }
            // Calcuate access options
            if ($time['User']['id'] == $user) {
                $events[$a]['permissions'] = array('view', 'edit', 'delete');
            }
        }

        // Sort function for events
        // assumes $array{ $array{ 'modified' => 'date' }, ... }
        $cmp = function($a, $b) {
            if (strtotime($a['modified']) == strtotime($b['modified'])) return 0;
            if (strtotime($a['modified']) < strtotime($b['modified'])) return 1;
            return -1;
        };

        usort($events, $cmp);

        return $events;
    }
}
