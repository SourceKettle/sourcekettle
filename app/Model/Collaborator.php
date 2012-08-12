<?php
/**
*
* Collaborator model for the DevTrack system
* Represents a project collaborator in the system
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
/**
 * Collaborator Model
 *
 * @property Project $Project
 * @property User $User
 */
class Collaborator extends AppModel {

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
        'access_level' => array(
            'inlist' => array(
                'rule' => array('inlist', array(0, 1, 2)),
                'message' => 'The user access level was not in the defined types',
            ),
        ),
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

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

        $collaborators = $this->find('all', $search);
        $events = array();

        // Collect time events
        foreach ( $collaborators as $a => $collaborator ) {
            // Store wanted details
            $events[$a] = array(
                'Type' => 'Collaborator',
                'Actioner' => array(
                    'name' => $collaborator['User']['name'],
                    'id' => $collaborator['User']['id'],
                    'email' => $collaborator['User']['email'],
                ),
                'Project' => array(
                    'id' => $collaborator['Project']['id'],
                    'name' => $collaborator['Project']['name']
                ),
                'url' => null,
                'modified' => $collaborator['Collaborator']['modified'],
                'detail' => $collaborator['Collaborator']['access_level'],
                'permissions' => array('view'),
            );
            // Calculate last access action
            if ($collaborator['Collaborator']['modified'] != $collaborator['Collaborator']['created']) {
                $events[$a]['action'] = 'updated';
            } else {
                $events[$a]['action'] = 'created';
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
