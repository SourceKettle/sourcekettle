<?php
/**
 *
 * AppController for the SourceKettle system
 * The application wide controller.
 *
 * Base system: CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Base system: Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Modifications: SourceKettle Development Team 2012
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Original: Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @copyright	Modifications: SourceKettle Development Team 2012
 * @link		http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.Controller
 * @since		SourceKettle v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');
App::uses('Sanitize', 'Utility');

App::import('Model', 'User');

class AppController extends Controller {

	public $helpers = array(
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
				'controller' => 'pages',
				'action' => 'home',
				'plugin' => false,
				'admin' => false,
			),
			'loginRedirect' => array(
				'controller' => 'dashboard',
				'action' => 'index'
			),
			'authorize' => array('Controller')
		)
	);

	// Default authorisation - allow admins, disallow anyone else.
	// Override this in the other controller objects to taste.
	public function isAuthorized($user) {

		// Deactivated users explicitly do not get access
		// (NB they should not be able to log in anyway, of course!)
		if (@$user['is_active'] != 1) {
			return false;
		}

		// Sysadmins can do anything...
		if (@$user['is_admin'] == 1) {
			return true;
		}

		// No further information available
		return false;
	}

	// Cleans up POST data to retrieve only allowed fields
	protected function _cleanPost($allowedFields = array()) {
		$data = array();
		// Take any fields present in the POST data and add them to our cleaned array
		foreach ($allowedFields as $field) {
			$value = array_pop(Hash::extract($this->request->data, $field));
			if (isset($value)) {
				$data = Hash::insert($data, $field, $value);
			}
		}
		return $data;
	}

/**
 * Before filter method acts first in the controller
 *
 * Configures the auth component to use the email column as the user name
 */
	public function beforeFilter() {
		parent::beforeFilter();
		if (isset($this->params['admin'])) {
			$this->set('sidebar', 'admin');
		}

		// There are various database models that are simple lists - we will simply load them here
		// so they can be used as lookups.
		$this->set('task_priorities', ClassRegistry::init('TaskPriority')->getLookupTable());
		$this->set('task_statuses',   ClassRegistry::init('TaskStatus')->getLookupTable());
		$this->set('task_types',      ClassRegistry::init('TaskType')->getLookupTable());

		//var_dump($this->Auth); exit(0);
		$this->Security->blackHoleCallback = 'appBlackhole';

		// Load config
		$this->sourcekettle_config = ClassRegistry::init('Setting')->loadConfigSettings();

		// Default form authentication
		$formConfig = array(
			'fields' => array ('username' => 'email', 'password' => 'password')
		);

		// Default to just form-based authentication
		$this->Auth->authenticate = array('Form' => $formConfig);

		if ($this->sourcekettle_config['Ldap']['enabled']['value']) {
			$ldapConfErrors = array();

			// Check for existence of all LDAP config fields
			if (!isset($this->sourcekettle_config['Ldap']['url']['value']) || empty($this->sourcekettle_config['Ldap']['url']['value'])) {
				$ldapConfErrors[] = 'ldap config error: must specify an LDAP URL';
			}
			if (!isset($this->sourcekettle_config['Ldap']['bind_dn']['value'])) {
				$ldapConfErrors[] = 'ldap config error: must specify a bind DN, or empty string';
			}
			if (!isset($this->sourcekettle_config['Ldap']['bind_pw']['value'])) {
				$ldapConfErrors[] = 'ldap config error: must specify a bind password, or empty string';
			}
			if (!isset($this->sourcekettle_config['Ldap']['base_dn']['value']) || empty($this->sourcekettle_config['Ldap']['base_dn']['value'])) {
				$ldapConfErrors[] = 'ldap config error: must specify an LDAP base DN';
			}

			// Make sure all-whitespace string is converted to empty string for binding...
			if (preg_match('/^\s*$/', $this->sourcekettle_config['Ldap']['bind_dn']['value'])) {
				$this->sourcekettle_config['Ldap']['bind_dn']['value'] = '';
			}
			if (preg_match('/^\s*$/', $this->sourcekettle_config['Ldap']['bind_pw']['value'])) {
				$this->sourcekettle_config['Ldap']['bind_pw']['value'] = '';
			}

			// Default: look them up by the 'mail' field
			if (!isset($this->sourcekettle_config['Ldap']['filter']['value'])) {
				$this->sourcekettle_config['Ldap']['filter']['value'] = '(mail=%USERNAME%)';
			}

			// Default: use the email address the user typed in
			if (!isset($this->sourcekettle_config['Ldap']['email_field']['value'])) {
				$this->sourcekettle_config['Ldap']['email_field']['value'] = '__SUPPLIED__';
			}

			// Default: use the given name and surname fields as the name
			if (!isset($this->sourcekettle_config['Ldap']['name_field']['value'])) {
				$this->sourcekettle_config['Ldap']['name_field']['value'] = 'givenName sn';
			}

			// Report errors, do not enable LDAP
			if (count($ldapConfErrors) > 0) {
				foreach ($ldapConfErrors as $err) {
					$this->log($err, 'error');
				}
			} else {
				$ldapConfig = array(
					'ldap_url'          => $this->sourcekettle_config['Ldap']['url']['value'],
					'ldap_bind_dn'      => $this->sourcekettle_config['Ldap']['bind_dn']['value'],
					'ldap_bind_pw'      => $this->sourcekettle_config['Ldap']['bind_pw']['value'],
					'ldap_base_dn'      => $this->sourcekettle_config['Ldap']['base_dn']['value'],
					'ldap_filter'       => $this->sourcekettle_config['Ldap']['filter']['value'],
					'ldap_to_user'      => array(
						$this->sourcekettle_config['Ldap']['email_field']['value'] => 'email',
						$this->sourcekettle_config['Ldap']['name_field']['value'] => 'name',
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
				$this->Auth->authenticate = array('LDAPAuthCake.LDAP' => $ldapConfig, 'Form' => $formConfig);

			}

		}

		// Set up the sourcekettle-specific auth model
		$this->Auth->userModel = 'User';

		//Customise the login error
		$this->Auth->loginError = __('The credentials you entered were incorrect. Please try again, or have you <a href="%s">lost your password</a>?', Router::url('/lost_password'));

		//Customise thge auth error (when they try to access a protected part of the site)
		$this->Auth->authError = 'You need to login to view that page';

		// Now set up the Auth object's authentication settings based on the config settings

		//Use sha256 as the hashing algorithm for the site as it is the most secure out of the allowed options.
		Security::setHash('sha256');

		// Currently logged-in user ID
		$userId = null;

		$this->User = ClassRegistry::init('User');

		if ($this->Auth->loggedIn()) {
			$userId = $this->Auth->user('id');

			$current_user = $this->User->findById($userId);
			$this->set('current_user', $current_user['User']);
			// Pretty much nicked from http://bakery.cakephp.org/articles/alkemann/2008/10/21/logablebehavior
			foreach ($this->uses as $class) {
				if (@$this->{$class}->Behaviors && $this->{$class}->Behaviors->enabled('ProjectHistory')) {
					$this->{$class}->setLogUser($current_user['User']);
				}
			}


		} else {
			$this->set('current_user', null);
		}
		// if admin pages are being requested
		if (isset($this->params['admin'])) {
			// check the admin is logged in
			if ( !isset($userId) || empty($userId) ) return $this->redirect('/');
			if ( $this->Auth->user('is_admin') == 0 ) return $this->redirect('/');
		}
		if (isset($this->params['api'])) {
			// The following line kinda breaks the M->V->C thing
			// TODO this needs tidying up
			$this->{$this->modelClass}->_is_api = true;
		}

		// Re-load the settings, now optionally with user and project settings
		if (isset($this->params->params['project'])) {
			$project = $this->params->params['project'];
		} else {
			$project = null;
		}

		// Override any non-locked settings with user/project settings
		$this->sourcekettle_config = ClassRegistry::init('Setting')->loadConfigSettings($this->Auth->user('id'), $project);

		// Set config and version
		$this->set('sourcekettle_config', $this->sourcekettle_config);
		$this->set('sourcekettleVersion', 'v1.6.3');


	}

	public function appBlackhole($type) {
		if ($type == 'csrf') {
			// if a CSRF violation
			$this->Flash->errorReason("The request was blackholed due to a CSRF violation. You have either tried to submit this form more than once or submitted the form from another web site");

			if (!$this->request->is('ajax')) {
				return $this->redirect($this->referer());
			}
		}
	}

}
