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
	public $helpers = array('Time', 'Paginator', 'Markitup');

	public $uses = array('Project', 'RepoType');

	// Which actions need which authorization levels (read-access, write-access, admin-access)
	protected function _getAuthorizationMapping() {
		return array(
			'history'  => 'read',
			'index'  => 'login',
			'public_projects'  => 'login',
			'view'   => 'read',
			'add' => 'login',
			'edit'   => 'write',
			'add_repo'   => 'admin',
			'delete' => 'write',
			'markupPreview'  => 'read',
			'api_history' => 'read',
			'api_autocomplete' => 'read',
		);
	}

	public function history($project) {
		$project = $this->_getProject($project);

		$this->set('historyCount', 25);
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Project->Collaborator->recursive = 0;
		$projects = $this->Project->Collaborator->find(
			'all', array(
			'conditions' => array('Collaborator.user_id' => $this->Auth->user('id')),
			'order' => array('Project.modified DESC')
			)
		);
		$this->set('projects', $projects);
	}

	public function public_projects() {
		$this->Project->recursive = -1;
		$this->paginate = array(
			'conditions' => array('Project.public' => true),
			'limit' => 15,
			'order' => 'Project.modified DESC'
		);
		$projects = $this->paginate('Project');

		$this->set('projects', $projects);
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		if ($this->request->is('post') && isset($this->request->data['Project']['name']) && $project = $this->request->data['Project']['name']) {
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
		$repoTypes = $this->Project->RepoType->find('list');
		$current_user = $this->viewVars['current_user'];

		// Flip keys for values, then look up the ID of the default repo type name
		$defaultRepo = array_flip($repoTypes);
		if (isset($this->sourcekettle_config['repo']['default'])) {
			$d = $this->sourcekettle_config['repo']['default'];
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
			$requestData = array(
				'Project' => $this->request->data['Project'],
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
		$project = $this->_getProject($project);
		$repoNone = $this->RepoType->nameToId('None');
		$this->set('noRepo',  ($project['Project']['repo_type'] == $repoNone));
		$current_user = $this->Auth->user();

		if ($this->request->is('post') || $this->request->is('put')) {
			$saved = $this->Project->save($this->request->data);
			if ($this->Flash->u($saved)) {
				$this->log("[ProjectController.edit] user[" . $current_user['id'] . "] edited project[" . $this->Project->id . "]", 'sourcekettle');
				return $this->redirect(array('project' => $this->Project->field('name'), 'action' => '*'));
			}
		}
		$this->request->data = $project;
	}

/**
 * admin_rename method
 * Allows site administrators to rename a project and its repository. Not to be used lightly.
 *
 * @param string $id
 * @return void
 */
	public function admin_rename($project = null) {
		$project = $this->_getProject($project);
		$current_user = $this->Auth->user();

		if ($this->request->is('post') || $this->request->is('put')) {
			$saved = $this->Project->rename($this->Project->id, $this->request->data['Project']['name']);
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
		$project = $this->_getProject($project);

		$repoTypes = $this->Project->RepoType->find('list');
		$this->set('repoTypes', $repoTypes);

		if ($repoTypes[$project['Project']['repo_type']] != 'None') {
			throw new NotFoundException("This project already has a repository. If you need to change this, contact your system administrator.");
		}
		$current_user = $this->Auth->user();


		if ($this->request->is('post') || $this->request->is('put')) {
			// TODO transactions for great justice, this is just lazy
			$saved = $this->Project->save($this->request->data);
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

	public function markupPreview() {
		$this->layout = 'ajax';
		$content = '';
		if (isset($this->request->query['data'])) {
			$content = $this->request->query['data'];
		}
		$this->set(compact('content'));
		$this->render('/Elements/Markitup/preview');
	}

	public function burndown($project = null) {

		$project = $this->_getProject($project);

		$now = new DateTime();

		// Start date: provided in GET or POST data, or use the project creation date
		if (isset($this->request->query['start'])) {
			$start = new DateTime($this->request->query['start']);
		} elseif (isset($this->request->data['start'])) {
			$start = new DateTime($this->request->data['start']);
		} else {
			$start = new DateTime($project['Project']['created']);
		}

		// End date: provided in GET or POST data,
		// falling back to the current date
		if (isset($this->request->query['end'])) {
			$end = new DateTime($this->request->query['end']);
		} elseif (isset($this->request->data['end'])) {
			$end = new DateTime($this->request->data['end']);
		} else {
			$end = $now;
		}

		// Find logged changes between the start and end dates
		$log = $this->Project->ProjectBurndownLog->find('all', array(
			'conditions' => array(
				'project_id' => $project['Project']['id'],
				'timestamp <=' => $end->format('Y-m-d 23:59:59'),
				'timestamp >=' => $start->format('Y-m-d 00:00:00'),
			),
			'fields' => array(
				'timestamp',
				'open_task_count',
				'open_minutes_count',
				'open_points_count',
				'closed_task_count',
				'closed_minutes_count',
				'closed_points_count',
			),
			'order' => array('timestamp'),
			'recursive' => -1,
		));

		$log = array_map(function($a){return $a['ProjectBurndownLog'];}, $log);

		$this->set(compact('project', 'log'));

	}
	/* ************************************************ *
	*													*
	*			API SECTION OF CONTROLLER				*
	*			 CAUTION: PUBLIC FACING					*
	*													*
	* ************************************************* */

/**
 * api_view function.
 *
 * @access public
 * @param mixed $id (default: null)
 * @return void
 */
	public function api_view($id = null) {
		$this->layout = 'ajax';

		$this->Project->recursive = -1;
		$data = array();

		if ($id == null) {
			$this->response->statusCode(400);
			$data['error'] = 400;
			$data['message'] = 'Bad request, no project id specified.';
		}

		if ($id == 'all') {
			$this->api_all();
			return;
		}

		if (is_numeric($id)) {
			$this->Project->id = $id;

			if (!$this->Project->exists()) {
				$this->response->statusCode(404);
				$data['error'] = 404;
				$data['message'] = 'No project found of that ID.';
				$data['id'] = $id;
			} else {
				$project = $this->Project->read();

				// Collaborators
				$c = $this->Project->Collaborator->find('list', array('fields' => array('user_id'), 'conditions' => array('project_id' => $id)));
				$project['Project']['collaborators'] = array_values($c);

				// Repo Type
				$project['Project']['repo_type'] = $this->Project->RepoType->field('name');

				$partOfProject = $this->Project->hasRead();
				$publicProject	= $this->Project->field('public');
				$isAdmin = ($this->_apiAuthLevel() == 1);

				if ($publicProject || $partOfProject || $isAdmin) {
					$data = $project['Project'];
				} else {
					$data['error'] = 401;
					$data['message'] = 'Project found, but is not public.';
					$data['id'] = $id;
				}
			}
		}

		$this->set('data',$data);
		$this->render('/Elements/json');
	}

/**
 * api_all function.
 * ADMINS only
 *
 * @access public
 * @return void
 */
	public function api_all() {
		$this->layout = 'ajax';

		$this->Project->recursive = -1;
		$data = array();

		switch ($this->_apiAuthLevel()) {
			case 1:
				foreach ($this->Project->find("all") as $project) {
					// Collaborators
					$c = $this->Project->Collaborator->find('list', array('fields' => array('user_id'), 'conditions' => array('project_id' => $project['Project']['id'])));
					$project['Project']['collaborators'] = array_values($c);

					// Repo Type
					$project['Project']['repo_type'] = $this->Project->RepoType->field('name', array('id' => $project['Project']['repo_type']));

					$data[] = $project['Project'];
				}
				break;
			default:
				$this->response->statusCode(403);
				$data['error'] = 403;
				$data['message'] = 'You are not authorised to access this.';
		}

		$this->set('data',$data);
		$this->render('/Elements/json');
	}

/**
 * api_history function.
 * not strictly an API call, consider moving to ajax routing
 *
 * @access public
 * @param int $number (default: 0)
 * @return void
 */
	public function api_history($number = 0) {
		if (!isset($this->request->params['named']['project'])) {
			$this->response->statusCode(400);
			$data['error'] = 400;
			$data['message'] = 'Bad request, no project id specified.';

			$this->layout = 'ajax';
			$this->set('data',$data);
			$this->render('/Elements/json');
		} else {
			$project = $this->_getProject($this->request->params['named']['project']);
			$this->layout = 'ajax';

			if (!is_numeric($number) || $number < 1 || $number > 50) {
				$number = 8;
			}
			$this->set('events', $this->Project->fetchEventsForProject($number));
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

		$data = array('users' => array());

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
				$data['users'][] = $project['Project']['name'];
			}

		}
		$this->set('data',$data);
		$this->render('/Elements/json');
	}
}
