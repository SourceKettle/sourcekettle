<?php
/**
 *
 * HelpController for the SourceKettle system
 * The controller to allow users to get help
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

class HelpController extends AppController{

/**
 * The name of the controller
 * @var type String
 */
	public $name = 'Help';
	public $uses = array(
		'TaskType',
		'TaskPriority',
		'TaskStatus',
	);

	// Dashboard - any user can see all actions
	public function isAuthorized($user) {
		if (isset($user['id'])) {
			return true;
		}
		return false;
	}

	public function index() {
	}

	public function dashboard() {
	}

	public function create() {
	}

	public function project_list() {
	}

	public function overview() {
	}

	public function time() {
	}

	public function source() {
	}

	public function tasks() {
		$this->set('statuses', $this->TaskStatus->find('list', array()));
		$this->set('priorities', $this->TaskPriority->find('list', array()));
		$this->set('types', $this->TaskType->find('list', array()));
	}

	public function milestones() {
	}

	public function attachments() {
	}

	public function collaborators() {
	}

	public function settings() {
	}

	public function details() {
	}

	public function security() {
	}

	public function theme() {
	}

	public function delete() {
	}

	public function addkey() {
	}

	public function viewkeys() {
	}

	public function admin_index() {
	}

	public function admin_settings() {
	}

	public function admin_usersearch() {
	}

	public function admin_useradd() {
	}

	public function admin_projectsearch() {
	}

	public function admin_projectadd() {
		$this->render('create');
	}

	private function __test() {
	}

}
