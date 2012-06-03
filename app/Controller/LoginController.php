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
App::uses('CakeEmail', 'Network/Email');
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
    public $uses = array('User', 'Setting', 'EmailConfirmationKey', 'SshKey');
    
    /**
     * Allows the view to use the Form helper
     * @var type 
     */
    public $helpers = array('Form');
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(); //Allow all to be outside the auth zone
    }
    
    /**
     * The login function.
     * 
     * Allows users to login using their username and password.
     */
    public function index(){
        if (!$this->Auth->loggedIn()){
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
        } else {
            //$this->redirect())
        }
    }
    
    /**
     * The logout function.
     * 
     * Allows users to logout of DevTrack.
     */
    public function logout(){
        $this->Auth->logout();
        
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
                    if ($this->data['User']['password'] != 'password'){
                        $this->User->create();
                        if ($this->User->save($this->request->data)){
                            $id = $this->User->getLastInsertID();
                            
                            //Check to see if an SSH key was added and save it
                            if (!empty($this->data['User']['ssh_key'])){
                                $this->SshKey->create();
                                $data = array('SshKey');
                                $data['SshKey']['user_id'] = $id;
                                $data['SshKey']['key'] = $this->request->data['User']['ssh_key'];
                                $data['SshKey']['comment'] = 'Default key';
                                $this->SshKey->save($data);
                            }
                            
                            //Now to create the key and send the email
                            
                            $key = $this->generate_key(20);
                            
                            $emailkey = $this->EmailConfirmationKey->create();
                            $data = array('EmailConfirmationKey');
                            $data['EmailConfirmationKey']['user_id'] = $id;
                            $data['EmailConfirmationKey']['key'] = $key;
                            $this->EmailConfirmationKey->save($data);
                            
                            $link = Router::url('/activate/' . $key, true);
                            
                            $message = "Dear " . $this->data['User']['name'] . " ,\n\nThank you for registering with DevTrack. In order to use your account, we require you to activate your account using the link below.\n\n" . $link . "\n\nWe hope you enjoy using DevTrack";

                            $email = new CakeEmail();
                            $email->config('default');
                            $email->to($this->data['User']['email']);
                            $email->subject('DevTrack activation');
                            $email->send($message);
                            echo $message; //TODO remove this line when emailing enabled
                            
                            $this->render('email_sent');
                        } else {
                            $this->Session->setFlash(__("<h4 class='alert-heading'>Error</h4>One or more fields were not filled in correctly. Please try again."), 'default', array(), 'error');
                        }
                    } else {
                        $this->Session->setFlash(__("<h4 class='alert-heading'>Error</h4>I see what you did there. '" . $this->data['User']['password'] . "' is not a good password. Try a different one."), 'default', array(), 'error');
                    }
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
     * Generates a random key of a given length
     * @param type $length The length of the key
     * @return string The random key 
     */
    private function generate_key($length){
        $key = ""; 
        $i = 0; 
        $possible = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";  
 
        while ($i < $length){ 
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1); 
             
            if (!strstr($key, $char)) {  
                $key .= $char; 
                $i++; 
            } 
        } 
        return $key;
    }
    
    /**
     * Function to allow for users to reset their passwords
     */
    public function lost_password(){
        
    }
    
    /**
     * Used to activate user accounts
     * @param type $key The key used to activate an account
     */
    public function activate($key){
        $record = $this->EmailConfirmationKey->find('first', array('conditions' => array('key' => $key), 'recursive' => 1));
        if(!empty($record)){
            $record['User']['is_active'] = 1;
            if ($this->User->save($record['User'])){
                $this->EmailConfirmationKey->delete($record['EmailConfirmationKey']['id'], false); //delete the email confirmation key
                $this->Session->setFlash(__("<h4 class='alert-heading'>Success</h4>Your account is now activated. You can now login."), 'default', array(), 'success');
                $this->redirect('/login');
            } else {
                $this->Session->setFlash(__("<h4 class='alert-heading'>Error</h4>An error occured, please contact your system administrator."), 'default', array(), 'error');
            }
        } else {
            $this->Session->setFlash(__("<h4 class='alert-heading'>Error</h4>The link given was not valid. Please contact your system administrator to manually activate your account."), 'default', array(), 'error');

        }
        $this->redirect('/');
        
    }
}

?>
