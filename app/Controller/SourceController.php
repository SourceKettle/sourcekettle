<?php
/**
 *
 * Source controller for the SourceKettle system
 * Provides access to the code behind projects
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

App::uses('AppProjectController', 'Controller');

class SourceController extends AppProjectController {

	public $helpers = array('Time', 'Source');

	public $uses = array(
		'Source',
		'Project',
	);

	// Which actions need which authorization levels (read-access, write-access, admin-access)
	protected function _getAuthorizationMapping() {
		return array(
			'commit'  => 'read',
			'commits'  => 'read',
			'gettingStarted'  => 'read',
			'index'  => 'read',
			'raw'  => 'read',
			'tree'  => 'read',
		);
	}

	public function isAuthorized($user) {
		if (!$this->sourcekettle_config['Features']['source_enabled']['value']) {
			if ($this->sourcekettle_config['Features']['source_enabled']['source'] == 'Project-specific settings') {
				throw new ForbiddenException(__('This project does not have source code management enabled. Please contact a project administrator to enable source code management.'));
			} else {
				throw new ForbiddenException(__('This system does not allow source code management. Please contact a system administrator to enable source code management.'));
			}
		}

		return parent::isAuthorized($user);
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
		$project = parent::_getProject($name);
		$this->Source->init();

		$branches = $this->Source->getBranches();
		$tags = $this->Source->getTags();
		if (empty($branches) && $this->request['action'] != 'gettingStarted') {
			return $this->redirect(array('project' => $name, 'controller' => 'source', 'action' => 'gettingStarted'));
		}
		$this->set('branches', $branches);
		$this->set('tags', $tags);
		if ($ref != null) {
			if (!in_array($ref, $branches) && !in_array($ref, $tags) && !$this->Source->Commit->exists($ref)) {
				throw new NotFoundException(__('Invalid Ref'));
			}
			$this->set('branchDetail', $this->Source->Commit->fetch($ref));
		}

		return $project;
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

		if (isset($this->request->query['branch'])) {
			$branch = urldecode($this->request->query['branch']);
		} else {
			$branch = $this->Source->getDefaultBranch();
		}

		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('source code'));
		$project = $this->__initialiseResources($project, $hash);

		if ($hash == null) {
			return $this->redirect(array('action' => 'commits', 'project' => $project['Project']['name'], '?' => array('branch' => $this->Source->getDefaultBranch())));
		}
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

		$this->set("branch", $branch);
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
	public function commits($project = null, $path = null) {

		if (isset($this->request->query['branch'])) {
			$branch = urldecode($this->request->query['branch']);
		} else {
			$branch = "master"; //$this->Source->getDefaultBranch();
		}

		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('source code'));
		$project = $this->__initialiseResources($project, $branch);
		if ($branch == null) {
			return $this->redirect(array('project' => $project['Project']['name'], $this->Source->getDefaultBranch()));
		}

		$numPerPage = 10;

		// Lets make sure its a valid int
		if (isset($this->request->query['page'])) {
			$page = $this->request->query['page'];

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
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('source code'));
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
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('source code'));
		return $this->redirect(array('action' => 'tree', 'project' => $project));
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
	public function raw($project = null, $path = null) {
		$this->layout = 'ajax';

		if (isset($this->request->query['branch'])) {
			$branch = urldecode($this->request->query['branch']);
		} else {
			$branch = "master"; //$this->Source->getDefaultBranch();
		}

		$project = $this->__initialiseResources($project, $branch);
		$blob = $this->Source->Blob->fetch($branch, $path);

		if ($blob['type'] != 'blob') {
			throw new NotFoundException(__('Invalid Location'));
		}

		$finfo = new finfo(FILEINFO_MIME);
		$mime = $finfo->buffer($blob['content']);

		$this->set('mimeType', $mime);
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
	public function tree($project = null, $path = null) {
		if (isset($this->request->query['branch'])) {
			$branch = urldecode($this->request->query['branch']);
		} else {
			$branch = "master"; //$this->Source->getDefaultBranch();
		}

		try {
			$project = $this->__initialiseResources($project, $branch);
		} catch (UnsupportedRepositoryType $e) {
			$this->render('GettingStarted/none');
		}

		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('source code'));

		try {
			$blob = $this->Source->Blob->fetch($branch, $path);
			if (!in_array($blob['type'], array('tree', 'blob'))) {
				throw new NotFoundException(__('Invalid Location'));
			}

			// TODO this should probably go in the GitCake plugin
			if ($blob['type'] == 'blob') {
				$finfo = new finfo(FILEINFO_MIME);
				$blob['mimeType'] = $finfo->buffer($blob['content']);
			}


			$this->set('tree', $blob);
			$this->set("path", $path);
			$this->set("branch", $branch);
		} catch (UnsupportedRepositoryType $e) {
			$this->render('GettingStarted/none');
		}
	}

}
