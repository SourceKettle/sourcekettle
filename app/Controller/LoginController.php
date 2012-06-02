<?php

/**
*
* LoginController for the DevTrack system
* The controller to allow users to login and logout
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
class LoginController extends AppController{
    /**
     * The name of the controller
     * @var type String
     */
    public $name = 'login';
    
    /**
     * Uses the Users model
     * @var type 
     */
    public $uses = array('Users', 'Setting');
    
    /**
     * Allows the view to use the Form helper
     * @var type 
     */
    public $helpers = array('Form');
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('register'); //Allow register to be outside the auth zone
    }
    
    /**
     * Displays the login page
     */
    public function index(){
        
    }
    
    /**
     * The login function.
     * 
     * Allows users to login using their username and password.
     */
    public function login(){
        
    }
    
    /**
     * The logout function.
     * 
     * Allows users to logout of DevTrack.
     */
    public function logout(){
        
    }
    
    /**
     * Function to allow users to register with the application
     */
    public function register(){
        //Check if registration is allowed by the user
        $enabled = $this->Setting->find('first', array('conditions' => array('name' => 'register_enabled')));
        if ($enabled['Setting']['value']){ //Check the setting
            //Registration part
        } else {
            //Display an error saying that registration is not allowed
            $this->render('registration_disabled');
        }
    }
}

?>
