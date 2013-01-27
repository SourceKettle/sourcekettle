<?php
/**
*
* LoginController for the DevTrack system
* The controller to allow users to login and logout
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

class LoginController extends AppController{

	public $name = 'Login';

	public $uses = array('User');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	/**
	 * The login function.
	 * Allows users to login using their username and password.
	 *
	 * @access public
	 * @return void
	 */
	public function index(){

		// If they're already logged in, bounce them to the homepage
		if($this->Auth->loggedIn()) $this->redirect($this->Auth->redirect());

		// Only POST requests are authenticated
		if (!$this->request->is('post')) return;

		// First, try to authenticate them
		if(!$this->Auth->login()){
			$this->log("[LoginController.index] Authentication failed using ".$this->request->data['User']['email'], 'devtrack');
			$this->Flash->error($this->Auth->loginError);
			return;
		}

		$this->log("[LoginController.index] Authentication succeeded for ".$this->request->data['User']['email'], 'devtrack');

		// Authentication succeeded - load the user object they logged in with
		$user = $this->User->findById($this->Auth->user('id'));

		// Check if the user has activated their account
		if (!$user['User']['is_active']){
			$this->log("[LoginController.index] user[".$user['User']['id']."] denied access - account is not activated", 'devtrack');
			$this->Flash->error($this->Auth->loginError);
			return;
		}

		// Authentication successful, everybody is happy! Let's log it to celebrate.
		$this->log("[LoginController.index] user[".$user['User']['id']."] logged in", 'devtrack');
		$this->redirect($this->Auth->redirect());
	}

	/**
	 * The logout function.
	 * Allows users to logout of DevTrack.
	 *
	 * @access public
	 * @return void
	 */
	public function logout(){
		$this->Auth->logout();
		$this->redirect('/');
	}

}
