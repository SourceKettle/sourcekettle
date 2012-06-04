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
    public $name = 'Login';
    
    /**
     * Uses the Users model
     * @var type 
     */
    public $uses = array('User');
    
    /**
     * Allows the view to use the Form helper
     * @var type 
     */
    public $helpers = array('Form');
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    /**
     * The login function.
     * 
     * Allows users to login using their username and password.
     */
    public function index(){
        
        //if (!$this->Auth->loggedIn()){
            if ($this->request->is('post')) {
                
                //Get the user they are trying to log in as
                $user = $this->User->find('first', array('conditions' => array('email' => $this->request->data['User']['email']), 'recursive' => -1));
                
                //Check if the user has activated their account
                if ($user['User']['is_active']){
                    if ($this->Auth->login()) { //if login works
                        $this->redirect($this->Auth->redirect()); //send them to the dashboard
                    } else { //else error!
                        $this->Session->setFlash(__("<h4 class='alert-heading'>Error</h4>The credentials supplied were not valid. Please try again."), 'default', array(), 'error');
                    }
                } else { //else error (same error as above so not to disclose that it is a valid account)
                    $this->Session->setFlash(__("<h4 class='alert-heading'>Error</h4>The credentials supplied were not valid. Please try again."), 'default', array(), 'error');
                }
            }
        /*} else {
            //$this->redirect())
        }*/
    }
    
    /**
     * The logout function.
     * 
     * Allows users to logout of DevTrack.
     */
    public function logout(){
        $this->Auth->logout();
        $this->redirect('/');
    }
    
    
}

?>
