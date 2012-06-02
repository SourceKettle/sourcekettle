<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
*
* AppController for the DevTrack system
* The application wide controller.
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
* 
* @copyright     DevTrack Development Team 2012
* @link          http://github.com/chrisbulmer/devtrack
* @package       DevTrack.Controller
* @since         DevTrack v 0.1
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
class AppController extends Controller {
    
    /**
     * The global helpers
     * @var type 
     */
    public $helpers = array('Html', 'Session', 'Bootstrap' => array('className' => 'TwitterBootstrap.TwitterBootstrap'), 'ActiveNav');
    
    /**
     * Global components used for authentication, authorisation and session management.
     * @var type 
     */
    public $components = array(
        'Acl', 
        'Session', 
        'Auth' => array(
            'authorize' => 'actions',
            'actionPath' => 'controllers/',
            'loginAction' => array(
                'controller' => 'login',
                'action' => 'index',
                'plugin' => false,
                'admin' => false,
            )
        ));
    
    
    /**
     * Before filter method acts first in the controller
     * 
     * Configures the auth component to use the email column as the user name
     */
    public function beforeFilter() {
        parent::beforeFilter();
        
        $this->Auth->fields = array(
            'username' => 'email',
            'password' => 'password'
        );
        //Customise the login error
        $this->Auth->loginError = 'The credentials you entered were incorrect. Please try again or have you<a href="lost_password">lost your password"</a>';
        
        //Customise thge auth error (when they try to access a protected part of the site)
        $this->Auth->authError = 'You need to login to view that page';
        
        //Use sha256 as the hashing algorithm for the site as it is the most secure out of the allowed options.
        Security::setHash('sha256');
        
        if($this->Auth->loggedIn()){
            $this->set('user_name', $this->Auth->user('name'));
        }
    }
}
