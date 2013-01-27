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
 * @link          http://github.com/SourceKettle/devtrack
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
        ),
        'ApiKey' => array(
            'className' => 'ApiKey',
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
        'LostPasswordKey' => array(
            'className' => 'LostPasswordKey',
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

    public function afterFind($results, $primary = false) {
        if ($this->_is_api) {
            foreach ($results as $x => $item) {
                // A list of things that should not be available in the API
                unset($results[$x]['User']['password']);
            }
        }
        return $results;
    }

    public function beforeSave($options = array()) {

        // Can't update the email or password field if it's an externally-managed account
        if ( !User::isDevtrackManaged($this->data) ) {
            $wl = $this->whitelist;
            if(empty($wl)){
                $wl = array_keys($this->schema());
            }
            $wl = array_diff($wl, array('password'));

            // Only blacklist the email if we're updating an existing account
            if( $this->id ) {
                $wl = array_diff($wl, array('email'));
            }
            $this->whitelist = $wl;
        }

        // Lowercase the email address for storage
        $this->data[$this->alias]['email'] = strtolower($this->data[$this->alias]['email']);

        if ( isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }


    public function beforeDelete($cascade = true) {

        // Check that the user account is DevTrack-managed, can't delete otherwise...
        if ( !User::isDevtrackManaged($this->data) ) {
            return false;
        }

        // Check to ensure that this user is not the only admin on multi-collaborator projects
        $projects = $this->Collaborator->find('list', array('fields' => array('Collaborator.project_id'), 'conditions' => array('Collaborator.user_id' => $this->id)));
        foreach ( $projects as $row => $project_id ) {
            $admins = $this->Collaborator->find('count', array('conditions' => array('Collaborator.project_id' => $project_id, 'Collaborator.access_level' => '2', 'Collaborator.user_id <>' => $this->id)));
            if ( $admins == 0 ) {
                $users = $this->Collaborator->find('count', array('conditions' => array('Collaborator.project_id' => $project_id, 'Collaborator.access_level <>' => '2', 'Collaborator.user_id <>' => $this->id)));
                if ( $users > 0 ) {
                    return false;
                }
            }
        }

        // Delete all the projects that the user is the only collaborator on
        foreach ( $projects as $row => $project_id ) {
            $users = $this->Collaborator->find('count', array('conditions' => array('Collaborator.project_id' => $project_id)));
            if ( $users == 1 ) {
                $this->Collaborator->Project->delete($project_id);
                $this->log("[UsersModel.beforeDelete] project[".$project_id."] deleted as user[".$this->id."] is being deleted", 'devtrack');
            }
        }
        return true;
    }

    // Is the user a DevTrack-managed account, i.e. password is stored in the database?
    // If it's been auto-created from e.g. LDAP, the password will be blank.
    public static function isDevtrackManaged($data) {
        return (
          isset($data['User']['password'])
          && !empty($data['User']['password'])
        );
    }

    // Case-insensitive findByEmail
    public function findByEmail($email, $fields = null, $order = null) {
        return $this->find('first', array(
          'conditions' => array(
            'LOWER(email)' => strtolower($email),
          ),
          'fields' => $fields,
          'order' => $order
        ));
    }

}
