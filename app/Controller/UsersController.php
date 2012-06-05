<?php

/**
 *
 * Users Controller for the DevTrack system
 * Provides methods for users to interact with their database object. Contains methods
 * for them to register, update their details and delete their account.
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
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class UsersController extends AppController {

    public $uses = array('User', 'Setting', 'EmailConfirmationKey', 'SshKey');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('register', 'activate', 'lost_password');
    }

    /**
     * Function to allow users to register with the application
     */
    public function register() {
        $this->set('title_for_layout', 'Register');
        //Check if registration is allowed by the user
        $enabled = $this->Setting->find('first', array('conditions' => array('name' => 'register_enabled')));
        if ($enabled['Setting']['value']) { //Check the setting
            //Registration part
            if ($this->request->is('post')) {
                $user = $this->User->findByEmail($this->request->data['User']['email']);
                if (!empty($user)) {
                    $this->Session->setFlash("A user already exists with the email address specified.", 'default', array(), 'error');
                } else {

                    //if data was posted therefore a submitted form
                    if ($this->data['User']['password'] == $this->data['User']['password_confirm']) {
                        if ($this->data['User']['password'] != 'password') {
                            $this->User->create();
                            if ($this->User->save($this->request->data['User'])) {
                                $id = $this->User->getLastInsertID();

                                //Check to see if an SSH key was added and save it
                                if (!empty($this->data['User']['ssh_key'])) {
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
            }
        } else {
            //Display an error saying that registration is not allowed
            $this->render('registration_disabled');
        }
    }

    public function api_register() {
        
    }

    /**
     * Generates a random key of a given length
     * @param type $length The length of the key
     * @return string The random key 
     */
    private function generate_key($length) {
        $key = "";
        $i = 0;
        $possible = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        while ($i < $length) {
            $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);

            if (!strstr($key, $char)) {
                $key .= $char;
                $i++;
            }
        }
        return $key;
    }

    /**
     * Used to activate user accounts
     * @param type $key The key used to activate an account
     */
    public function activate($key = null) {
        if ($key == null) {
            $this->Session->setFlash("The key given was not a valid activation key.", 'default', array(), 'error');
            $this->redirect('/');
        } else {
            $record = $this->EmailConfirmationKey->find('first', array('conditions' => array('key' => $key), 'recursive' => 1));
            if (!empty($record)) {
                $record['User']['is_active'] = 1;

                //create a new record to stop it rehashing the password
                $newrecord['User'] = array();
                $newrecord['User']['id'] = $record['User']['id'];
                $newrecord['User']['is_active'] = '1';
                if ($this->User->save($newrecord['User'])) {
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

    /**
     * Function to allow for users to reset their passwords
     */
    public function lost_password() {
        
    }

    /**
     * Allows users to view their profile
     */
    public function index() {
        $user = $this->Auth->user();
        $this->set('user', $user);
        $this->User->id = $user['id'];
        $this->request->data = $this->User->read();
        $this->request->data['User']['password'] = null;
    }
    
    

    /**
     * Allows admins to see all users
     */
    public function admin_index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function admin_view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }
    
    /**
     * Edit the name and the email address of the current user
     */
    public function editdetails(){
        $this->User->id = $this->Auth->user('id');
        
        if ($this->request->is('post')){
            if ($this->User->save($this->request->data)){
                $this->Session->setFlash(__('Your changes have been saved.'), 'default', array(), 'success');
                $this->Session->write('Auth.User.name', $this->request->data['User']['name']);
                $this->Session->write('Auth.User.email', $this->request->data['User']['email']);
            } else {
                $this->Session->setFlash(__('There was a problem saving your changes. Please try again.'), 'default', array(), 'error');
            }
        }
        
        $this->redirect('index');
    }

    /**
     * Edit the current users password
     */
    public function editpassword(){
        $this->User->id = $this->Auth->user('id');
        $user = $this->User->read(null, $this->User->id);
        $user = $user['User'];
        if ($this->request->is('post')){
            if ($user['password'] == $this->Auth->password($this->request->data['User']['password_current'])){
                if ($this->request->data['User']['password'] == $this->request->data['User']['password_confirm']){
                    
                    if ($this->User->save($this->request->data)){
                        $this->Session->setFlash(__('Your changes have been saved.'), 'default', array(), 'success');
                    } else {
                        $this->Session->setFlash(__('There was a problem saving your changes. Please try again.'), 'default', array(), 'error');
                    } 
                } else {
                    $this->Session->setFlash(__('Your passwords did not match. Please try again.'), 'default', array(), 'error');
                }      
            } else {
                $this->Session->setFlash(__('Your current password was incorrect. Please try again.'), 'default', array(), 'error');
            }
            
            
        }
        
        $this->redirect('index');
    }
}
