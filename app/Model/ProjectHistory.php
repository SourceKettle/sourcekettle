<?php
/**
 *
 * ProjectHistory model for the DevTrack system
 * Stores the past for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.Model
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * @extends AppModel
 */

App::uses('AppModel', 'Model');

class ProjectHistory extends AppModel {

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
        'model' => array(
            'inlist' => array(
                'rule' => array('inlist', array('collaborator','task','milestone','source','time')),
            ),
        ),
        'model_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'A valid project id was not entered',
            ),
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
        )
    );

}
