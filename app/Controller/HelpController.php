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
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __(''));
	}

	public function dashboard() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __(''));
	}

	public function create() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __('how do I create a project?'));
	}

	public function project_list() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __('what do all the project icons mean?'));
	}

	public function overview() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __('the project overview page'));
	}

	public function time() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __('how do I log time?'));
	}

	public function source() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __('source code for your project'));
	}

	public function tasks() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __('how do I manage tasks?'));
		$this->set('statuses', $this->TaskStatus->find('list', array()));
		$this->set('priorities', $this->TaskPriority->find('list', array()));
		$this->set('types', $this->TaskType->find('list', array()));
	}

	public function milestones() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __('how do I manage milestones?'));
	}

	public function attachments() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __('how do I add attachments?'));
	}

	public function collaborators() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __('how do I manage collaborators?'));
	}

	public function settings() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __('how do I change my project settings?'));
	}

	public function details() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __(''));
	}

	public function security() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __(''));
	}

	public function theme() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __(''));
	}

	public function delete() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __(''));
	}

	public function addkey() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __('how do I manage my SSH keys?'));
	}

	public function viewkeys() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __('how do I manage my SSH keys?'));
	}

	public function admin_index() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __(''));
	}

	public function admin_settings() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __('how do I change the system settings?'));
	}

	public function admin_usersearch() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __(''));
	}

	public function admin_useradd() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __(''));
	}

	public function admin_projectsearch() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __(''));
	}

	public function admin_projectadd() {
		$this->set('pageTitle', __('Help!'));
		$this->set('subTitle', __(''));
		$this->render('create');
	}

	private function __test() {
	}

}
