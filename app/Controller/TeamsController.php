<?php
App::uses('AppController', 'Controller');
class TeamsController extends AppController {
	public $components = array('Paginator');

    public $paginate = array(
        'limit' => 25,
        'order' => array(
            'Team.name' => 'asc'
        ),
		'fields' => array(
			'Team.name',
			'Team.description',
		),
    );

	public function view($team = null) {
		if (!is_numeric($team)) {
			$team = $this->Team->field('id', array('name' => $team));
		}
		$team = $this->Team->findById($team);

		if (empty($team)) {
			throw new NotFoundException(__('Invalid team'));
		}

		// TODO this should really be picked up by the model...
		foreach ($team['CollaboratingTeam'] as $i => $ct) {
			$team['CollaboratingTeam'][$i]['project_name'] = $this->Team->CollaboratingTeam->Project->field('name', array('id' => $ct['project_id']));
			$team['CollaboratingTeam'][$i]['access_level'] = $this->Team->CollaboratingTeam->Project->Collaborator->accessLevelIdToName($ct['access_level']);
		}

		foreach ($team['GroupCollaboratingTeam'] as $i => $ct) {
			$team['GroupCollaboratingTeam'][$i]['project_group_name'] = $this->Team->CollaboratingTeam->Project->ProjectGroup->field('name', array('id' => $ct['project_group_id']));
			$team['GroupCollaboratingTeam'][$i]['access_level'] = $this->Team->CollaboratingTeam->Project->Collaborator->accessLevelIdToName($ct['access_level']);
		}

		$this->set(compact('team'));

	}

	public function admin_index() {
		$this->Paginator->settings = $this->paginate;
		$teams = $this->Paginator->paginate('Team');
		$this->set('teams', $teams);
	}

	// No special admin permission needed to view teams
	public function admin_view($team = null) {
		return $this->redirect(array('action' => 'view', 'admin' => false));
	}

	public function admin_add() {
		
		if ($this->request->is('post')) {
			$this->Team->create();
			if ($this->Team->save($this->request->data)) {
				$this->Session->setFlash(__('The team has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The team could not be saved. Please try again.'));
			}
		}
		$users = $this->Team->User->find('list');
		$this->set(compact('users'));
		
	}

	public function admin_edit($id = null) {
		if (!$this->Team->exists($id)) {
			throw new NotFoundException(__('Invalid team'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Team->save($this->request->data)) {
				$this->Session->setFlash(__('The team has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The team could not be saved. Please try again.'));
			}
		} else {
			$options = array('conditions' => array('Team.' . $this->Team->primaryKey => $id));
			$this->request->data = $this->Team->find('first', $options);
		}
		$users = $this->Team->User->find('list');
		$this->set(compact('users'));
		
	}
	public function admin_delete($id = null) {
		$this->Team->id = $id;
		if (!$this->Team->exists()) {
			throw new NotFoundException(__('Invalid team'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Team->delete()) {
			$this->Session->setFlash(__('Team deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Team was not deleted'));
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
		$data = array('teams' => array());

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

			$teams = $this->Team->find(
				"all",
				array(
					'conditions' => array(
						'OR' => array(
							'LOWER(Team.name) LIKE' => $query,
							'LOWER(Team.description) LIKE' => $query,
						)
					),
					'fields' => array(
						'Team.name',
						'Team.description',
					)
				)
			);
			foreach ($teams as $team) {
				$data['teams'][] = $team['Team']['name'] . " [" . $team['Team']['description'] . "]";
			}

		}
		$this->set('data', $data);
		$this->render('/Elements/json');
	}
}
