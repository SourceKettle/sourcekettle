<?php
/**
 *
 * Source controller for the DevTrack system
 * Provides access to the code behind projects
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

App::uses('AppProjectController', 'Controller');

class SourceController extends AppProjectController {

	public $helpers = array('Time', 'Source');

/**
 * __getPath function.
 *
 * @access private
 * @return void
 */
	private function __getPath() {
		$route = $this->params['pass'];
		unset($route[0]); // Project name
		unset($route[1]); // Branch name
		return implode('/',$route);
	}

/**
 * __initialiseResources function.
 *
 * @access private
 * @param mixed $name
 * @throws NotFoundException
 * @return void
 */
	private function __initialiseResources($name, $ref = null) {
		$project = parent::_projectCheck($name);
		$this->Source->init();

		$branches = $this->Source->getBranches();
		if (empty($branches) && $this->request['action'] != 'gettingStarted') {
			$this->redirect(array('project' => $name, 'controller' => 'source', 'action' => 'gettingStarted'));
		}
		$this->set('branches', $branches);

		if ($ref != null) {
			if (!in_array($ref, $branches) && !$this->Source->Commit->exists($ref)) {
				throw new NotFoundException(__('Invalid Ref'));
			}
			$this->set('branchDetail', $this->Source->Commit->fetch($ref));
		}

		return $project;
	}

/**
 * ajax_diff function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @throws NotFoundException
 * @return void
 */
	public function ajax_diff($project = null) {
		$this->layout = 'ajax';

		if ($project == null && isset($this->request->params['named'])) {
			$project = $this->request->params['named'];
		}
		$project = $this->__initialiseResources($project);

		if (!isset($this->request->data['file']) || !isset($this->request->data['parent']) || !isset($this->request->data['hash'])) {
			throw new NotFoundException(__('Invalid Parameters'));
		}

		$hash	= $this->request->data['hash'];
		$parent = $this->request->data['parent'];
		$file	= $this->request->data['file'];

		if (($parent != '' && !$this->Source->Commit->exists($parent)) || !$this->Source->Commit->exists($hash)) {
			throw new NotFoundException(__('Invalid HashRefs'));
		}

		$this->set('file', $file);
		$this->set('commit', array('hash' => $hash));
		$this->set('diff', $this->Source->Commit->diff($hash, $parent, $file));

		$this->render('/Elements/Source/commit_changeset_item');
	}

/**
 * commit function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @param mixed $hash (default: null)
 * @return void
 */
	public function commit($project = null, $hash = null) {
		$project = $this->__initialiseResources($project, $hash);

		$commit = $this->Source->Commit->fetch($hash);

		$maxDiffSize = 20;
		$lazyLoad = false;
		$commit['diff'] = array();

		if (count($commit['changeset']) > $maxDiffSize) {
			$lazyLoad = false;
		} else {
			$maxDiffSize = count($commit['changeset']);
		}
		for ($i = 0; $i < $maxDiffSize; $i++) {
			$file = $commit['changeset'][$i];
			$commit['diff'][$file] = $this->Source->Commit->diff($commit['hash'], $commit['parent'], $file);
		}

		$this->set("branch", null);
		$this->set("commit", $commit);
		$this->set("lazyLoad", $lazyLoad);
	}

/**
 * commits function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @param mixed $branch (default: null)
 * @return void
 */
	public function commits($project = null, $branch = null) {
		$project = $this->__initialiseResources($project, $branch);
		$path	= $this->__getPath();

		if ($branch == null) {
			$this->redirect(array('project' => $project['Project']['name'], 'branch' => $this->Source->getDefaultBranch()));
		}

		$numPerPage = 10;

		// Lets make sure its a valid int
		if (isset($this->params['named']['page'])) {
			$page = $this->params['named']['page'];

			if (!is_numeric($page) || $page < 1 || $page > 1000) {
				$page = 1;
			}
		} else {
			$page = 1;
		}

		foreach ($this->Source->Commit->history($branch, $numPerPage + 1, (($page - 1) * $numPerPage), $path) as $a => $commit) {
			$commits[$a] = $this->Source->Commit->fetch($commit);
		}

		if (count($commits) == $numPerPage + 1) {
			unset($commits[$numPerPage]);
			$this->set("more_pages", true);
		} else {
			$this->set("more_pages", false);
		}

		$this->set("branch", $branch);
		$this->set("commits", $commits);
		$this->set("page", $page);
		$this->set("path", $path);
	}

/**
 * gettingStarted function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @return void
 */
	public function gettingStarted($project = null) {
		$project = $this->__initialiseResources($project);
		$type	= $this->Source->getType();

		$this->set('user', $this->Auth->user());
		if ($type == RepoTypes::GIT) {
			$this->render('GettingStarted/git');
		} else if ($type == RepoTypes::SVN) {
			$this->render('GettingStarted/svn');
		} else {
			$this->render('GettingStarted/none');
		}
	}

/**
 * index function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @return void
 */
	public function index($project = null) {
		$this->redirect(array('action' => 'tree', 'project' => $project));
	}

/**
 * raw function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @param mixed $branch (default: null)
 * @throws NotFoundException
 * @return void
 */
	public function raw($project = null, $branch = null) {
		$this->layout = 'ajax';

		$project = $this->__initialiseResources($project, $branch);
		$path	= $this->__getPath();

		if ($branch == null) {
			throw new NotFoundException(__('Invalid Branch'));
		}

		$blob = $this->Source->Blob->fetch($branch, $path);

		if ($blob['type'] != 'blob') {
			throw new NotFoundException(__('Invalid Location'));
		}

		$this->set('sourceFile', $blob['content']);
	}

/**
 * tree function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @param mixed $branch (default: null)
 * @throws NotFoundException
 * @return void
 */
	public function tree($project = null, $branch = null) {
		try {
			$project = $this->__initialiseResources($project, $branch);
			$path	= $this->__getPath();

			if ($branch == null) {
				$this->redirect(array('project' => $project['Project']['name'], 'branch' => $this->Source->getDefaultBranch()));
			}

			$blob = $this->Source->Blob->fetch($branch, $path);

			if (!in_array($blob['type'], array('tree', 'blob'))) {
				throw new NotFoundException(__('Invalid Location'));
			}

			$this->set('tree', $blob);
			$this->set("path", $path);
			$this->set("branch", $branch);
		} catch (UnsupportedRepositoryType $e) {
			$this->render('GettingStarted/none');
		}
	}

}
