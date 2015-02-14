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

/**
 * index method
 *
 * @return void
 */
	public function index($project = null) {
		$project = $this->_getProject($project);
		$this->Story->contain(array('Project' => array('name')));
		$conditions = array("Story.project_id" => $project['Project']['id']);
		$this->set('stories', $this->paginate($this->Story, $conditions));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($project = null, $id = null) {
		$project = $this->_getProject($project);
		if (!$this->Story->exists($id)) {
			throw new NotFoundException(__('Invalid story'));
		}
		$this->Story->contain(array('Project' => array('name'), 'Creator' => array('name', 'email', 'id')));
		$this->set('story', $this->Story->findByProjectIdAndPublicId($project['Project']['id'], $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($project = null) {
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
	public function edit($id = null) {
		if (!$this->Story->exists($id)) {
			throw new NotFoundException(__('Invalid story'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Story->save($this->request->data)) {
				return $this->flash(__('The story has been saved.'), array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('Story.' . $this->Story->primaryKey => $id));
			$this->request->data = $this->Story->find('first', $options);
		}
		$creators = $this->Story->Creator->find('list');
		$this->set(compact('creators'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Story->id = $id;
		if (!$this->Story->exists()) {
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
