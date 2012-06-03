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
    public $uses = array('User', 'Setting');
    
    /**
     * Allows the view to use the Form helper
     * @var type 
     */
    public $helpers = array('Form');
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('register', 'lost_password', 'login'); //Allow register to be outside the auth zone
        
        // Alias the username field to be email instead
        $this->Auth->fields = array(
            'username' => 'email',
            'password' => 'password'
        );
    }
    
    /**
     * The login function.
     * 
     * Allows users to login using their username and password.
     */
    public function index(){
        if ($this->request->is('post')) {
            //$this->Auth->password($this->request->data['User']['password']);
            if ($this->Auth->login()) {
                $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash(__("<h4 class='alert-heading'>Error</h4>The credentials supplied were not valid. Please try again."), 'default', array(), 'error');
            }
        }
    }
    
    /**
     * The logout function.
     * 
     * Allows users to logout of DevTrack.
     */
    public function logout(){
        $this->redirect($this->Auth->logout());
    }
    
    /**
     * Function to allow users to register with the application
     */
    public function register(){
        $this->set('title_for_layout', 'Register');
        //Check if registration is allowed by the user
        $enabled = $this->Setting->find('first', array('conditions' => array('name' => 'register_enabled')));
        if ($enabled['Setting']['value']){ //Check the setting
            //Registration part
            if($this->request->is('post')){
                //if data was posted therefore a submitted form
                if ($this->data['User']['password'] == $this->data['User']['password_confirm']) {
                    $this->User->create();
                    $this->User->save($this->request->data);
                    $this->render('email_sent');
                } else {
                    $this->Session->setFlash(__("<h4 class='alert-heading'>Error</h4>The passwords do not match. Please try again."), 'default', array(), 'error');

                }
            }
        } else {
            //Display an error saying that registration is not allowed
            $this->render('registration_disabled');
        }
    }
    
    /**
     * Function to allow for users to reset their passwords
     */
    public function lost_password(){
        
    }
}

?>
