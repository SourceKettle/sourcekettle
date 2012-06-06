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

    public $uses = array('User', 'Setting');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('register', 'activate', 'lost_password', 'reset_password');
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
                //if data was posted therefore a submitted form
                if ($this->data['User']['password'] == $this->data['User']['password_confirm']) {
                    if ($this->data['User']['password'] != 'password') {
                        $this->User->create();
                        if ($this->User->save($this->request->data['User'])) {
                            $id = $this->User->getLastInsertID();

                            //Check to see if an SSH key was added and save it
                            if (!empty($this->data['User']['ssh_key'])) {
                                $this->User->SshKey->create();
                                $data = array('SshKey');
                                $data['SshKey']['user_id'] = $id;
                                $data['SshKey']['key'] = $this->request->data['User']['ssh_key'];
                                $data['SshKey']['comment'] = 'Default key';
                                $this->User->SshKey->save($data);
                            }

                            //Now to create the key and send the email

                            $key = $this->generate_key(20);
                            $data = array('EmailConfirmationKey');
                            $data['EmailConfirmationKey']['user_id'] = $id;
                            $data['EmailConfirmationKey']['key'] = $key;
                            $this->User->EmailConfirmationKey->save($data);

                            $link = Router::url('/activate/' . $key, true);

                            $message = "Dear " . $this->data['User']['name'] . " ,\n\nThank you for registering with DevTrack. In order to use your account, we require you to activate your account using the link below.\n\n" . $link . "\n\nWe hope you enjoy using DevTrack";

                            $email = new CakeEmail();
                            $email->config('default');
                            $email->to($this->data['User']['email']);
                            $email->subject('DevTrack activation');
                            $email->send($message);
                            echo($message); //TODO remove this line when emailing enabled

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
     * Register a user via an API call
     */
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
            $record = $this->User->EmailConfirmationKey->find('first', array('conditions' => array('key' => $key), 'recursive' => 1));
            if (!empty($record)) {
                $record['User']['is_active'] = 1;

                //create a new record to stop it rehashing the password
                $newrecord['User'] = array();
                $newrecord['User']['id'] = $record['User']['id'];
                $newrecord['User']['is_active'] = '1';
                if ($this->User->save($newrecord['User'])) {
                    $this->User->EmailConfirmationKey->delete($record['EmailConfirmationKey']['id'], false); //delete the email confirmation key
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
     * Function to allow for users to reset their passwords. Will generate a key and an email. 
     */
    public function lost_password($key = null) {
        if ($this->request->is('post')){
            //generate random password and email it to them
            $user = $this->User->findByEmail($this->request->data['User']['email']); //Get the user attached to the given email
            
            if (empty($user)){
                // Just pretend to send the user an email
                $this->Session->setFlash("An email was sent to the given email address. Please use the link to reset your password.", 'default', array(), 'success');
            } else {
                //Create a lost password key object
                $this->User->LostPasswordKey->create();
                $data['LostPasswordKey'] = array();
                $data['LostPasswordKey']['user_id'] = $user['User']['id'];
                $data['LostPasswordKey']['key'] = $this->generate_key(25);
                $this->User->LostPasswordKey->save($data);

                //Create the message
                $message = "Dear " . $user['User']['name'] . ",\n\n";
                $message .= "A request to reset your password was made, if this was by you then please click the link below within the next 30 minutes.\n\n";
                $message .= Router::url('/users/reset_password/' . $data['LostPasswordKey']['key'], true);
                $message .= "\n\nIf this request was not made by you then please ignore this email and the key will expire shortly.\n\n";

                //Send the email
                $email = new CakeEmail();
                $email->config('default');
                $email->to($user['User']['email']);
                $email->subject('DevTrack password reset');
                $email->send($message);
                echo($message); //TODO remove this line when emailing enabled
            }
            $this->redirect('/login');
        } else if($this->request->is('get')){
            //display the form or act on the link
            if ($key == null){
                // Display the form
                $this->render('lost_password');
            } else {
                // act on the key
                $passwordkey = $this->User->LostPasswordKey->findByKey($key);
                if (empty($passwordkey)){
                    $this->Session->setFlash("The key given was invalid", 'default', array(), 'error');
                    $this->render('lost_password');
                } else {
                    $this->reset_password($key);
                }
            }
        }
    }
    
    /**
     * The function to reset a password
     * @param type $key The LostPasswordKey to use
     */
    public function reset_password($key = null){
        if ($key == null){
            $this->Session->setFlash("A valid password reset key was not given.", 'default', array(), 'error');
            $this->redirect('/');
        } else {
            $passwordkey = $this->User->LostPasswordKey->findByKey($key);
            if (empty($passwordkey)){
                $this->Session->setFlash("The key given was invalid", 'default', array(), 'error');
                $this->redirect('lost_password');
            } else if ($this->request->is('post')){
                //Check if the key has expired
                
                App::uses('CakeTime', 'Utility');
                $keytime = CakeTime::toUnix($passwordkey['LostPasswordKey']['created']);
                if ($keytime + 1800 <= time()){
                    if ($this->request->data['User']['password'] == $this->request->data['User']['password_confirm']){ //if the passwords match
                        $this->User->id = $passwordkey['User']['id'];
                        if ($this->User->save($this->request->data)){
                            $this->User->LostPasswordKey->delete($passwordkey['LostPasswordKey']);
                            $this->Session->setFlash("Your password has been reset. You can now login.", 'default', array(), 'success');
                            $this->redirect('/login');
                        } else {
                            $this->Session->setFlash("There was problem resetting your password. Please try again.", 'default', array(), 'error');
                        }
                    } else {
                        $this->Session->setFlash("Your passwords do not match. Please try again.", 'default', array(), 'error');
                    }
                } else {
                    $this->Session->setFlash("The key given was invalid", 'default', array(), 'error');
                    $this->redirect('lost_password');
                }    
            }
        }
    }

    /**
     * Allows users to view their profile
     */
    public function index() {
        $this->redirect('editdetails');
    }
    
    
    /**
     * Allows admins to see all users
     */
    public function admin_index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    /**
     * View a user in admin mode
     * @param type $id The id of the user to view
     */
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
        $this->User->id = $this->Auth->user('id'); //get the current user
        
        if ($this->request->is('post')){
            if ($this->User->save($this->request->data)){
                $this->Session->setFlash(__('Your changes have been saved.'), 'default', array(), 'success');
                $this->Session->write('Auth.User.name', $this->request->data['User']['name']);
                $this->Session->write('Auth.User.email', $this->request->data['User']['email']);
                $this->set('user_name', $this->request->data['User']['name']);
            } else {
                $this->Session->setFlash(__('There was a problem saving your changes. Please try again.'), 'default', array(), 'error');
            }
        }
        
        //update the page
        $user = $this->Auth->user();
        $this->set('user', $user);
        $this->User->id = $user['id'];
        $this->request->data = $this->User->read();
        $this->request->data['User']['password'] = null;
    }

    /**
     * Edit the current users password
     */
    public function editpassword(){
        $this->User->id = $this->Auth->user('id'); //get the current user
        $user = $this->User->read(null, $this->User->id);
        $user = $user['User']; 
        if ($this->request->is('post')){
            if ($user['password'] == $this->Auth->password($this->request->data['User']['password_current'])){ //check their current password
                if ($this->request->data['User']['password'] == $this->request->data['User']['password_confirm']){ //check passwords match
                    
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
        
        //Update the page details
        $user = $this->Auth->user();
        $this->set('user', $user);
        $this->User->id = $user['id'];
        $this->request->data = $this->User->read();
        $this->request->data['User']['password'] = null;
    }
    
    /**
     * Add an SSH key for the current user
     */
    public function addkey(){
        if ($this->request->is('post')){
            $this->request->data['SshKey']['user_id'] = $this->Auth->user('id'); //Set the key to belong to the current user
            if ($this->User->SshKey->save($this->request->data)){
                $this->Session->setFlash(__('Your key was added successfully.'), 'default', array(), 'success');
            } else {
                $this->Session->setFlash(__('There was a problem saving your key. Please try again.'), 'default', array(), 'error');
            }
        } 
        
        //update the page
        $user = $this->Auth->user();
        $this->User->id = $user['id'];
        $this->request->data = $this->User->read();
        $this->request->data['User']['password'] = null;
        
    }
    
    /**
     * Deletes a ssh key of the current user
     * @param type $id The id of the key to delete
     */
    public function deletekey($id = null){
        if ($this->request->is('post') && $id != null){
            //Find the key object
            $key = $this->User->SshKey->find('first', array(
                'conditions' => array('SshKey.id' => $id)
            ));
            
            if ($key['SshKey']['user_id'] == $this->Auth->user('id')){ //check the key belongs to the current user
                if ($this->User->SshKey->delete($key['SshKey'])){
                    $this->Session->setFlash(__('Your key was removed successfully.'), 'default', array(), 'success');
                } else {
                    $this->Session->setFlash(__('There was a problem removing your key. Please try again.'), 'default', array(), 'error');
                }
            } else {
                $this->Session->setFlash(__('2There was a problem removing your key. Please try again.'), 'default', array(), 'error');
            }
        }
        
        //update the page
        $user = $this->Auth->user();
        $this->User->id = $user['id'];
        $this->request->data = $this->User->read();
        $this->request->data['User']['password'] = null;
    }
    
    /**
     * Function to delete a user
     * Use at your own peril
     * 
     * Deletes the current user (the one that is authenticated with the system) and any projects for which there are no other
     * collaborators
     */
    public function delete(){
        if($this->request->is('post')){
            $this->User->id = $this->Auth->user('id');
            
            //Now delete the user
            if ($this->User->delete($this->Auth->id)) {
                $this->Session->setFlash(__('Account deleted'), 'default', array(), 'success');
                //Now log them out of the system
                $this->Auth->logout();
                $this->redirect('/');
            }
            // TODO check what projects made this fail
            $this->Session->setFlash(__('Account was not deleted'), 'default', array(), 'error');
            $this->redirect(array('action' => 'delete'));
        } else {
            $user = $this->Auth->user();
            $this->User->id = $user['id'];
            $this->request->data = $this->User->read();
            $this->request->data['User']['password'] = null;
        }
    }
    
    /**
     * Function for viewing a user's public page
     * @param type $id The id of the user to view
     */
    public function view($id = null){
        $this->helpers[] = 'Time'; //load time helper
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        //Find the users public projects or public projects they are working on
        $this->User->Collaborator->Project->Collaborator->recursive = 0;
        $this->set('projects', $this->User->Collaborator->find('all', array('conditions' => array('Collaborator.user_id' => $this->Auth->user('id'), 'public' => true))));
        $this->set('user', $this->User->read(null, $id));
    }
}
