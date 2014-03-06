<?php
/**
 *
 * SetupController for the DevTrack system
 * Controller for setup page
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 DevTrack Development Team 2012
 * @link			http://github.com/SourceKettle/devtrack
 * @package		DevTrack.Controller
 * @since		 DevTrack v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

class SetupController extends AppController {

	public $name = 'Setup';

	public $uses = array('ConnectionManager', 'Debugger', 'Validation');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(); //does not require login to use all actions in this controller
	}

	/**
	 * The main setup page.
	 */
	public function index() {
		// Find our config base
		// TODO handle windows case

		// The Application base if we are running only a local configuration.
		$app_cfg_base = APP . 'Config' . DS;

		// The Application base if we have created /etc/sourcekettle.
		$linux_cfg_base = DS . 'etc' . DS . 'sourcekettle' . DS;

		if (file_exists($linux_cfg_base)) {
			$configdir = $linux_cfg_base;
			$globalConfigFile = $linux_cfg_base . 'global.php';
		} else {
			$configdir = $app_cfg_base;
			$globalConfigFile = $app_cfg_base . 'global.php';
		}
		$this->set('configdir', $configdir);
		$this->set('globalConfigFile', $globalConfigFile);

		// Variable should be true if all checks complete OK
		$complete = true;

		// Check apache to see if mod_rewrite is enabled
		if (!in_array('mod_rewrite', apache_get_modules())) {
			$this->set('modRewriteExists' , false);
			$complete = false;
		} else {
			$this->set('modRewriteExists' , true);
		}

		// Check Cipher Settings
		if (Configure::read('Security.salt') == 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi') {
			$this->set('securitySaltChanged' , false);
			$complete = false;
		} else {
			$this->set('securitySaltChanged' , true);
		}
		if (Configure::read('Security.cipherSeed') === '76859309657453542496749683645') {
			$this->set('securityCipherChanged' , false);
			$complete = false;
		} else {
			$this->set('securityCipherChanged' , true);
		}

		// Check that PHP is up to scratch
		if (version_compare(PHP_VERSION, '5.2.8', '>=')) {
			$this->set('phpVersionCheck' , true);
		} else {
			$this->set('phpVersionCheck' , false);
			$complete = false;
		}

		// Check that the cache config is OK
		$settings = Cache::settings();
		if (!empty($settings)) {
			$this->set('cacheConfigSet' , true);
			$this->set('cacheConfigEngine', $settings['engine']);
		} else {
			$this->set('cacheConfigSet' , false);
			$complete = false;
		}

		// Check that TMP is writable
		if (is_writable(TMP)) {
			$this->set('tmpWritable' , true);
		} else {
			$this->set('tmpWritable' , false);
			$complete = false;
		}

		if (Validation::alphaNumeric('cakephp')) {
			$this->set('unicodeSupport' , true);
		} else {
			$this->set('unicodeSupport' , false);
			$complete = false;
		}

		if (file_exists($configdir . 'database.php')) {
			$this->set('databaseConfigPresent' , true);
			try {
				$connected = ConnectionManager::getDataSource('default');
			} catch (Exception $connectionError) {
				$connected = false;
			}
			if ($connected && $connected->isConnected()) {
				$this->set('databaseConnected' , true);
				$tables = ConnectionManager::getDataSource('default')->listSources();
				if (empty($tables)) {
					$this->set('databaseTables', false);
					$complete = false;
				} else {
					$this->set('databaseTables', true);
				}
			} else {
				$this->set('databaseConnected' , false);
				$this->set('databaseConnectionIssue', $connectionError->getMessage());
				$complete = false;
			}
		} else {
			$this->set('databaseConfigPresent' , false);
			$complete = false;
		}

		// Check GIT
		$config = Configure::read('devtrack');
		if (isset($config['repo']['user']) && !empty($config['repo']['user'])){
			$this->set('gitUserSet' , true);
			$this->set('gitUser', $config['repo']['user']);
		} else {
			$this->set('gitUserSet' , false);
			$complete = false;
		}

		if (isset($config['repo']['base']) && !empty($config['repo']['base'])){
			$this->set('gitRepoSet' , true);
			$this->set('gitRepoSetBase', $config['repo']['base']);
			if (is_writable($config['repo']['base'])) {
				$this->set('gitRepoWritable' , true);
			} else {
				$this->set('gitRepoWritable' , false);
				$complete = false;
			}
			if (is_readable($config['repo']['base'])){
				$this->set('gitRepoReadable' , true);
			} else {
				$this->set('gitRepoReadable' , false);
				$complete = false;
			}
		} else {
			$this->set('gitRepoSet' , false);
			$complete = false;
		}

		$this->set('complete', $complete);
	}

}
