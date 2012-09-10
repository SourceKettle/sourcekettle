<?php
/**
 *
 * AppController for the DevTrack system
 * The application wide controller.
 *
 * Base system: CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Base system: Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Modifications: DevTrack Development Team 2012
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Original: Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @copyright     Modifications: DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.Controller
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

class AppController extends Controller {

    /**
     * The global helpers
     * @var type
     */
    public $helpers = array(
        'DT',
        'Js',
        'Html',
        'Text',
        'Session',
        'Form',
        'Bootstrap' => array('className' => 'TwitterBootstrap.TwitterBootstrap'),
        'Popover',
        'TwitterBootswatch.TwitterBootswatch',
        'Gravatar'
    );

    /**
     * Global components used for authentication, authorisation and session management.
     * @var type
     */
    public $components = array(
        'Session',
        'Auth' => array(
            //'authorize' => 'actions',
            'actionPath' => 'controllers/',
            'loginAction' => array(
                'controller' => 'login',
                'action' => 'index',
                'plugin' => false,
                'admin' => false,
            ),
            'loginRedirect' => array(
                'controller' => 'dashboard',
                'action' => 'index'
            ),
            'authenticate' => array(
                'Form' => array(
                    'fields' => array ('username' => 'email', 'password' => 'password')
                )
            )
        ));


    /**
     * Before filter method acts first in the controller
     *
     * Configures the auth component to use the email column as the user name
     */
    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->userModel = 'User';

        //Customise the login error
        $this->Auth->loginError = 'The credentials you entered were incorrect. Please try again or have you <a href="lost_password">lost your password</a>';

        //Customise thge auth error (when they try to access a protected part of the site)
        $this->Auth->authError = 'You need to login to view that page';

        //Use sha256 as the hashing algorithm for the site as it is the most secure out of the allowed options.
        Security::setHash('sha256');

        if($this->Auth->loggedIn()){
            $user_id = $this->Auth->user('id');
            $user_name = $this->Auth->user('name');
            $user_email = $this->Auth->user('email');
            $this->{$this->modelClass}->setCurrentUserData($user_id, $user_name, $user_email);
            $this->set('user_id', $user_id);
            $this->set('user_name', $user_name);
            $this->set('user_email', $user_email);
        }

        // Load config file in
        $this->devtrack_config = array_merge(Configure::read('devtrack'), ClassRegistry::init('Settings')->find('list', array('fields' => array('Settings.name', 'Settings.value'))));

        $this->set('devtrack_config', $this->devtrack_config);

        // if admin pages are being requested
        if(isset($this->params['admin'])) {
            // check the admin is logged in
            if ( $this->Auth->user('is_admin') == 0 ) $this->redirect('/');
        }
        if(isset($this->params['api'])) {
            // The following line kinda breaks the M->V->C thing
            $this->{$this->modelClass}->_is_api = true;
        }

        if ($theme = $this->Auth->user('theme')) {
            $this->set('user_theme', $theme);
        } else {
            $this->set('user_theme', null);
        }
    }

    protected function _api_auth_level() {
        $_USER_MODEL = ClassRegistry::init('User');

        if (array_key_exists('key', $this->request->query)) {
            $api_key = $this->request->query['key'];
        } else {
            $api_key = null;
        }

        // Check if an admin cookie exists
        if (!($user_id = $this->Auth->user('id'))) {
            // Get User with this API key
            $user_id = $_USER_MODEL->ApiKey->field('user_id', array('key' => $api_key));
        }

        $user = $_USER_MODEL->findById($user_id);

        if ($user != FALSE && $user['User']['is_admin']) {
            return 1;
        }
        return 0;
    }
}
