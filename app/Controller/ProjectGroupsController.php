<?php
App::uses('AppController', 'Controller');
/**
 * ProjectGroups Controller
 *
 * @property ProjectGroup $ProjectGroup
 */
class ProjectGroupsController extends AppController {

	public function isAuthorized($user) {

		// No public pages here, must be logged in
		if (empty($user)) {
			return false;
		}

		// Deactivated users explicitly do not get access
		// (NB they should not be able to log in anyway, of course!)
		if (@$user['is_active'] != 1) {
			return false;
		}

		// If you are logged in, you can view project groups and autocomplete project group names
		if ($this->action == 'view' || $this->action == 'api_autocomplete') {
			return true;
		}

		// Sysadmins can do anything...
		if (@$user['is_admin'] == 1) {
			return true;
		}

		// Admins only for all but viewing project groups
		return false;
	}
/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->ProjectGroup->recursive = 0;
		$this->set('projectGroups', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($group = null) {
		if (!is_numeric($group)) {
			$group = $this->ProjectGroup->field('id', array('name' => $group));
		}

		$projectGroup = $this->ProjectGroup->findById($group);

		if (empty($projectGroup)) {
			throw new NotFoundException(__('Invalid group'));
		}

		// TODO should really be pulled in by the model
		foreach ($projectGroup['GroupCollaboratingTeam'] as $i => $ct) {
			$projectGroup['GroupCollaboratingTeam'][$i]['team_name'] = $this->ProjectGroup->GroupCollaboratingTeam->Team->field('name', array('id' => $ct['team_id']));
			$projectGroup['GroupCollaboratingTeam'][$i]['access_level'] = $this->ProjectGroup->Project->Collaborator->accessLevelIdToName($ct['access_level']);
		}
		$this->set(compact('projectGroup'));
	}

	// No special admin permission needed to view teams
	public function admin_view($group = null) {
		return $this->redirect(array('action' => 'view', 'group' => $group, 'admin' => false));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->ProjectGroup->create();
			if ($this->ProjectGroup->save($this->request->data)) {
				$this->Session->setFlash(__('The project group has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project group could not be saved. Please try again.'));
			}
		}
		$members = array();
		$nonMembers = $this->ProjectGroup->Project->find('list');
		$this->set(compact('members', 'nonMembers'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->ProjectGroup->exists($id)) {
			throw new NotFoundException(__('Invalid project group'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->ProjectGroup->save($this->request->data)) {
				$this->Session->setFlash(__('The project group has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project group could not be saved. Please try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectGroup.' . $this->ProjectGroup->primaryKey => $id));
			$this->request->data = $this->ProjectGroup->find('first', $options);
		}

		$members = array();
		$nonMembers = $this->ProjectGroup->Project->find('list');
		foreach ($this->request->data['Project'] as $member) {
			$members[$member['id']] = $nonMembers[$member['id']];
			unset($nonMembers[$member['id']]);
		}
		$this->set(compact('members', 'nonMembers'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->ProjectGroup->id = $id;
		if (!$this->ProjectGroup->exists()) {
			throw new NotFoundException(__('Invalid project group'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProjectGroup->delete()) {
			$this->Session->setFlash(__('Project group deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Project group was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

/**
 * api_autocomplete function.
 *
 * @access public
 * @return void
 */
	public function api_autocomplete() {
		$this->layout = 'ajax';

		$this->User->recursive = -1;
		$data = array('projectGroups' => array());

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

			$project_groups = $this->ProjectGroup->find(
				"all",
				array(
					'conditions' => array(
						'OR' => array(
							'LOWER(ProjectGroup.name) LIKE' => $query,
							'LOWER(ProjectGroup.description) LIKE' => $query,
						)
					),
					'fields' => array(
						'ProjectGroup.name',
						'ProjectGroup.description',
					)
				)
			);
			foreach ($project_groups as $project_group) {
				$data['projectGroups'][] = $project_group['ProjectGroup']['name'] . " [" . $project_group['ProjectGroup']['description'] . "]";
			}

		}
		$this->set('data', $data);
		$this->render('/Elements/json');
	}
}
