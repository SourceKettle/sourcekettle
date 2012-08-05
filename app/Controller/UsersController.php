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

class UsersController extends AppController {

    public $helpers = array('Time');
    public $components = array('Email');

    public $uses = array('User', 'Setting');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('register', 'activate', 'lost_password',
            'reset_password',
            'api_all', 'api_view', 'api_register');
    }

    /**
     * Function to allow users to register with the application
     */
    public function register() {
        $this->set('title_for_layout', 'Register');
        //Check if registration is allowed by the user
        if ($this->Setting->field('value', array('name' => 'register_enabled'))) {
            //Registration part
            if ($this->request->is('post')) {
                //if data was posted therefore a submitted form
                if ($this->data['User']['password'] == $this->data['User']['password_confirm']) {
                    if ($this->data['User']['password'] != 'password') {
                        $this->User->create();
                        if ($this->User->save($this->request->data['User'])) {
                            $id = $this->User->getLastInsertID();
                            $this->log("[UsersController.register] user[${id}] created", 'devtrack');

                            //Check to see if an SSH key was added and save it
                            if (!empty($this->data['User']['ssh_key'])) {
                                $this->User->SshKey->create();
                                $data = array('SshKey');
                                $data['SshKey']['user_id'] = $id;
                                $data['SshKey']['key'] = $this->request->data['User']['ssh_key'];
                                $data['SshKey']['comment'] = 'Default key';
                                $this->User->SshKey->save($data);
                                
                                // Update the sync required flag
                                $sync_required = $this->Setting->find('first', array('conditions' => array('name' => 'sync_required')));
                                $sync_required['Setting']['value'] = 1;
                                $this->Setting->save($sync_required);
                                
                                $this->log("[UsersController.register] sshkey[".$this->User->SshKey->getLastInsertID()."] added to user[${id}]", 'devtrack');
                            }

                            //Now to create the key and send the email
                            $this->User->EmailConfirmationKey->save(
                                array('EmailConfirmationKey' => array(
                                    'user_id' => $id,
                                    'key' => $this->_generate_key(20),
                                ))
                            );
                            $this->_sendNewUserMail($id);
                            $this->render('email_sent');
                        } else {
                            $this->Session->setFlash(__("<h4 class='alert-heading'>Error</h4>One or more fields were not filled in correctly. Please try again."), 'default', array(), 'error');
                        }
                    } else {
                        $this->Session->setFlash(__("<h4 class='alert-heading'>Oh Dear...</h4>I see what you did there. 'password' is not a good password. Be more original!"), 'default', array(), 'error');
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
        $this->set('data', array(1,2,3));
        $this->layout = 'ajax';
        $this->render('/Elements/json');
    }

    public function api_view($id = null) {
        $this->layout = 'ajax';
        if ($id == null) {
            $this->set('data',array(
                'error' => 400,
                'message' => 'Bad request, no user id specified',
                ));
        } else {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                $this->set('data',array(
                    'error' => 404,
                    'message' => 'No user found of that ID.',
                    'id' => $id,
                ));
            } else {
                //$this->set('data',$this->User->read(null, $id));
                $user = $this->User->find("first", array(
                    'conditions' => array('id' => $id),
                    'recursive' => -1, // Don't recurse other models
                ));

                unset($user['User']['password']); // goodbye hash :'(
                $this->set('data',$user['User']);
            }
        }

        $this->render('/Elements/json');
    }

    public function api_all() {
        // Only admins should be able to call this
        $this->layout = 'ajax';

        if (array_key_exists('key', $this->request->query)) {
            $api_key = $this->request->query['key'];
        } else {
            $api_key = null;
        }

        // Get User with this API key
        //
        $user_id = $this->User->ApiKey->field('user_id',
            array('key' => $api_key));

        $user = $this->User->findById($user_id);

        if ($user != FALSE && $user['User']['is_admin']) {
            $all = $this->User->find("all", array(
                'recursive' => -1,
            ));

            $new = array();

            foreach ($all as $item) {
                unset($item['User']['password']);
                array_push($new,$item['User']);
            }

            $this->set('data',$new);
        } else {
            $this->set('data',array(
                'error' => 403,
                'message' => 'You are not authorised to access this.',
            ));
        }

        $this->render('/Elements/json');
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
                    $this->log("[UsersController.activate] user[".$newrecord['User']['id']."] activated", 'devtrack');

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

            if (!empty($user)){
                //Now to create the key and send the email
                $this->User->LostPasswordKey->save(
                    array('LostPasswordKey' => array(
                        'user_id' => $user['User']['id'],
                        'key' => $this->_generate_key(25),
                    ))
                );
                $this->_sendForgottenPasswordMail($user['User']['id'], $this->User->LostPasswordKey->getLastInsertID());
                $this->log("[UsersController.lost_password] lost password email sent to user[".$user['User']['id']."]", 'devtrack');
            }
            $this->Session->setFlash("An email was sent to the given email address. Please use the link to reset your password.", 'default', array(), 'success');
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
                if ($keytime + 1800 >= time()){
                    if ($this->request->data['User']['password'] == $this->request->data['User']['password_confirm']){ //if the passwords match
                        $this->User->id = $passwordkey['User']['id'];
                        if ($this->User->save($this->request->data)){
                            $this->User->LostPasswordKey->delete($passwordkey['LostPasswordKey']);
                            $this->Session->setFlash("Your password has been reset. You can now login.", 'default', array(), 'success');
                            $this->log("[UsersController.reset_password] password reset for user[".$passwordkey['User']['id']."]", 'devtrack');

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
     * Allows admins to see all users
     */
    public function admin_index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    /**
     * Allows users to view their profile
     */
    public function index() {
        $this->redirect(array ('action' => 'details'));
    }

    /**
     * View a user in admin mode
     * @param type $id The id of the user to view
     */
    public function admin_view($id = null) {
        $this->User->id = $id;

        if (!$this->User->exists()) throw new NotFoundException(__('Invalid user'));

        //Find the users projects they are working on
        $this->set('projects', $this->User->Collaborator->findAllByUser_id($id));
        $this->request->data = $this->User->read();
        $this->request->data['User']['password'] = null;
    }

    /**
     * Function for viewing a user's public page
     * @param type $id The id of the user to view
     */
    public function view($id = null){
        $this->User->id = $id;

        if (!$this->User->exists()) throw new NotFoundException(__('Invalid user'));

        //Find the users public projects or public projects they are working on
        $this->User->Collaborator->Project->Collaborator->recursive = 0;
        $this->set('projects', $this->User->Collaborator->find('all', array('conditions' => array('Collaborator.user_id' => $id, 'public' => true))));
        $this->set('user', $this->User->read(null, $id));
    }

    /**
     * Create a new user
     */
    public function add() {
        $this->redirect('register');
    }

    /**
     * Create a new user
     */
    public function admin_add() {
        if ($this->request->is('post')) { // if data was posted therefore a submitted form
            $this->User->create();
            if ($this->User->save($this->request->data['User'])) {
                $id = $this->User->getLastInsertID();
                $this->log("[UsersController.admin_add] user[${id}] created by user[".$this->Auth->user('id')."]", 'devtrack');

                //Now to create the key and send the email
                $this->User->EmailConfirmationKey->save(
                    array('EmailConfirmationKey' => array(
                        'user_id' => $id,
                        'key' => $this->_generate_key(20),
                    ))
                );
                $this->_sendAdminCreatedUserMail($id, $this->User->LostPasswordKey->getLastInsertID());
                $this->Session->setFlash(__('New User added successfully.'), 'default', array(), 'success');
                $this->log("[UsersController.admin_add] user[".$id."] added by user[".$this->Auth->user('id')."]", 'devtrack');
                $this->redirect(array('action' => 'view', $id));
            } else {
                $this->Session->setFlash(__("<h4 class='alert-heading'>Error</h4>One or more fields were not filled in correctly. Please try again."), 'default', array(), 'error');
            }
        }
    }

    /**
     * Edit the name and the email address of a user
     * @param type $id The id of the user to edit
     */
    public function admin_edit($id = null){
        $this->User->id = $id;

        if (!$this->User->exists()) throw new NotFoundException(__('Invalid user'));

        if ($this->request->is('post')){
            if ($this->User->save($this->request->data)){
                $this->Session->setFlash(__('Your changes have been saved.'), 'default', array(), 'success');
                $this->log("[UsersController.admin_edit] user[".$this->Auth->user('id')."] edited details of user[".$this->User->id."]", 'devtrack');
            } else {
                $this->Session->setFlash(__('There was a problem saving your changes. Please try again.'), 'default', array(), 'error');
            }
        }
        $this->redirect(array('controller' => 'users', 'action' => 'admin_view', $this->User->id));
    }

    /**
     * Edit the name and the email address of the current user
     */
    public function details(){
        $this->User->id = $this->Auth->user('id'); //get the current user

        if ($this->request->is('post')){
            if ($this->User->save($this->request->data)){
                $this->Session->setFlash(__('Your changes have been saved.'), 'default', array(), 'success');
                $this->log("[UsersController.details] user[".$this->User->id."] edited details", 'devtrack');

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
    public function security(){
        $this->User->id = $this->Auth->user('id'); //get the current user
        $user = $this->User->read(null, $this->User->id);
        $user = $user['User'];
        if ($this->request->is('post')){
            if ($user['password'] == $this->Auth->password($this->request->data['User']['password_current'])){ //check their current password
                if ($this->request->data['User']['password'] == $this->request->data['User']['password_confirm']){ //check passwords match

                    if ($this->User->save($this->request->data)){
                        $this->Session->setFlash(__('Your changes have been saved.'), 'default', array(), 'success');
                        $this->log("[UsersController.security] user[".$this->Auth->user('id')."] changed password", 'devtrack');
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
     * Edit the current users theme
     */
    public function theme(){
        $this->User->id = $this->Auth->user('id'); //get the current user

        if ($this->request->is('post')){
            $this->User->set('theme', (string) $this->request->data['User']['theme']);
            if ($this->User->save()){
                // Don't update the used theme until we're sure we saved
                
                $this->Session->setFlash(__('Your changes have been saved.'), 'default', array(), 'success');
                $this->log("[UsersController.theme] user[".$this->Auth->user('id')."] changed theme", 'devtrack');
                $this->Session->write('Auth.User.theme', (string) $this->request->data['User']['theme']);
                $this->redirect(array('action'=>'theme'));
            } else {
                $this->Session->setFlash(__('There was a problem saving your changes. Please try again.'), 'default', array(), 'error');
            }
        }
        
        $user = $this->Auth->user();
        $this->set('user', $user);
        $this->User->id = $user['id'];
        $this->request->data = $this->User->read();
        $this->request->data['User']['password'] = null; // We need to set the password to null, otherwise it get's changed!
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
                $this->log("[UsersController.delete] user[".$this->Auth->user('id')."] deleted", 'devtrack');

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
     * Generates a random key of a given length
     * @param type $length The length of the key
     * @return string The random key
     */
    private function _generate_key($length) {
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

    /*
     * _sendNewUserMail
     * Send a user a registration email
     *
     * @param $id int the id of the user to email
     */
    private function _sendNewUserMail($id) {
        // No sending emails in debug mode
        if ( Configure::read('debug') > 1 ) {
            $this->Email->delivery = 'debug';
        }
        $User = $this->User->read(null,$id);
        $Addr = $this->Setting->field('value', array('name' => 'sysadmin_email'));
        $Key  = $this->User->EmailConfirmationKey->field('key', array('user_id' => $id));

        $this->Email->to = $User['User']['email'];
        $this->Email->bcc = array('secret@example.com');
        $this->Email->subject = 'Welcome to DevTrack - Account activation';
        $this->Email->replyTo = $Addr;
        $this->Email->from = 'DevTrack Admin <'.$Addr.'>';
        $this->Email->template = 'email_activation';

        $this->Email->sendAs = 'text'; // because we hate to send pretty mail

        //Set view variables as normal
        $this->set('User', $User);
        $this->set('Key', $Key);
        if ($this->Email->send()) {
            return true;
        }
        return false;
    }

    /*
     * _sendForgottenPasswordMail
     * Send a user a forgotten password email
     *
     * @param $id int the id of the user to email
     * @param $key int the id of the key to send
     */
    private function _sendForgottenPasswordMail($id, $key) {
        // No sending emails in debug mode
        if ( Configure::read('debug') > 1 ) {
            $this->Email->delivery = 'debug';
        }
        $User = $this->User->read(null,$id);
        $Key  = $this->User->LostPasswordKey->read(null,$id);
        $Addr = $this->Setting->field('value', array('name' => 'sysadmin_email'));

        $this->Email->to = $User['User']['email'];
        //$this->Email->bcc = array('secret@example.com');
        $this->Email->subject = 'DevTrack - Forgotten Password';
        $this->Email->replyTo = $Addr;
        $this->Email->from = 'DevTrack Admin <'.$Addr.'>';
        $this->Email->template = 'email_forgotten_password';

        $this->Email->sendAs = 'text'; // because we hate to send pretty mail

        //Set view variables as normal
        $this->set('User', $User);
        $this->set('Key', $Key);
        if ($this->Email->send()) {
            return true;
        }
        return false;
    }

    /*
     * _sendAdminCreatedUserMail
     * Send a user a email saying an account has been created
     *
     * @param $id int the id of the user to email
     * @param $key int the id of the key to send
     */
    private function _sendAdminCreatedUserMail($id, $key) {
        // No sending emails in debug mode
        if ( Configure::read('debug') > 1 ) {
            $this->Email->delivery = 'debug';
        }
        $User = $this->User->read(null,$id);
        $Key  = $this->User->LostPasswordKey->read(null,$id);
        $Addr = $this->Setting->field('value', array('name' => 'sysadmin_email'));

        $this->Email->to = $User['User']['email'];
        //$this->Email->bcc = array('secret@example.com');
        $this->Email->subject = 'Welcome to DevTrack - Suprise!';
        $this->Email->replyTo = $Addr;
        $this->Email->from = 'DevTrack Admin <'.$Addr.'>';
        $this->Email->template = 'email_admin_create';

        $this->Email->sendAs = 'text'; // because we hate to send pretty mail

        //Set view variables as normal
        $this->set('User', $User);
        $this->set('Key', $Key);
        if ($this->Email->send()) {
            return true;
        }
        return false;
    }
}
