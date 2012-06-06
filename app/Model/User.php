<?php
/**
 *
 * User model for the DevTrack system
 * Represents a user in the system
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
App::uses('AppModel', 'Model', 'AuthComponent', 'Controller/Component');

/**
 * User Model
 *
 * @property Collaborator $Collaborator
 * @property EmailConfirmationKey $EmailConfirmationKey
 * @property SshKey $SshKey
 */
class User extends AppModel {

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
                'message' => 'Please enter your name',
            ),
        ),
        'email' => array(
            'email' => array(
                'rule' => array('email'),
                'message' => 'Please enter your email',
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'An account already exists for this email address',
            ),
        ),
        'password' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Please enter a password',
            ),
            'minlength' => array(
                'rule' => array('minlength', 8),
                'message' => 'Your password must be at least 8 characters',
            ),
        ),
        'is_admin' => array(
            'boolean' => array(
                'rule' => array('boolean'),
            ),
        ),
        'is_active' => array(
            'boolean' => array(
                'rule' => array('boolean'),
            ),
        ),
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Collaborator' => array(
            'className' => 'Collaborator',
            'foreignKey' => 'user_id',
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
        'EmailConfirmationKey' => array(
            'className' => 'EmailConfirmationKey',
            'foreignKey' => 'user_id',
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
        'SshKey' => array(
            'className' => 'SshKey',
            'foreignKey' => 'user_id',
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

    public function beforeSave() {
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }

}