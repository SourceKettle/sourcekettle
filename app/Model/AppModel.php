<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

    // The logged in user
    public $_auth_user_id;
    public $_auth_user_name;
    public $_auth_user_email;

    /**
     * setCurrentUserData function.
     * Recursively traverse the MODELS and set the current logged in users data
     * This should hopefully mean we can have fatter models
     *
     * @access public
     * @param mixed $id (default: null)
     * @param mixed $name (default: null)
     * @param mixed $email (default: null)
     * @return void
     */
    public function setCurrentUserData($id = null, $name = null, $email = null) {
        if ($this->_auth_user_id) return true;

        $this->_auth_user_id = $id;
        $this->_auth_user_name = $name;
        $this->_auth_user_email = $email;

        foreach (array_keys($this->hasMany) as $key) {
            $this->{$key}->setCurrentUserData($id, $name, $email);
        }
        foreach (array_keys($this->belongsTo) as $key) {
            $this->{$key}->setCurrentUserData($id, $name, $email);
        }
        foreach (array_keys($this->hasAndBelongsToMany) as $key) {
            $this->{$key}->setCurrentUserData($id, $name, $email);
        }
    }
}
