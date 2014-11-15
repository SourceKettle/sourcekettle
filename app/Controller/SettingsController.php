<?php

/**
 *
 * SettingsController Controller for the SourceKettle system
 * Provides the hard-graft control of the settings of the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link			http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.Controller
 * @since		 SourceKettle v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');

class SettingsController extends AppController {

	// Regexes for validating LDAP-related fields
	private static $_regex = array(
		'Ldap,url' => '/^ldap(s)?:\/\/[.a-zA-Z0-9-]+$/',
		'Ldap,base_dn'  => '/^[a-zA-Z]+=[a-zA-Z]+(,[a-zA-Z]+=[a-zA-Z]+)*$/',
		'Ldap,bind_dn'  => '/^[a-zA-Z]+=[a-zA-Z]+(,[a-zA-Z]+=[a-zA-Z]+)*$/',
		//'Users,sysadmin_email' => Validation::email,
		//'Users,email' => Validation::email,
	);

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
	}

	public function admin_set() {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}

		$code = 200;
		$message = '';

		foreach ($this->request->data as $name => $value) {
			$name = preg_replace('/,/', '.', $name);
			if (strtolower($value) == 'true') {
				$value = 1;
			} elseif(strtolower($value) == 'false') {
				$value = 0;
			}
			$this->Setting->id = $this->Setting->field('id', array('name' => $name));
			if ($this->Setting->exists()) {
				$this->Setting->set('value', $value);
				if (!$this->Setting->save()) {
					$message .= __('Failed to update setting "%s";', $name);
				}
			} else {
				$message .= __('Unknown setting "%s";', $name);
			}
		}

		if (empty($message)) {
			$message = __('Settings updated OK');
		}

		$this->set('data', array('code' => $code, 'message' => $message));
		$this->render('/Elements/json');
	}

	function admin_setLock() {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}

		$code = 200;
		$message = '';

		foreach ($this->request->data as $name => $value) {
			if (strtolower($value) == 'true') {
				$value = 1;
			} else {
				$value = 0;
			}

			$name = preg_replace('/,/', '.', $name);
			$this->Setting->id = $this->Setting->field('id', array('name' => $name));
			if ($this->Setting->exists()) {
				$this->Setting->set('locked', $value);
				if (!$this->Setting->save()) {
					$message .= __('Failed to update setting "%s";', $name);
				}
			} else {
				$message .= __('Unknown setting "%s";', $name);
			}
		}

		if (empty($message)) {
			$message = __('Settings updated OK');
		}

		$this->set('data', array('code' => $code, 'message' => $message));
		$this->render('/Elements/json');
	}

/**
 * admin_setEmail method
 *
 * @throws MethodNotAllowedException
 * @return void
 */
	public function admin_setEmail() {
		// Check form value provided and request is a post request
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Setting->id = $this->Setting->field('id', array('name' => 'sysadmin_email'));
		if ($this->Setting->exists() && Validation::email($this->request->data['Settings']['sysadmin_email'])) {
			$this->Setting->set('value', $this->request->data['Settings']['sysadmin_email']);
			$this->Setting->save();
		}
		return $this->redirect(array('admin' => true, 'controller' => 'settings', 'action' => 'index'));
	}

/**
 * admin_setRegistration function.
 *
 * @access public
 * @param mixed $value (default: null)
 * @return void
 * @throws
 */
	public function admin_setRegistration($value = null) {
		$this->__adminSetField('register_enabled', $value);
	}

	public function admin_setLDAPEnabled($value = null) {
		$this->__adminSetField('ldap_enabled', $value);
	}

	public function admin_setLdapUrl() {
		// Check form value provided and request is a post request
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$ok = Validation::custom($this->request->data['Settings']['ldap_url'], self::$_regex['ldap_url']);

		$this->Setting->id = $this->Setting->field('id', array('name' => 'ldap_url'));
		if ($this->Setting->exists() && $ok) {
			$this->Setting->set('value', $this->request->data['Settings']['ldap_url']);
			$this->Setting->save();
		}
		return $this->redirect(array('admin' => true, 'controller' => 'settings', 'action' => 'index'));
	}

	public function admin_setLdapBaseDN() {
		// Check form value provided and request is a post request
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$ok = Validation::custom($this->request->data['Settings']['ldap_base_dn'], self::$_regex['ldap_dn']);
		$this->Setting->id = $this->Setting->field('id', array('name' => 'ldap_base_dn'));
		if ($this->Setting->exists() && $ok) {
			$this->Setting->set('value', $this->request->data['Settings']['ldap_base_dn']);
			$this->Setting->save();
		}
		return $this->redirect(array('admin' => true, 'controller' => 'settings', 'action' => 'index'));
	}

	public function admin_setLdapBindDN() {
		// Check form value provided and request is a post request
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$ok = Validation::custom($this->request->data['Settings']['ldap_bind_dn'], self::$_regex['ldap_dn']);
		$this->Setting->id = $this->Setting->field('id', array('name' => 'ldap_bind_dn'));
		if ($this->Setting->exists() && $ok) {
			$this->Setting->set('value', $this->request->data['Settings']['ldap_bind_dn']);
			$this->Setting->save();
		}
		return $this->redirect(array('admin' => true, 'controller' => 'settings', 'action' => 'index'));
	}

	public function admin_setLdapBindPW() {
		// Check form value provided and request is a post request
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Setting->id = $this->Setting->field('id', array('name' => 'ldap_bind_pw'));
		if ($this->Setting->exists()) {
			$this->Setting->set('value', $this->request->data['Settings']['ldap_bind_pw']);
			$this->Setting->save();
		}
		return $this->redirect(array('admin' => true, 'controller' => 'settings', 'action' => 'index'));
	}

	public function admin_setLdapFilter() {
		// Check form value provided and request is a post request
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Setting->id = $this->Setting->field('id', array('name' => 'ldap_filter'));
		if ($this->Setting->exists()) {
			$this->Setting->set('value', $this->request->data['Settings']['ldap_filter']);
			$this->Setting->save();
		}
		return $this->redirect(array('admin' => true, 'controller' => 'settings', 'action' => 'index'));
	}

/**
 * admin_setFeatureTime function.
 *
 * @access public
 * @param mixed $value (default: null)
 * @return void
 */
	public function admin_setFeatureTime($value = null) {
		$this->__adminSetField('feature_time_enabled', $value);
	}

/**
 * admin_setFeatureTask function.
 *
 * @access public
 * @param mixed $value (default: null)
 * @return void
 */
	public function admin_setFeatureTask($value = null) {
		$this->__adminSetField('feature_task_enabled', $value);
	}

/**
 * admin_setFeatureSource function.
 *
 * @access public
 * @param mixed $value (default: null)
 * @return void
 */
	public function admin_setFeatureSource($value = null) {
		$this->__adminSetField('feature_source_enabled', $value);
	}

/**
 * admin_setFeatureAttachment function.
 *
 * @access public
 * @param mixed $value (default: null)
 * @return void
 */
	public function admin_setFeatureAttachment($value = null) {
		$this->__adminSetField('feature_attachment_enabled', $value);
	}

/**
 * adminSetField function.
 *
 * @access private
 * @param mixed $field (default: null)
 * @param mixed $value (default: null)
 * @return void
 */
	private function __adminSetField($field = null, $value = null) {
		$this->Setting->id = $this->Setting->field('id', array('name' => $field));
		if (!$this->Setting->exists()) {
			$this->Flash->error('The specified setting does not exist in the database. Please create "' . $field . '" and try again.');
		} else if (in_array((int)$value, array(0,1))) {
			$this->Setting->set('value', $value);
			$this->Setting->save();
		} else {
			$this->Flash->error('Cannot set "' . $field . '" to a value other than 1 or 0. Please try again.');
		}
		return $this->redirect(array('admin' => true, 'controller' => 'settings', 'action' => 'index'));
	}
}
