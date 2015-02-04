<?php

/**
 *
 * ProjectsController Controller for the SourceKettle system
 * Provides the hard-graft control of the projects contained within the system
 * Including CRUD, admin CRUD and API control
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

class ProjectsController extends AppProjectController {

/**
 * Project helpers
 * @var type
 */
	public $helpers = array('Time', 'Paginator', 'Markitup', 'History');

	public $uses = array('Project', 'RepoType', 'Team', 'GroupCollaboratingTeam');

	public $components = array("Paginator");

	// Which actions need which authorization levels (read-access, write-access, admin-access)
	protected function _getAuthorizationMapping() {
		return array(
			'history'  => 'read',
			'index'  => 'login',
			'team_projects'  => 'login',
			'public_projects'  => 'login',
			'view'   => 'read',
			'add' => 'login',
			'fork' => 'read',
			'edit'   => 'admin',
			'add_repo'   => 'admin',
			'delete' => 'write',
			'schedule' => 'read',
			'markupPreview'  => 'read',
			'changeSetting' => 'admin',
			'api_history' => 'read',
			'api_list_collaborators' => 'read',
			'api_list_milestones' => 'read',
			'api_autocomplete' => 'login',
		);
	}

	public function history($project) {
		$project = $this->_getProject($project);

		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __("project history"));
		$this->set('historyCount', 25);
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->set('pageTitle', __("My Projects"));
		$this->set('subTitle', __("all the projects you care about"));

		$this->Paginator->settings = array(
			'conditions' => array('Collaborator.user_id' => $this->Auth->user('id')),
			'joins' => array(
			 	array('table' => 'collaborators',
						'alias' => 'Collaborator',
						'type' => 'INNER',
						'conditions' => array(
							'Collaborator.project_id = Project.id',
						)
					),
			),
			'limit' => 15,
			'order' => array('Project.modified DESC')
		);
		$projects = $this->paginate('Project');
		$this->set('projects', $projects);
	}

	public function team_projects($page = 1) {

		$this->set('pageTitle', __("Team Projects"));
		$this->set('subTitle', __("we do what we must because we can"));

		// Teams the user is a member of
		$teams = $this->Team->TeamsUser->find('list', array(
			'conditions' => array(
				'user_id' => $this->Auth->user('id'),
			),
			'fields' => array('team_id'),
		));
		$teams = array_values($teams);

		// Nothing to do if they're not in any teams...
		if (empty($teams)) {
			$this->set('projects', array());
			return $this->render("index");
		}

		// Project groups the user's teams have access to
		$teamProjectGroups = $this->GroupCollaboratingTeam->find('list', array(
			'conditions' => array(
				'GroupCollaboratingTeam.team_id' => $teams,
			),
			'fields' => array('GroupCollaboratingTeam.project_group_id'),
		));

		$teamProjectGroups = array_values($teamProjectGroups);

		if (empty($teamProjectGroups)) {
			$conditions = array(
				'CollaboratingTeam.team_id' => $teams,
			);
		} else {
			$conditions = array(
				'OR' => array(
					'CollaboratingTeam.team_id' => $teams,
					'ProjectGroupsProject.project_group_id' => $teamProjectGroups,
				)
			);
		}

		$this->Paginator->settings = array(
			'conditions' => $conditions,
			'joins' => array(
			 	array('table' => 'collaborating_teams',
					'alias' => 'CollaboratingTeam',
					'type' => 'LEFT',
					'conditions' => array(
						'CollaboratingTeam.project_id = Project.id',
					)
				),
			 	array('table' => 'project_groups_projects',
					'alias' => 'ProjectGroupsProject',
					'type' => 'LEFT',
					'conditions' => array(
						'ProjectGroupsProject.project_id = Project.id',
					)
				),
			),
			'limit' => 15,
			'group' => array('Project.id'),
			'order' => array('Project.modified DESC'),
		);

		$projects = $this->paginate('Project');
		$this->set('projects', $projects);
		return $this->render("index");
	}

	public function public_projects() {
		$this->set('pageTitle', __("Public Projects"));
		$this->set('subTitle', __("projects people have shared"));

		$this->Paginator->settings = array(
			'conditions' => array(
				'Project.public' => true,
			),
			'limit' => 15,
			'order' => 'Project.modified DESC'
		);
		$projects = $this->paginate('Project');

		$this->set('projects', $projects);
		return $this->render("index");
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->set('pageTitle', __("Administration"));
		$this->set('subTitle', __("da vinci code locator"));
		$data = $this->_cleanPost(array("Project.name"));
		if ($this->request->is('post') && isset($data['Project']['name']) && $project = $data['Project']['name']) {
			if ($project = $this->Project->findByName($project)) {
				return $this->redirect(array(
					'controller' => 'projects',
					'action' => 'view',
					'admin' => false,
					'project' => $project['Project']['name']
				));
			} else {
				$this->Flash->error('The specified Project does not exist. Please try again.');
			}
		}
		$this->Project->recursive = 0;
		$this->set('projects', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($name = null) {

		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __("project overview"));

		$project = $this->_getProject($name);

		$numberOfOpenTasks = $this->Project->Task->find('count', array(
			'conditions' => array(
				'Task.project_id' => $project['Project']['id'],
				'TaskStatus.name' => array('open')
			)
		));
		$numberOfInProgressTasks = $this->Project->Task->find('count', array(
			'conditions' => array(
				'Task.project_id' => $project['Project']['id'],
				'TaskStatus.name' => array('in progress')
			)
		));
		$numberOfClosedTasks = $this->Project->Task->find('count', array(
			'conditions' => array(
				'Task.project_id' => $project['Project']['id'],
				'TaskStatus.name' => array('resolved', 'closed')
			)
		));
		$numberOfDroppedTasks = $this->Project->Task->find('count', array(
			'conditions' => array(
				'Task.project_id' => $project['Project']['id'],
				'TaskStatus.name' => array('dropped')
			)
		));
		$numberOfTasks = $numberOfClosedTasks + $numberOfInProgressTasks + $numberOfOpenTasks + $numberOfDroppedTasks;

		$percentOfTasks = 0;
		if ($numberOfTasks > 0) {
			$percentOfTasks = round($numberOfClosedTasks / $numberOfTasks * 100, 1);
		}

		$openMilestones = $this->Project->Milestone->getOpenMilestones();
		if (empty($openMilestones)) {
			$milestone = null;
		} else {
			$milestone = $openMilestones[0];
		}

		$numCollab = count($this->Project->Collaborator->findAllByProjectId($project['Project']['id']));

		$this->set(compact('milestone', 'numberOfOpenTasks', 'numberOfInProgressTasks', 'numberOfClosedTasks', 'numberOfDroppedTasks', 'numberOfTasks', 'percentOfTasks', 'numCollab'));
		$this->set('historyCount', 8);
	}

/**
 * add
 * Create a new project for the current user
 *
 * @return void
 */
	public function add() {
		$this->set('pageTitle', __('New project'));
		$this->set('subTitle', __('where baby projects are made'));

		$repoTypes = $this->Project->RepoType->find('list');
		$current_user = $this->viewVars['current_user'];

		// Flip keys for values, then look up the ID of the default repo type name
		$defaultRepo = array_flip($repoTypes);
		if (isset($this->sourcekettle_config['SourceRepository']['default']['value'])) {
			$d = $this->sourcekettle_config['SourceRepository']['default']['value'];
		} else {
			$d = 'None';
		}

		if (array_key_exists($d, $defaultRepo)) {
			$defaultRepo = $defaultRepo[$d];
		} else {
			$defaultRepo = $defaultRepo['None'];
		}

		if ($this->request->is('post')) {

			// Create the project object with its data
			$this->Project->create();

			// Lets vet the data coming in
			$data = $this->_cleanPost(array("Project.name", "Project.description", "Project.repo_type", "Project.public"));
			$requestData = array(
				'Project' => $data['Project'],
				'Collaborator' => array(
					array(
						'user_id' => $current_user['id'],
						'access_level' => 2 // Project admin
					)
				)
			);

			if ($this->Project->saveAll($requestData)) {
				// Need to know the repo type so we can skip repo creation if necessary...
				$repoType = $repoTypes[$requestData['Project']['repo_type']];


				$this->log("[ProjectController.add] project[" . $this->Project->id . "] added by user[" . $current_user['id'] . "]", 'sourcekettle');
				$this->log("[ProjectController.add] user[" . $current_user['id'] . "] added to project[" . $this->Project->id . "] automatically as an admin", 'sourcekettle');

				// Create the actual repository, if required - if it fails, delete the database content
				if (strtolower($repoType) == 'none') {
					$this->log("[ProjectController.add] project[" . $this->Project->id . "] does not require a repository", 'sourcekettle');
				} elseif (!$this->Project->Source->create()) {
					$this->log("[ProjectController.add] project[" . $this->Project->id . "] repository creation failed - automatically removing project data", 'sourcekettle');
					$this->Project->delete();
					$this->Flash->c(false);
				} else {
					$this->Flash->c(true);
				}

				return $this->redirect(array('project' => $requestData['Project']['name'], 'action' => 'view'));
			} else {
				$this->Flash->c(false);
			}
		}

		$this->set(compact('repoTypes', 'defaultRepo'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($project = null) {
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __("project settings"));

		$project = $this->_getProject($project);
		$repoNone = $this->RepoType->nameToId('None');
		$this->set('noRepo',  ($project['Project']['repo_type'] == $repoNone));
		$current_user = $this->Auth->user();

		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->_cleanPost(array("Project.name", "Project.description", "Project.repo_type", "Project.public"));
			$saved = $this->Project->save($data);
			if ($this->Flash->u($saved)) {
				$this->log("[ProjectController.edit] user[" . $current_user['id'] . "] edited project[" . $this->Project->id . "]", 'sourcekettle');
				return $this->redirect(array('project' => $this->Project->field('name'), 'action' => '*'));
			}
		}
		$this->request->data = $project;
	}

	// NB this is called "clone" in the interface, but "clone" is a reserved word in PHP...
	// TODO lots of copypasta from the add method, which should be consolidated in the Project model
	public function fork($project = null) {
		$this->set('pageTitle', __("Clone project"));
		$this->set('subTitle', __("stand on the shoulders of giants"));
		$project = $this->_getProject($project);

		// Check the project has a git repo
		if ($this->RepoType->idToName($project['Project']['repo_type']) != 'git') {
			throw new Exception(__("Cannot clone %s: it does not have a git repository", $project['Project']['name']));
		}

		// Create the new project and redirect to it
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->Project->create();

			// Lets vet the data coming in
			$data = $this->_cleanPost(array("Project.name", "Project.description", "Project.repo_type", "Project.public"));
			$requestData = array(
				'Project' => $data['Project'],
				'Collaborator' => array(
					array(
						'user_id' => $this->Auth->user('id'),
						'access_level' => 2 // Project admin
					)
				)
			);

			$requestData['Project']['repo_type'] = $project['Project']['repo_type'];

			if ($this->Project->saveAll($requestData)) {

				// Create the actual repository, if required - if it fails, delete the database content
				if (!$this->Project->Source->create(array('cloneFrom' => $requestData['Project']['cloneFrom']))) {
					$this->log("[ProjectController.add] project[" . $this->Project->id . "] repository creation failed - automatically removing project data", 'sourcekettle');
					$this->Project->delete();
					$this->Flash->c(false);
				} else {
					$this->Flash->c(true);
				}

				return $this->redirect(array('project' => $requestData['Project']['name'], 'action' => 'view'));
			} else {
				$this->Flash->c(false);
			}
		}

		// Add in the "cloneFrom" field if it's a fresh request
		if (empty($this->request->data)) {
			$this->request->data = $project;
			$this->request->data['Project']['cloneFrom'] = $project['Project']['name'];
		}

		// Don't retain the original project name and ID!
		unset($this->request->data['Project']['name']);
		unset($this->request->data['Project']['id']);
	}

/**
 * admin_rename method
 * Allows site administrators to rename a project and its repository. Not to be used lightly.
 *
 * @param string $id
 * @return void
 */
	public function admin_rename($project = null) {
		$this->set('pageTitle', __('Administration'));
		$this->set('subTitle', __("...they called it *what*??"));
		$project = $this->_getProject($project);
		$current_user = $this->Auth->user();

		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->_cleanPost(array("Project.name", "Project.description", "Project.repo_type", "Project.public"));
			$saved = $this->Project->rename($this->Project->id, $data['Project']['name']);
			if ($this->Flash->u($saved)) {
				$this->log("[ProjectController.rename] user[" . $current_user['id'] . "] renamed project[" . $this->Project->id . "]", 'sourcekettle');
				return $this->redirect(array('project' => $this->Project->id, 'action' => 'view', 'admin' => false));
			}
		}
		$this->request->data = $project;
	}
/**
 * add_repo method
 * Used to create a repository for a project that was created without one.
 *
 * @param string $id
 * @return void
 */
	public function add_repo($project = null) {
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __("add a repository"));
		$project = $this->_getProject($project);

		$repoTypes = $this->Project->RepoType->find('list');
		$this->set('repoTypes', $repoTypes);

		if ($repoTypes[$project['Project']['repo_type']] != 'None') {
			throw new NotFoundException("This project already has a repository. If you need to change this, contact your system administrator.");
		}
		$current_user = $this->Auth->user();


		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->_cleanPost(array("Project.name", "Project.description", "Project.repo_type", "Project.public"));
			// TODO transactions for great justice, this is just lazy
			$saved = $this->Project->save($data);
			if ($this->Flash->u($saved)) {
				$this->Project->Source->create();
				$this->log("[ProjectController.add_repo] user[" . $current_user['id'] . "] added a repository to project[" . $this->Project->id . "]", 'sourcekettle');
				return $this->redirect(array('project' => $this->Project->id, 'action' => 'view'));
			}
		}
		$this->request->data = $project;
	}

/**
 * delete
 * remove a project and its sub components from the database
 *
 * @param string $project
 *
 * @return void
 */
	public function delete($project = null) {
		$this->set('pageTitle', __('Are you sure you want to delete "%s"?', $this->request['project']));
		$project = $this->_getProject($project);

		$this->Flash->setUp();

		if ($this->request->is('post')) {
			if ($this->Flash->d($this->Project->delete(), $project['Project']['name'])) {
				return $this->redirect(array('action' => 'index'));
			}
		}
		$this->set('object', array('name' => $project['Project']['name']));
		$this->set('objects', $this->Project->preDelete());
		$this->render('/Elements/Project/delete');
	}

	public function schedule($project = null) {

		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __("milestone schedule"));
		$project = $this->_getProject($project);

		$milestones = $this->Project->Milestone->find('all', array(
			'conditions' => array(
				'project_id' => $project['Project']['id'],
			),
			'order' => array(
				'Milestone.starts',
			),
			'recursive' => -1,
		));

		$this->set(compact('project', 'milestones'));
	}

	public function changeSetting($project) {
		if (!$this->request->is('post')) {
			return $this->redirect(array('project' => $project, 'action' => 'edit'));
		}

		$code = 200;
		$message = __("Settings updated.");
		// Note that we don't do much cleaning here, saveSettingsTree already checks for known settings
		$data = array('ProjectSetting' => @$this->request->data['ProjectSetting']);
		if (!$this->Project->ProjectSetting->saveSettingsTree($project, $data)) {
			$code = 500;
			$message = __("Failed to change settings");
		}

		if ($this->request->is('ajax')) {
			$this->set('data', array('code' => $code, 'message' => $message));
			$this->render('/Elements/json');
			return;
		} elseif($code == 200) {
			$this->Flash->info(__('Settings updated.'));
		} else {
			$this->Flash->error(__('There was a problem updating the settings.'));
		}
		return $this->redirect (array ('action' => 'index'));
	}

	/* ************************************************ *
	*													*
	*			API SECTION OF CONTROLLER				*
	*			 CAUTION: PUBLIC FACING					*
	*													*
	* ************************************************* */

/**
 * api_history function.
 * not strictly an API call, consider moving to ajax routing
 *
 * @access public
 * @param int $number (default: 0)
 * @return void
 */
	public function api_history($project = null, $number = 0) {
		if (!isset($project)  || !$project) {
			$this->response->statusCode(400);
			$data['error'] = 400;
			$data['message'] = 'Bad request, no project specified.';

			$this->layout = 'ajax';
			$this->set('data',$data);
			$this->render('/Elements/json');
		} else {
			$this->layout = 'ajax';

			if (!is_numeric($number) || $number < 1 || $number > 50) {
				$number = 8;
			}

			$this->set('events', $this->Project->fetchEventsForProject($project, $number));
			$this->render('/Elements/history');
		}
	}

/**
 * api_autocomplete function.
 *
 * @access public
 * @return void
 */
	public function api_autocomplete() {
		$this->layout = 'ajax';

		$data = array('projects' => array());

		if (isset($this->request->query['query'])
			&& $this->request->query['query'] != null
			&& strlen($this->request->query['query']) > 0) {

			$query = strtolower($this->request->query['query']);

			// At 3 characters, start matching anywhere within the name
			if(strlen($query) > 2){
				$query = "%$query%";
			} else {
				$query = "$query%";
			}

			$projects = $this->Project->find(
				"all",
				array(
					'conditions' => array(
						'OR' => array(
							'LOWER(Project.name) LIKE' => $query,
							'LOWER(Project.description) LIKE' => $query
						),
					),
					'fields' => array(
						'Project.name'
					)
				)
			);
			foreach ($projects as $project) {
				$data['projects'][] = $project['Project']['name'];
			}

		}
		$this->set('data',$data);
		$this->render('/Elements/json');
	}

	public function api_list_collaborators($project = null) {
			
		if (!isset($project)  || !$project) {
			$this->response->statusCode(400);
			$data['error'] = 400;
			$data['message'] = 'Bad request, no project specified.';

			$this->layout = 'ajax';
			$this->set('data',$data);
			$this->render('/Elements/json');
			return;
		}

		$project = $this->_getProject($project);
		$this->set('data', $this->Project->listCollaborators($project['Project']['id']));
		$this->render('/Elements/json');
	}

	public function api_list_milestones($project = null) {
			
		if (!isset($project)  || !$project) {
			$this->response->statusCode(400);
			$data['error'] = 400;
			$data['message'] = 'Bad request, no project specified.';

			$this->layout = 'ajax';
			$this->set('data',$data);
			$this->render('/Elements/json');
			return;
		}

		$project = $this->_getProject($project);
		$this->set('data', $this->Project->listMilestones($project['Project']['id']));
		$this->render('/Elements/json');
	}
}
