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
 * @copyright	Original: Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @copyright	Modifications: DevTrack Development Team 2012
 * @link		http://github.com/SourceKettle/devtrack
 * @package		DevTrack.Controller
 * @since		DevTrack v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('ArraySource', 'Model/Datasource');
App::import('Model', 'User');

class AppController extends Controller {

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

	public $components = array(
		'RequestHandler',
		'Session',
		'Flash',
		'Security' => array(
			'csrfUseOnce' => false
		),
		'Useful',
		'Auth' => array(
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
				),
			)
		)
	);

/**
 * Before filter method acts first in the controller
 *
 * Configures the auth component to use the email column as the user name
 */
	public function beforeFilter() {
		parent::beforeFilter();

		// Add new db config
		ConnectionManager::create('array', array('datasource' => 'ArraySource'));

		$this->Security->blackHoleCallback = 'appBlackhole';

		// Load config file in
		$this->devtrack_config = array_merge(
			Configure::read('devtrack'),
			ClassRegistry::init('Settings')->find('list', array('fields' => array('Settings.name', 'Settings.value')))
		);

		$this->set('devtrack_config', $this->devtrack_config);
		$this->set('devtrackVersion', 'v1.0');

		// Set up the devtrack-specific auth model
		$this->Auth->userModel = 'User';

		//Customise the login error
		$this->Auth->loginError = 'The credentials you entered were incorrect. Please try again, or have you <a href="lost_password">lost your password</a>?';

		//Customise thge auth error (when they try to access a protected part of the site)
		$this->Auth->authError = 'You need to login to view that page';

		// Now set up the Auth object's authentication settings based on the config settings

		//Use sha256 as the hashing algorithm for the site as it is the most secure out of the allowed options.
		Security::setHash('sha256');

		if ($this->Auth->loggedIn()) {
			User::store($this->Auth->user());

			$userId = User::get('id');
			$userName = User::get('name');
			$userEmail = User::get('email');
			$isAdmin = (User::get('is_admin') == 1);
			$this->set('user_id', $userId);
			$this->set('user_name', $userName);
			$this->set('user_email', $userEmail);
			$this->set('user_is_admin', $isAdmin);
		} else {
			$this->set('user_is_admin', false);
		}

		// if admin pages are being requested
		if (isset($this->params['admin'])) {
			// check the admin is logged in
			if ( !isset($userId) || empty($userId) ) $this->redirect('/login');
			if ( $this->Auth->user('is_admin') == 0 ) $this->redirect('/');
			$this->Flash->message('You are currently in the ADMIN section of the site..');
		}
		if (isset($this->params['api'])) {
			// The following line kinda breaks the M->V->C thing
			$this->{$this->modelClass}->_is_api = true;
		}

		if ($theme = $this->Auth->user('theme')) {
			$this->set('user_theme', $theme);
		} else {
			$this->set('user_theme', null);
		}

		// Is the user account devtrack-managed or external e.g. LDAP?
		if (isset($userId)) {
			$_userModel = ClassRegistry::init('User');
			$user = $_userModel->findById($userId);
			$this->set('user_is_devtrack_managed', User::isDevTrackManaged($user));
		}
	}

	public function appBlackhole($type) {
		if ($type == 'csrf') {
			// if a CSRF violation
			$this->Flash->errorReason("The request was blackholed due to a CSRF violation. You have either tried to submit this form more than once or submitted the form from another web site");

			if (!$this->request->is('ajax')) {
				$this->redirect($this->referer());
			}
		}
	}

	protected function _apiAuthLevel() {
		$_userModel = ClassRegistry::init('User');

		if (array_key_exists('key', $this->request->query)) {
			$apiKey = $this->request->query['key'];
		} else {
			$apiKey = null;
		}

		// Check if an admin cookie exists
		if (!($userId = $this->Auth->user('id'))) {
			// Get User with this API key
			$userId = $_userModel->ApiKey->field('user_id', array('key' => $apiKey));
		}

		$user = $_userModel->findById($userId);

		if ($user != false && $user['User']['is_admin']) {
			return 1;
		}
		return 0;
	}
}
