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

        // If they're already logged in, bounce them to the homepage
        if($this->Auth->loggedIn()){
            $this->redirect($this->Auth->redirect());
            return; // Not sure if needed?
        }

        if ($this->request->is('post')) {
                
            // First, try to authenticate them
            if(!$this->Auth->login()){
                $this->log("[LoginController.index] Authentication failed using ".$this->request->data['User']['email'], 'devtrack');
                $this->Session->setFlash(__($this->Auth->loginError), 'default', array(), 'error');
                return;
            }

            $this->log("[LoginController.index] Authentication succeeded for ".$this->request->data['User']['email'], 'devtrack');

            // Authentication succeeded - load the user object they logged in with
            $user = $this->User->find('first', array('conditions' => array('email' => $this->request->data['User']['email']), 'recursive' => -1));
            $this->log("[LoginController.index] Looked up user - ID is ".$user['User']['id'], 'devtrack');


            // Check if the user has activated their account
            if (!$user['User']['is_active']){
                $this->log("[LoginController.index] user[".$this->Auth->user('id')."] denied access - account is not activated", 'devtrack');
                $this->Session->setFlash(__($this->Auth->loginError), 'default', array(), 'error');
                return;
            }

            // Authentication successful, everybody is happy! Let's log it to celebrate.
            $this->log("[LoginController.index] user[".$this->Auth->user('id')."] logged in", 'devtrack');

        }
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
