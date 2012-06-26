<?php
/**
*
* Source model for the DevTrack system
* Represents a source in the system
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
 * Source Model
 *
 * @property Project $Project
 */
class Source extends AppModel {

    public $useTable = 'source';

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
                'message' => 'Please select a project',
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
        )
    );

    public function RepoLocationOnFileSystem($name = null) {
        $devtrack_config = Configure::read('devtrack');
        $base = $devtrack_config['repo']['base'];
        if ($base[strlen($base)-1] != '/') {
            $base .= '/';
        }

        // Check for existant project
        $project = $this->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        return $base . $project['Project']['name'] . '.git/';
    }

}
