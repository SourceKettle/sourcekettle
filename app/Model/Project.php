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
                'rule' => '/[0-9a-zA-Z_-]+$/',
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
        )
    );

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

}
