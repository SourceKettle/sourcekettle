<?php
/**
*
* Project model for the DevTrack system
* Represents a project in the system
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
 * Project Model
 *
 * @property RepoType $RepoType
 * @property Collaborator $Collaborator
 */
class Project extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Please enter a name for the project',
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'This project name has already been taken',
            ),
            'minLength' => array(
                'rule' => array('minLength', 4),
                'message' => 'Project names must be at least 4 characters long',
            ),
            'alphaNumericDashUnderscore' => array(
                'rule' => '/^[0-9a-zA-Z_-]+$/',
                'message' => 'May contain only letters, numbers, dashes and underscores',
            ),
            'startWithALetter' => array(
                'rule' => '/^[a-zA-Z].+$/',
                'message' => 'Project names must start with a letter',
            ),
        ),
        'public' => array(
            'boolean' => array(
                'rule' => array('boolean'),
                'message' => 'Please select the visibility of the project',
            ),
        ),
        'repo_type' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Please select a repository type',
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
        'RepoType' => array(
            'className' => 'RepoType',
            'foreignKey' => 'repo_type',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Collaborator' => array(
            'className' => 'Collaborator',
            'foreignKey' => 'project_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Source' => array(
            'className' => 'Source',
            'foreignKey' => 'project_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

    public function beforeSave($options) {
        if (!empty($this->data['Project']['name'])) {
            $this->data['Project']['name'] = strtolower($this->data['Project']['name']);
        }
        return true;
    }

    /**
     * Fetches a project from either its name or its id
     *
     * @param $key string id or name of project to fetch
     *
     * @return Project The project found by the given key, null if no project is found
     */
    public function getProject($key) {
        if ($key == null){ //Sanity check
            return null;
        }

        $project = null;
        if (is_numeric($key)) {
            $project = $this->find('first', array('conditions' => array('Project.id' => $key)));
        } else {
            $project = $this->find('first', array('conditions' => array('Project.name' => $key)));
        }
        if (empty($project)){
            $project = null;
        }
        return $project;
    }

    /**
     * Checks to see if a user is an admin of this project
     *
     * @param $user int id of the user to check
     * @return boolean true if admin
     */
    public function isAdmin($user = null) {
        if ( $user == null ) return false;

        $admins = $this->Collaborator->find('first', array('conditions' => array('Collaborator.user_id' => $user, 'Collaborator.project_id' => $this->id), 'fields' => array('Collaborator.access_level')));
        if ( $admins['Collaborator']['access_level'] == 2 ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks to see if a user is a member of this project
     *
     * @param $user int id of the user to check
     * @return boolean true if member
     */
    public function isMember($user = null) {
        if ( $user == null ) return false;

        $members = $this->Collaborator->find('first', array('conditions' => array('Collaborator.user_id' => $user, 'Collaborator.project_id' => $this->id), 'fields' => array('Collaborator.access_level')));
        if ( !empty($members) ) {
            return true;
        } else {
            return false;
        }
    }

    public function fetchEventsForProject() {
        $this->recursive = 2;
        $project = $this->getProject($this->id);

        $events = array();

        // Collect collaborator events
        foreach ( $project['Collaborator'] as $a ) {
            array_push($events, array(
                'Type' => 'Collaborator',
                'user_name' => $a['User']['name'],
                'project_name' => $project['Project']['name'],
                'user_id' => $a['User']['id'],
                'project_id' => $project['Project']['id'],
                'modified' => $a['modified'],
            ));
        }

        // Sort function for events
        // assumes $array{ $array{ 'modified' => 'date' }, ... }
        $cmp = function($a, $b) {
            if ($a['modified'] == $b['modified']) return 0;
            if (strtotime($a['modified']) < strtotime($b['modified'])) return 1;
            return -1;
        };
        usort($events, $cmp);

        return $events;
    }

}
