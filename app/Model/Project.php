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
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'public' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				'message' => 'Please select the visibility of the project',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'repo_type' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please select a repository type',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'wiki_enabled' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				'message' => 'Please enter a boolean value for wiki enabled',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'task_tracking_enabled' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				'message' => 'Please enter a boolean value for task tracking enabled',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'time_management_enabled' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				'message' => 'Please enter a boolean value for time management enabled',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
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
			'dependent' => false,
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
        //Being cheeky and loading a model into a component which CakePHP doesn't allow for some reason
        $this->Project = ClassRegistry::init("Project"); 
        
        $project = null;
        if (is_numeric($key)) {
            $project = $this->Project->find('first', array('conditions' => array('Project.id' => $key)));
        } else {    
            $project = $this->Project->find('first', array('conditions' => array('Project.name' => $key)));
        }
        if (empty($project)){
            $project = null;
        }
        return $project;
    }

}
