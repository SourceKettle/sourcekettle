<?php

/**
 *
 * AdminController Controller for the SourceKettle system
 * Provides the hard-graft overview of the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	SourceKettle Development Team 2012
 * @link		http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.Controller
 * @since		SourceKettle v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');

class AdminController extends AppController {

	public $useTable = false;

	public $uses = array(
		'Project',
		'ProjectHistory',
		'User',
	);

	public function beforeFilter() {
		parent::beforeFilter();
		$this->set('sidebar', 'admin');
	}
/**
 * index method
 *
 * @return void
 */
	public function admin_index() {

		$this->set('pageTitle', __('Administration'));
		$this->set('subTitle', __('system overview'));

		// TODO move to config
		// Staleness thresholds in days
		$recentThreshold = 7;
		$staleThreshold = 90;
		$deadThreshold = 365;

		// Total number of users and projects
		$numUsers = $this->User->find('count');

		// Number of projects by activity: recent, active, stale, dead
		$projectsByActivity = array(
			'recent' => 0,
			'active' => 0,
			'stale' => 0,
			'dead' => 0,
			'unused' => 0,
		);

		$projects = $this->ProjectHistory->find('all', array(
			'fields' => array('Project.id', 'Project.name', 'datediff(now(), max(date(ProjectHistory.created))) as latest'),
			'group' => array('Project.id'),
			'order' => array('latest DESC'),
		));
		
		$numProjects = 0;
		foreach ($projects as $project) {
			if ($project[0]['latest'] < $recentThreshold) {
				$projectsByActivity['recent']++;
			} elseif($project[0]['latest'] < $staleThreshold) {
				$projectsByActivity['active']++;
			} elseif($project[0]['latest'] < $deadThreshold) {
				$projectsByActivity['stale']++;
			} else {
				$projectsByActivity['dead']++;
			}
			$numProjects++;
		}
		// Anything with no project history is 'unused'
		$projectsByActivity['unused'] = $numProjects - array_sum(array_values($projectsByActivity));

		$this->set(compact('numUsers', 'projectsByActivity'));

	}

	// This is the same as the ode in AppController, but we should make sure
	// that ONLY sysadmins have access, even if we later change the defaults in AppController.
	// So, don't call parent function.
	public function isAuthorized($user) {
		if (@$user['is_admin'] == 1) {
			return true;
		}
		return false;
	}

}
