<?php

/**
 *
 * ProjectsController Controller for the DevTrack system
 * Provides the hard-graft control of the projects contained within the system
 * Including CRUD, admin CRUD and API control
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

class ProjectsController extends AppProjectController {

/**
 * Project helpers
 * @var type
 */
	public $helpers = array('Time', 'GoogleChart.GoogleChart', 'Paginator', 'Markitup');

	public $uses = array('Project', 'RepoType');

/**
 * beforeFilter function.
 *
 * @access public
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(
			'api_all',
			'api_view'
		);
	}

	public function history($project) {
		$project = $this->_projectCheck($project);

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
			'conditions' => array('Collaborator.user_id' => User::get('id')),
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
				$this->redirect(array('action' => 'view', $project['Project']['id']));
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
		$project = $this->_projectCheck($name);

		$numberOfOpenTasks = $this->Project->Task->find('count', array('conditions' => array('Task.task_status_id <' => 4, 'Task.project_id' => $project['Project']['id'])));
		$numberOfClosedTasks = $this->Project->Task->find('count', array('conditions' => array('Task.task_status_id' => 4, 'Task.project_id' => $project['Project']['id'])));
		$numberOfTasks = $numberOfClosedTasks + $numberOfOpenTasks;

		$percentOfTasks = 0;
		if ($numberOfTasks > 0) {
			$percentOfTasks = round($numberOfClosedTasks / $numberOfTasks * 100, 1);
		}

		$openMilestones = $this->Project->Milestone->getOpenMilestones();
		$openMilestones = $this->Project->Milestone->find('all', array('conditions' => array('Milestone.id' => array_values($openMilestones)), 'order' => 'Milestone.created ASC'));
		if (empty($openMilestones)) {
			$milestone = null;
		} else {
			$milestone = $openMilestones[0];
		}

		$numCollab = count($this->Project->Collaborator->findAllByProjectId($project['Project']['id']));

		$this->set(compact('milestone', 'numberOfOpenTasks', 'numberOfClosedTasks', 'numberOfTasks', 'percentOfTasks', 'numCollab'));
		$this->set('historyCount', 8);
	}

/**
 * admin_view method
 *
 * @param string $id
 * @throws NotFoundException
 * @return void
 */
	public function admin_view($project = null) {

		// Check for valid project name
		$project = $this->Project->getProject($project);
		if ( empty($project)) {
			throw new NotFoundException(__('Invalid project'));
		}

		$this->Project->recursive = 2;
		$this->Project->id = $project['Project']['id'];
		$this->request->data = $this->Project->read();
	}

/**
 * add
 * Create a new project for the current user
 *
 * @return void
 */
	public function add() {
		$repoTypes = $this->Project->RepoType->find('list');

		if ($this->request->is('post')) {

			// Create the project object with its data
			$this->Project->create();

			// Lets vet the data coming in
			$requestData = array(
				'Project' => $this->request->data['Project'],
				'Collaborator' => array(
					array(
						'user_id' => User::get('id'),
						'access_level' => 2 // Project admin
					)
				)
			);

			if ($this->Project->saveAll($requestData)) {
				// Need to know the repo type so we can skip repo creation if necessary...
				$repoType = $repoTypes[$requestData['Project']['repo_type']];

				$this->log("[ProjectController.add] project[" . $this->Project->id . "] added by user[" . User::get('id') . "]", 'devtrack');
				$this->log("[ProjectController.add] user[" . User::get('id') . "] added to project[" . $this->Project->id . "] automatically as an admin", 'devtrack');

				// Create the actual repository, if required - if it fails, delete the database content
				if (strtolower($repoType) == 'none') {
					$this->log("[ProjectController.add] project[" . $this->Project->id . "] does not require a repository", 'devtrack');
				} elseif (!$this->Project->Source->create()) {
					$this->log("[ProjectController.add] project[" . $this->Project->id . "] repository creation failed - automatically removing project data", 'devtrack');
					$this->Project->delete();
					$this->Flash->c(false);
				} else {
					$this->Flash->c(true);
				}

				$this->redirect(array('project' => $requestData['Project']['name'], 'action' => 'view'));
			} else {
				$this->Flash->c(false);
			}
		}

		$this->set(compact('repoTypes'));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Project->create();
			if ($this->Flash->c($this->Project->save($this->request->data))) {
				$this->redirect(array('action' => 'index'));
			}
		}
		$this->set('repoTypes', $this->Project->RepoType->find('list'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($project = null) {
		$project = $this->_projectCheck($project, true, true);

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Flash->u($this->Project->save($this->request->data))) {
				$this->log("[ProjectController.edit] user[" . User::get('id') . "] edited project[" . $this->Project->id . "]", 'devtrack');
				$this->redirect(array('project' => $project['Project']['name'], 'action' => 'view'));
			}
		}
		$this->request->data = $project;
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($project = null) {
		$project = $this->_projectCheck($project);

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Flash->u($this->Project->save($this->request->data))) {
				$this->redirect(array('action' => 'admin_view', $this->Project->id));
			}
		}
		$this->request->data = $project;
		$this->set('repoTypes', $this->Project->RepoType->find('list'));
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
		$project = $this->_projectCheck($project, true, true);

		$this->Flash->setUp();

		if ($this->request->is('post')) {
			if ($this->Flash->d($this->Project->delete(), $project['Project']['name'])) {
				$this->redirect(array('action' => 'index'));
			}
		}
		$this->set('object', array('name' => $project['Project']['name']));
		$this->set('objects', $this->Project->preDelete());
		$this->render('/Elements/Project/delete');
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @throws MethodNotAllowedException
 * @return void
 */
	public function admin_delete($name = null) {
		$project = $this->_projectCheck($name);

		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}

		$this->Flash->d($this->Project->delete(), $project['Project']['name']);
		$this->redirect(array('action' => 'admin_index'));
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
			$project = $this->_projectCheck($this->request->params['named']['project']);
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
			&& strlen($this->request->query['query']) > 1) {

			$query = $this->request->query['query'];
			$projects = $this->Project->find(
				"all",
				array(
					'conditions' => array(
						'OR' => array(
							'Project.name LIKE' => $query . '%',
							'Project.description LIKE' => $query . '%'
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
