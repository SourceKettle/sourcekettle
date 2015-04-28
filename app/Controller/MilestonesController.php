<?php
/**
 *
 * MilestonesController Controller for the SourceKettle system
 * Provides the hard-graft control of the Milestones for projects
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
//App::uses('Project', 'Model');

class MilestonesController extends AppProjectController {

	public $helpers = array('Task');

	public $uses = array('Milestone', 'Project');

	// Which actions need which authorization levels (read-access, write-access, admin-access)
	protected function _getAuthorizationMapping() {
		return array(
			'index'  => 'read',
			'open'  => 'read',
			'closed'  => 'read',
			'view'   => 'read',
			'plan'   => 'write',
			'add'   => 'write',
			'edit'   => 'write',
			'close'   => 'write',
			'reopen'   => 'write',
			'burndown' => 'read',
			'delete'   => 'write',
		);
	}

	public function isAuthorized($user) {
		if (!$this->sourcekettle_config['Features']['task_enabled']['value']) {
			if ($this->sourcekettle_config['Features']['task_enabled']['source'] == "Project-specific settings") {
				throw new ForbiddenException(__('This project does not have task tracking enabled. Please contact a project administrator to enable task tracking.'));
			} else {
				throw new ForbiddenException(__('This system does not allow task tracking. Please contact a system administrator to enable task tracking.'));
			}
		}

		return parent::isAuthorized($user);
	}

/**
 * index method
 *
 * @return void
 */
	public function index($project = null) {
		return $this->redirect(array('project' => $project, 'action' => 'open'));
	}

/**
 * index method
 *
 * @return void
 */
	public function open($project = null) {
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('open milestones'));
		$project = $this->_getProject($project);
		$milestones = $this->Milestone->getOpenMilestones();
		$this->set('milestones', $milestones);
		$this->render('open_closed');
	}

/**
 * index method
 *
 * @return void
 */
	public function closed($project = null) {
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('closed milestones'));
		$project = $this->_getProject($project);
		$milestones = $this->Milestone->getClosedMilestones();
		$this->set('milestones', $milestones);
		$this->render('open_closed');
	}

/**
 * view method
 *
 * @return void
 */
	public function view($project = null, $id = null) {
		$project = $this->_getProject($project);
		$milestone = $this->Milestone->open($id);

		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('Milestone board: "%s"', $milestone['Milestone']['subject']));

		$open = $this->Milestone->tasksOfStatusForMilestone($id, 'open');
		$inProgress = $this->Milestone->tasksOfStatusForMilestone($id, 'in progress');
		if ($this->sourcekettle_config['Features']['4col_kanban_enabled']['value']) {
			$resolved = $this->Milestone->tasksOfStatusForMilestone($id, 'resolved');
			$closed = $this->Milestone->tasksOfStatusForMilestone($id, 'closed');
			$this->set('closed', $closed);
			
		} else {
			$resolved = $this->Milestone->tasksOfStatusForMilestone($id, array('resolved', 'closed'));
			$closed = array();
		}
		$dropped = $this->Milestone->tasksOfStatusForMilestone($id, 'dropped');

		// Final value is min size of the board
		$max = max(count($open), count($inProgress), count($resolved), 3);
		if ($this->sourcekettle_config['Features']['4col_kanban_enabled']['value']) {
			$max = max($max, count($closed));
		}

		// Calculate number of points complete/total for the milestone
		$points_total = 0;
		foreach ($milestone['Tasks'] as $k => $v){
			if ($k == 'dropped') continue;
			$points_total += $v['points'];
		}
		$points_todo = $milestone['Tasks']['in progress']['points'] + $milestone['Tasks']['open']['points'];
		$points_complete = $points_total - $points_todo;

		$this->set('milestone', $milestone);
		$this->set(compact('open', 'inProgress', 'resolved', 'dropped', 'points_complete', 'points_todo', 'points_total'));

		if ($this->sourcekettle_config['Features']['story_enabled']['value']) {
			$stories = $this->Milestone->storiesForMilestone($milestone['Milestone']['id']);
			$this->set('stories', $stories);
		}
	}

/**
 * plan method
 *
 * @return void
 */
	public function plan($project = null, $id = null) {
		$project = $this->_getProject($project);
		$milestone = $this->Milestone->open($id);

		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('Priority planner: "%s"', $milestone['Milestone']['subject']));

		$mustHave   = $this->Milestone->tasksOfPriorityForMilestone($id, 'blocker');
		$shouldHave = $this->Milestone->tasksOfPriorityForMilestone($id, 'urgent');
		$couldHave  = $this->Milestone->tasksOfPriorityForMilestone($id, 'major');
		$mightHave  = $this->Milestone->tasksOfPriorityForMilestone($id, 'minor');

		$this->Project->id = $project['Project']['id'];
		$wontHave   = $this->Project->getProjectBacklog();

		$this->set('milestone', $milestone);

		$this->set(compact('mustHave', 'shouldHave', 'couldHave', 'mightHave', 'wontHave'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($project = null) {
		$project = $this->_getProject($project);
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('add a milestone'));

		if ($this->request->is('get') && isset($this->request->query['Task'])) {
			$this->request->data['Task'] = array_values(array_filter($this->request->query['Task'], function($a) {return ($a != 0);}));
		}

		if ($this->request->is('post')) {
			$this->Milestone->create();

			$data = $this->_cleanPost(array("Milestone.subject", "Milestone.description", "Milestone.starts", "Milestone.due"));
			$data['Milestone']['project_id'] = $project['Project']['id'];

			// Force new milestones into the 'open' state, this makes the most sense...
			$data['Milestone']['is_open'] = true;

			// TODO indent levels of doom
			if ($this->Flash->c($this->Milestone->save($data))) {
				if (isset($this->request->data['Task'])) {
					foreach ($this->request->data['Task'] as $publicId) {
						if (!is_numeric($publicId)) {
							continue;
						}
						$task = $this->Milestone->Task->findByProjectIdAndPublicId($project['Project']['id'], $publicId);
						if (!$task) {
							continue;
						}
						$this->Milestone->Task->id = $task['Task']['id'];
						$this->Milestone->Task->saveField('milestone_id', $this->Milestone->id);
					}
				}
				return $this->redirect(array('project' => $project['Project']['name'], 'action' => 'plan', $this->Milestone->id));
			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($project = null, $id = null) {
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('edit milestone'));
		$project = $this->_getProject($project);
		$milestone = $this->Milestone->open($id);

		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->_cleanPost(array("Milestone.subject", "Milestone.description", "Milestone.starts", "Milestone.due"));
			$data['Milestone']['project_id'] = $project['Project']['id'];
			if (isset($id)) {
				$this->Milestone->contain(array());
				$existing = $this->Milestone->findById($id);
				$data['Milestone'] = array_merge($existing['Milestone'], $data['Milestone']);
			}
			$saved = $this->Milestone->save($data);
			if ($this->request->is('ajax')) {
				$this->layout = 'ajax';
				
				if ($saved) {
					$this->request->data = $saved;
					$this->request->data['message'] = 'no_error';
				} else {
					$this->response->statusCode(500);
					$this->request->data = array('error' => 500, 'message' => __('Save failed'));
				}
				$this->set('data', $this->request->data);
				$this->render('/Elements/json');
				return;
			}

			if ($this->Flash->u($saved)) {
				return $this->redirect(array('project' => $project['Project']['name'], 'action' => 'view', $id));
			}
		} else {
			$this->request->data = $milestone;
		}
		$this->set('milestone', $milestone);
	}

/**
 * close method
 *
 * @param string $id
 * @return void
 */
	public function close($project = null, $id = null) {
		$project = $this->_getProject($project);
		$milestone = $this->Milestone->open($id);
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('close milestone'));
		if (!$milestone['Milestone']['is_open']) {
			throw new NotFoundException(__("Cannot close milestone - it is already closed!"));
		}

		if ($this->request->is('post') || $this->request->is('put')) {

			if (!isset($this->request->data['Milestone']['new_milestone'])) {
				$newMilestone = 0;
			} else {
				$newMilestone = $this->request->data['Milestone']['new_milestone'];
			}

			// Manual transactions used here for good reason:
			// saving all related stuff fails, as we're changing the milestone_id
			// i.e. making it no longer related. So, let's do it this way.
			$dataSource = $this->Milestone->getDataSource();
			$dataSource->begin();

			// First attempt to shift the tasks to the new milestone ID
			if (!$this->Flash->u($this->Milestone->shiftTasks($id, $newMilestone))) {
				$dataSource->rollback();

			// Now update the milestone status itself
			} else {
				$milestone = $this->Milestone->open($id);
				$milestone['Milestone']['is_open'] = 0;
				if (!$this->Flash->u($this->Milestone->save($milestone))) {
					$dataSource->rollback();
				} else {
					$dataSource->commit();
					return $this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
				}
			}

		} else {
			$this->request->data = $milestone;
		}

		// For the form, build a list of other open milestones we can attach tasks to
		$otherMilestones = array(0 => '(no milestone)');
		foreach ($this->Milestone->getOpenMilestones() as $m) {
			$otherMilestones[$m['Milestone']['id']] = $m['Milestone']['subject'];
		}

		unset($otherMilestones[$id]);
		ksort($otherMilestones);

		$this->set('other_milestones', $otherMilestones);
		$this->set('milestone', $milestone);
		$this->set('name', $milestone['Milestone']['subject']);
	}

/**
 * reopen method
 *
 * @param string $id
 * @return void
 */
	public function reopen($project = null, $id = null) {
		$project = $this->_getProject($project);
		$milestone = $this->Milestone->open($id);
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('re-open milestone'));

		if($milestone['Milestone']['is_open']){
			throw new NotFoundException(__("Cannot re-open milestone - it is already open!"));
		}

		if ($this->request->is('post') || $this->request->is('put')) {

			$milestone = $this->Milestone->open($id);
			$milestone['Milestone']['is_open'] = 1;

			if ($this->Flash->u($this->Milestone->save($milestone))) {
				return $this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
			}

		} else {
			$this->request->data = $milestone;
		}
		$this->set('milestone', $milestone);
		$this->set('name', $milestone['Milestone']['subject']);
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($project = null, $id = null) {

		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('delete milestone'));
		$project = $this->_getProject($project);
		$milestone = $this->Milestone->open($id);

		if ($this->request->is('post')) {

			$newMilestone = @$this->request->data['Milestone']['new_milestone'];

			$dataSource = $this->Milestone->getDataSource();
			$dataSource->begin();

			// First attempt to shift the tasks to the new milestone ID
			if (!$this->Flash->u($this->Milestone->shiftTasks($id, $newMilestone, true))) {
				$dataSource->rollback();

			// Now delete the milestone.
			} else {
				$milestone = $this->Milestone->open($id);
				if (!$this->Flash->d($this->Milestone->delete())) {
					$dataSource->rollback();
				} else {
					$dataSource->commit();
					return $this->redirect(array('project' => $project['Project']['name'], 'action' => 'index'));
				}
			}

		} else {
			$this->request->data = $milestone;
		}

		// For the form, build a list of other open milestones we can attach tasks to
		$otherMilestones = array(0 => '(no milestone)');
		foreach ($this->Milestone->getOpenMilestones() as $m) {
			$otherMilestones[$m['Milestone']['id']] = $m['Milestone']['subject'];
		}
		unset($otherMilestones[$id]);
		ksort($otherMilestones);

		$this->set('other_milestones', $otherMilestones);
		$this->set('milestone', $milestone);
		$this->set('name', $milestone['Milestone']['subject']);
	}

	public function burndown($project = null, $id = null) {

		$milestone = $this->Milestone->open($id);
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('burn-down chart: "%s"', $milestone['Milestone']['subject']));

		$now = new DateTime();

		// Start date: provided in GET or POST data, or use the milestone creation date
		if (isset($this->request->query['start'])) {
			$start = new DateTime($this->request->query['start']);
		} elseif (isset($this->request->data['start'])) {
			$start = new DateTime($this->request->data['start']);
		} elseif (isset($milestone['Milestone']['starts']) && $milestone['Milestone']['starts'] != '0000-00-00' ) {
			$start = new DateTime($milestone['Milestone']['starts']);
		} else {
			$start = new DateTime($milestone['Milestone']['created']);
		}

		// End date: provided in GET or POST data, or use the due date if it's in the future,
		// finally falling back to the current date
		if (isset($this->request->query['end'])) {
			$end = new DateTime($this->request->query['end']);
		} elseif (isset($this->request->data['end'])) {
			$end = new DateTime($this->request->data['end']);
		} elseif (isset($milestone['Milestone']['due']) ) {
			$end = new DateTime($milestone['Milestone']['due']);

			// If the milestone is late (i.e. still open), render up to the current date
			if ($milestone['Milestone']['is_open'] && $end < $now) {
				$end = $now;
			}
		} else {
			$end = $now;
		}


		// Find logged changes between the start and end dates
		$log = $this->Milestone->fetchBurndownLog($id, $start, $end);

		$this->set(compact('milestone', 'log'));
	}

}
