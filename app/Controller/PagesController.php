<?php
/**
 *
 * PagesController for the SourceKettle system
 * Controller for static pages
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
class PagesController extends AppController {

	public $name = 'Pages';

	public $uses = array();

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(); //does not require login to use all actions in this controller
	}

	public function display() {
		$path = func_get_args();
		$count = count($path);
		if (!$count) {
			return $this->redirect('/');
		}
		$page = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'title_for_layout'));
		$this->render(implode('/', $path));
	}

	public function home() {
		if ($this->Auth->loggedIn()) {
			return $this->redirect('/dashboard');
		}
	}
}
