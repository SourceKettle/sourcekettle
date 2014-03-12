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
			)
		)
	);

/*	public function __construct($request = null, $response = null){
		parent::__construct($request, $response);
		$settings = ClassRegistry::init('Settings')->find('list', array('fields' => array('Settings.name', 'Settings.value')));
		
	}
*/

/**
 * Before filter method acts first in the controller
 *
 * Configures the auth component to use the email column as the user name
 */
	public function beforeFilter() {
		parent::beforeFilter();
putenv('LDAPTLS_REQCERT=never');
		$this->Security->blackHoleCallback = 'appBlackhole';

		// Load config file in
		$this->devtrack_config = array_merge(
			Configure::read('devtrack'),
			ClassRegistry::init('Settings')->find('list', array('fields' => array('Settings.name', 'Settings.value')))
		);

		// Default form authentication
		$form_config = array(
			'fields' => array ('username' => 'email', 'password' => 'password')
		);

		// Is LDAP authentication enabled?
		if(!isset($this->devtrack_config['ldap_enabled'])){
			$this->devtrack_config['ldap_enabled'] = false;
		}

		// Default to just form-based authentication
		$this->Auth->authenticate = array('Form' => $form_config);

		if($this->devtrack_config['ldap_enabled']){
			$ldap_conf_errors = array();

			// Check for existence of all LDAP config fields
			if(!isset($this->devtrack_config['ldap_url']) || empty($this->devtrack_config['ldap_url'])){
				$ldap_conf_errors[] = 'ldap config error: must specify an LDAP URL';
			}
			if(!isset($this->devtrack_config['ldap_bind_dn'])){
				$ldap_conf_errors[] = 'ldap config error: must specify a bind DN, or empty string';
			}
			if(!isset($this->devtrack_config['ldap_bind_pw'])){
				$ldap_conf_errors[] = 'ldap config error: must specify a bind password, or empty string';
			}
			if(!isset($this->devtrack_config['ldap_base_dn']) || empty($this->devtrack_config['ldap_base_dn'])){
				$ldap_conf_errors[] = 'ldap config error: must specify an LDAP base DN';
			}

			// Make sure all-whitespace string is converted to empty string for binding...
			if(preg_match('/^\s*$/', $this->devtrack_config['ldap_bind_dn'])){
				$this->devtrack_config['ldap_bind_dn'] = '';
			}
			if(preg_match('/^\s*$/', $this->devtrack_config['ldap_bind_pw'])){
				$this->devtrack_config['ldap_bind_pw'] = '';
			}

			// Default: look them up by the 'mail' field
			if(!isset($this->devtrack_config['ldap_filter'])){
				$this->devtrack_config['ldap_filter'] = '(mail=%USERNAME%)';
			}

			// Default: use the email address the user typed in
			if(!isset($this->devtrack_config['ldap_email_field'])){
				$this->devtrack_config['ldap_email_field'] = '__SUPPLIED__';
			}

			// Default: use the given name and surname fields as the name
			if(!isset($this->devtrack_config['ldap_name_field'])){
				$this->devtrack_config['ldap_name_field'] = 'givenName sn';
			}

			// Report errors, do not enable LDAP
			if(count($ldap_conf_errors) > 0){
				foreach ($ldap_conf_errors as $err){
					$this->log($err, 'error');
				}
			} else {
				$ldap_config = array(
					'ldap_url'          => $this->devtrack_config['ldap_url'],
						'ldap_bind_dn'      => $this->devtrack_config['ldap_bind_dn'],
						'ldap_bind_pw'      => $this->devtrack_config['ldap_bind_pw'],
						'ldap_base_dn'      => $this->devtrack_config['ldap_base_dn'],
						'ldap_filter'       => $this->devtrack_config['ldap_filter'],
						'ldap_to_user'      => array(
							$this->devtrack_config['ldap_email_field'] => 'email',
							$this->devtrack_config['ldap_name_field'] => 'name',
						),
						// TODO this is an array, we need to make sure we can DB-ify this
						'all_usernames' => array(
							'proxyAddresses',
							'mail',
						),

						// These are SourceKettle-specific, not server-specific
						'form_fields'       => array ('username' => 'email', 'password' => 'password'),
						'defaults'      => array(
							'is_active' => 1,
							'is_admin'  => 0,
						)
				);

				// Put the LDAP authentication in before form auth
				$this->Auth->authenticate = array('LDAPAuthCake.LDAP' => $ldap_config, 'Form' => $form_config);

			}

		}

		$this->set('devtrack_config', $this->devtrack_config);
		$this->set('devtrackVersion', 'v1.1');

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
