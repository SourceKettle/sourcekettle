<?php
App::uses('AppProjectController', 'Controller');
/**
 * Stories Controller
 *
 * @property Story $Story
 * @property PaginatorComponent $Paginator
 */
class StoriesController extends AppProjectController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	// Which actions need which authorization levels (read-access, write-access, admin-access)
	protected function _getAuthorizationMapping() {

		return array(
			'index'  => 'read',
			'view'   => 'read',
			'add'   => 'write',
			'edit'   => 'write',
			'delete'   => 'write',
		);
	}
/**
 * index method
 *
 * @return void
 */
	public function index($project = null) {
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('User stories'));
		$project = $this->_getProject($project);
		$this->Story->contain(array(
			'Project' => array(
				'name',
			),
			'Task' => array(
				'id', 'public_id', 'subject', 'story_points', 'story_id', 'milestone_id',
				'TaskStatus' => array('id', 'name'),
				'TaskType' => array('id', 'name'),
				'TaskPriority' => array('id', 'name'),
			),
		));

		$this->set('stories', $this->Story->find("all", array(
			"conditions" => array("Story.project_id" => $project['Project']['id']),
			"order" => array("id"),
		)));


		$this->set('milestones', $this->Story->Project->Milestone->find("all", array(
			"conditions" => array("is_open" => true),
			"fields" => array("id", "subject", "starts", "due"),
			"contain" => array('Task' => array(
				'id', 'public_id', 'subject', 'story_points', 'story_id', 'milestone_id',
				'TaskStatus' => array('id', 'name'),
				'TaskType' => array('id', 'name'),
				'TaskPriority' => array('id', 'name'),
				'Milestone' => array(
					'subject', 'id', 'starts', 'due', 'is_open',
					'order' => array('starts', 'due'),
				),
			)),
			"order" => array("starts", "due"),
		)));

	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($project = null, $id = null) {
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('User stories'));
		$project = $this->_getProject($project);
		$this->Story->contain(array('Project' => array('name'), 'Creator' => array('name', 'email', 'id')));
		$story = $this->Story->findByProjectIdAndPublicId($project['Project']['id'], $id);
		if (!$story) {
			throw new NotFoundException(__('Invalid story'));
		}
		$this->set('story', $story);
	}

/**
 * add method
 *
 * @return void
 */
	public function add($project = null) {
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('User stories'));
		$project = $this->_getProject($project);
		if ($this->request->is('post')) {
			$this->Story->create();
			$data = $this->_cleanPost(array("Story.subject", "Story.description"));
			$data['Story']['project_id'] = $project['Project']['id'];
			$data['Story']['creator_id'] = $this->Auth->user('id');
			$saved = $this->Story->save($data);
			if ($saved) {
				$saved = $this->Story->findById($saved['Story']['id']);
				$this->Flash->info (__('The story \'<a href="%s">%s</a>\' has been created.', Router::url(array('action' => 'view', 'project' => $project['Project']['name'], $saved['Story']['public_id'])), $saved['Story']['subject']));
				return $this->redirect(array('project' => $project['Project']['name'], 'action' => 'add'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($project = null, $id = null) {

		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('User stories'));
		$project = $this->_getProject($project);
		$story = $this->Story->findByProjectIdAndPublicId($project['Project']['id'], $id);
		$this->Story->id = $story['Story']['id'];
		if (!$story) {
			throw new NotFoundException(__('Invalid story'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$data = $this->_cleanPost(array("Story.subject", "Story.description"));
			$saved = $this->Story->save($data);
			if ($saved) {
				$saved = $this->Story->findByProjectIdAndPublicId($project['Project']['id'], $id);
				$this->Flash->info (__('The story \'<a href="%s">%s</a>\' has been updated.', Router::url(array('action' => 'view', 'project' => $project['Project']['name'], $saved['Story']['public_id'])), $saved['Story']['subject']));
				return $this->redirect(array('project' => $project['Project']['name'], 'action' => 'view', $id));
			}
		} else {
			$options = array('conditions' => array('Story.' . $this->Story->primaryKey => $id));
			$this->request->data = $this->Story->find('first', $options);
		}
		return $this->render("add");
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($project = null, $id = null) {
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('User stories'));
		$project = $this->_getProject($project);
		$story = $this->Story->findByProjectIdAndPublicId($project['Project']['id'], $id);
		if (!$story) {
			throw new NotFoundException(__('Invalid story'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Story->delete()) {
			return $this->flash(__('The story has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The story could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}

}
