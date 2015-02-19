<?php
/**
 *
 * TasksController Controller for the SourceKettle system
 * Provides the hard-graft control of the tasks for users
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
App::uses("Gravatar", "Gravatar");

class TasksController extends AppProjectController {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Time', 'Task');

	public $uses = array(
		'Project',
		'Task',
		'TaskStatus',
		'TaskType', 
		'TaskPriority',
		'User',
		'Milestone',
		'Story',
		'Collaborator',
		'Team',
	);

/**
 * Components
 *
 * @var array
 */
	public $components = array("RequestHandler");

/**
 * beforeFilter function.
 *
 * @access public
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->Security->unlockedActions = array (
			'starttask',
			'stoptask',
			'resolve',
			'unresolve',
			'freeze',
			'api_update'
		);
	}
	// Which actions need which authorization levels (read-access, write-access, admin-access)
	protected function _getAuthorizationMapping() {

		return array(
			'index'  => 'read',
			'others'  => 'read',
			'nobody'  => 'read',
			'all'  => 'read',
			'view'   => 'read',
			'tree'   => 'read',
			'comment' => 'write',
			'deleteComment' => 'write',
			'updateComment' => 'write',
			'edit'   => 'write',
			'add'   => 'write',
			'assign'   => 'write',
			'opentask' => 'write',
			'closetask' => 'write',
			'starttask' => 'write',
			'stoptask' => 'write',
			'resolve' => 'write',
			'unresolve' => 'write',
			'freeze' => 'write',
			'api_all' => 'read',
			'api_view' => 'read',
			'api_update' => 'write',
			'personal_kanban' => 'login',
			'team_kanban' => 'login',
		);
	}

	// Special case for team kanban, requires team membership instead of project collaboration
	public function isAuthorized($user) {

		if (!$this->sourcekettle_config['Features']['task_enabled']['value']) {
			if ($this->sourcekettle_config['Features']['task_enabled']['source'] == "Project-specific settings") {
				throw new ForbiddenException(__('This project does not have task tracking enabled. Please contact a project administrator to enable task tracking.'));
			} else {
				throw new ForbiddenException(__('This system does not allow task tracking. Please contact a system administrator to enable task tracking.'));
			}
		}

		if ($this->action != 'team_kanban') {
			return parent::isAuthorized($user);
		}

		if (!$user['is_active']) {
			return false;
		}

		$teamId = $this->Team->field('id', array('name' => $this->params->params['team']));
		if (empty($teamId) || $teamId < 1) {
			throw new NotFoundException(__("Invalid team"));
		}

		if ($user['is_admin']) {
			return true;
		}

		if (!$this->Team->isMember($teamId, $user['id'])) {
			$this->Auth->authError = __('You must be a team member to view the team kanban chart.');
			return false;
		} else {
			return true;
		}

	}
/**
 * index method
 *
 * @return void
 */
	public function index($project = null) {
		$project = $this->_getProject($project);
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('things to do'));

		// Convert from comma separated to arrays
		$statuses   = preg_split('/\s*,\s*/', trim(@$this->request->query['statuses']),   null, PREG_SPLIT_NO_EMPTY);
		$priorities = preg_split('/\s*,\s*/', trim(@$this->request->query['priorities']), null, PREG_SPLIT_NO_EMPTY);
		$types      = preg_split('/\s*,\s*/', trim(@$this->request->query['types']),      null, PREG_SPLIT_NO_EMPTY);
		$assignees  = preg_split('/\s*,\s*/', trim(@$this->request->query['assignees']),  null, PREG_SPLIT_NO_EMPTY);
		$creators   = preg_split('/\s*,\s*/', trim(@$this->request->query['creators']),   null, PREG_SPLIT_NO_EMPTY);
		$milestones = preg_split('/\s*,\s*/', trim(@$this->request->query['milestones']), null, PREG_SPLIT_NO_EMPTY);

		// Filter out invalid entries
		$selected_statuses   = $this->TaskStatus->filterValid($statuses);
		$selected_priorities = $this->TaskPriority->filterValid($priorities);
		$selected_types      = $this->TaskType->filterValid($types);
		$selected_assignees  = $this->User->filterValid($assignees);
		$selected_creators   = $this->User->filterValid($creators);
		$selected_milestones = $this->Milestone->filterValid($milestones);

		// For the filters: lists of available statuses, priorities etc.
		$milestones    = $this->Project->Milestone->listMilestoneOptions();
		$milestones = array($milestones[0]) + $milestones['Open'] + $milestones['Closed'];
		$statuses      = $this->TaskStatus->find('list', array());
		$priorities    = $this->TaskPriority->find('list', array());
		$types         = $this->TaskType->find('list', array());
		$collaborators = $this->Project->listCollaborators();

		// Combine the data for easily generating URLs for the task filter bar
		// Yes, this is horrible.
		$filter_clear_status = 'priorities='.urlencode(implode(',', array_keys($selected_priorities))).
							   '&types='.urlencode(implode(',', array_keys($selected_types))).
							   '&assignees='.urlencode(implode(',', array_keys($selected_assignees))).
							   '&creators='.urlencode(implode(',', array_keys($selected_creators))).
							   '&milestones='.urlencode(implode(',', array_keys($selected_milestones)));

		$filter_clear_priority = 'statuses='.urlencode(implode(',', array_keys($selected_statuses))).
							   '&types='.urlencode(implode(',', array_keys($selected_types))).
							   '&assignees='.urlencode(implode(',', array_keys($selected_assignees))).
							   '&creators='.urlencode(implode(',', array_keys($selected_creators))).
							   '&milestones='.urlencode(implode(',', array_keys($selected_milestones)));

		$filter_clear_type   = 'statuses='.urlencode(implode(',', array_keys($selected_statuses))).
							   '&priorities='.urlencode(implode(',', array_keys($selected_priorities))).
							   '&assignees='.urlencode(implode(',', array_keys($selected_assignees))).
							   '&creators='.urlencode(implode(',', array_keys($selected_creators))).
							   '&milestones='.urlencode(implode(',', array_keys($selected_milestones)));

		$filter_clear_assignee = 'statuses='.urlencode(implode(',', array_keys($selected_statuses))).
							     '&priorities='.urlencode(implode(',', array_keys($selected_priorities))).
							     '&types='.urlencode(implode(',', array_keys($selected_types))).
							     '&creators='.urlencode(implode(',', array_keys($selected_creators))).
							     '&milestones='.urlencode(implode(',', array_keys($selected_milestones)));

		$filter_clear_creator = 'statuses='.urlencode(implode(',', array_keys($selected_statuses))).
							    '&priorities='.urlencode(implode(',', array_keys($selected_priorities))).
							    '&types='.urlencode(implode(',', array_keys($selected_types))).
							    '&assignees='.urlencode(implode(',', array_keys($selected_assignees))).
							    '&milestones='.urlencode(implode(',', array_keys($selected_milestones)));

		$filter_clear_milestone = 'statuses='.urlencode(implode(',', array_keys($selected_statuses))).
							      '&priorities='.urlencode(implode(',', array_keys($selected_priorities))).
							      '&types='.urlencode(implode(',', array_keys($selected_types))).
							      '&assignees='.urlencode(implode(',', array_keys($selected_assignees))).
							      '&creators='.urlencode(implode(',', array_keys($selected_creators)));

		$filter_urls = array(
			'status'    => array(
				'clear' => $filter_clear_status,
				'all'   => $filter_clear_status.'&statuses=all',
			),
			'priority'  => array(
				'clear' => $filter_clear_priority,
				'all'   => $filter_clear_priority.'&priorities=all',
			),
			'type'      => array(
				'clear' => $filter_clear_type,
				'all'   => $filter_clear_type.'&types=all',
			),
			'assignee'  => array(
				'clear' => $filter_clear_assignee,
				'all'   => $filter_clear_assignee.'&assignees=all',
				'none'  => $filter_clear_assignee.'&assignees=0',
			),
			'creator'   => array(
				'clear' => $filter_clear_creator,
				'all'   => $filter_clear_creator.'&creators=all',
			),
			'milestone' => array(
				'clear' => $filter_clear_milestone,
				'all'   => $filter_clear_milestone.'&milestones=all',
				'none'  => $filter_clear_milestone.'&milestones=0',
			),
		);

		foreach ($statuses as $id => $status) {
			$filter_urls['status'][$id] = $filter_clear_status."&statuses=".urlencode($status);
		}

		foreach ($priorities as $id => $priority) {
			$filter_urls['priority'][$id] = $filter_clear_priority."&priorities=".urlencode($priority);
		}

		foreach ($types as $id => $type) {
			$filter_urls['type'][$id] = $filter_clear_type."&types=".urlencode($type);
		}

		foreach ($collaborators as $id => $assignee) {
			$filter_urls['assignee'][$id] = $filter_clear_assignee."&assignees=".urlencode($id);
		}

		foreach ($collaborators as $id => $creator) {
			$filter_urls['creator'][$id] = $filter_clear_creator."&creators=".urlencode($id);
		}

		foreach ($milestones as $id => $milestone) {
			$filter_urls['milestone'][$id] = $filter_clear_milestone."&milestones=".urlencode($id);
		}

		$this->set('filter_urls', $filter_urls);

		$conditions = array('Task.project_id' => $project['Project']['id']);

		if (!empty($selected_statuses)) {
			$conditions['Task.task_status_id'] = array_keys($selected_statuses);
		}
		if (!empty($selected_priorities)) {
			$conditions['Task.task_priority_id'] = array_keys($selected_priorities);
		}
		if (!empty($selected_types)) {
			$conditions['Task.task_type_id'] = array_keys($selected_types);
		}
		if (!empty($selected_assignees)) {
			$conditions['Task.assignee_id'] = array_keys($selected_assignees);
		}
		if (!empty($selected_creators)) {
			$conditions['Task.owner_id']   = array_keys($selected_creators);
		}
		if (!empty($selected_milestones)) {
			$conditions['Task.milestone_id'] = array_keys($selected_milestones);
		}

		// Load task list based on the filtering rules
		$tasks = $this->Task->find('all', array(
			'conditions' => $conditions,
		));
		
		$this->set('tasks', $tasks);


		$this->set('statuses',      $statuses);
		$this->set('priorities',    $priorities);
		$this->set('types',         $types);
		$this->set('collaborators', $collaborators);
		$this->set('milestones',    $milestones);

		$this->set('selected_statuses',   $selected_statuses);
		$this->set('selected_priorities', $selected_priorities);
		$this->set('selected_types',      $selected_types);
		$this->set('selected_assignees',  $selected_assignees);
		$this->set('selected_creators',   $selected_creators);
		$this->set('selected_milestones', $selected_milestones);


	}

	public function personal_kanban($maxAgeDays=30) {
		$this->set('pageTitle', __("Personal kanban chart for %s", $this->Auth->user('name')));
		$this->set('subTitle', null);

		$open = $this->User->tasksOfStatusForUser($this->Auth->user('id'), 'open');
		$inProgress = $this->User->tasksOfStatusForUser($this->Auth->user('id'), 'in progress');
		$resolved = $this->User->tasksOfStatusForUser($this->Auth->user('id'), array('resolved', 'closed'), $maxAgeDays);

		// Calculate number of points complete/total for the milestone
		$points_todo = array_reduce($open, function($v, $t){return $v + $t['Task']['story_points'];});
		$points_todo = array_reduce($inProgress, function($v, $t){return $v + $t['Task']['story_points'];}, $points_todo);
		$points_complete = array_reduce($resolved, function($v, $t){return $v + $t['Task']['story_points'];});
		$points_total = $points_todo + $points_complete;

		$this->set(compact('open', 'inProgress', 'resolved', 'points_total', 'points_todo', 'points_complete'));

	}

	public function team_kanban($team = null, $maxAgeDays=30) {
		$this->set('pageTitle', __('Team kanban chart: %s', $team));
		$this->set('subTitle', null);

		// NB we check it's valid in the isAuthorized method, so no need to check again
		$team = $this->Team->findByName($team);

		$open = $this->Team->tasksOfStatusForTeam($team['Team']['id'], 'open');
		$inProgress = $this->Team->tasksOfStatusForTeam($team['Team']['id'], 'in progress');
		$resolved = $this->Team->tasksOfStatusForTeam($team['Team']['id'], array('resolved', 'closed'), $maxAgeDays);

		// Calculate number of points complete/total for the milestone
		$points_todo = array_reduce($open, function($v, $t){return $v + $t['Task']['story_points'];});
		$points_todo = array_reduce($inProgress, function($v, $t){return $v + $t['Task']['story_points'];}, $points_todo);
		$points_complete = array_reduce($resolved, function($v, $t){return $v + $t['Task']['story_points'];});
		$points_total = $points_todo + $points_complete;
		$this->set(compact('team', 'open', 'inProgress', 'resolved', 'points_total', 'points_todo', 'points_complete'));

	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($project = null, $public_id = null) {
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('task card and log'));
		$project = $this->_getProject($project);
		$task = $this->Task->open($public_id);
		$current_user = $this->Auth->user();

		$this->set('task', $task);
		$this->request->data = $task;

		// Fetch the changes that will have happened
		$changes	= $this->Task->Project->ProjectHistory->find(
			'all',
			array(
				'conditions' => array(
					'ProjectHistory.row_id' => $this->Task->id,
					'ProjectHistory.row_field !=' => '+',
					'ProjectHistory.model' => 'task'
				)
			)
		);
		$comments = $this->Task->TaskComment->find('all', array('conditions' => array('Task.id' => $this->Task->id)));

		// They are in the wrong format for the sort function - so move the modified field
		foreach ($changes as $x => $change) {
			$changes[$x]['created'] = $change['ProjectHistory']['created'];
		}
		foreach ($comments as $x => $comment) {
			$comments[$x]['created'] = $comment['TaskComment']['created'];
		}

		// Fetch any additional users that may be needed
		$changeUsers = array(0 => array('(Not assigned)', null));
		$this->Task->Assignee->recursive = -1;
		foreach ($changes as $change) {
			if ($change['ProjectHistory']['row_field'] == 'assignee_id') {
				$_old = $change['ProjectHistory']['row_field_old'];
				$_new = $change['ProjectHistory']['row_field_new'];

				if ($_old && !isset($changeUsers[$_old])) {
					$this->Task->Assignee->id = $_old;
					$_temp = $this->Task->Assignee->read();
					if (count($_temp) > 0) {
						$changeUsers[$_old] = array($_temp['Assignee']['name'], $_temp['Assignee']['email']);
					} else {
						$changeUsers[$_old] = array('(Unknown or deleted user)', '');
					}
				}
				if ($_new && !isset($changeUsers[$_new])) {
					$this->Task->Assignee->id = $_new;
					$_temp = $this->Task->Assignee->read();
					if (count($_temp) > 0) {
						$changeUsers[$_new] = array($_temp['Assignee']['name'], $_temp['Assignee']['email']);
					} else {
						$changeUsers[$_new] = array('(Unknown or deleted user)', '');
					}
				}
			}
		}

		// Merge the changes
		$changes = array_merge($changes, $comments);

		// Sort function for events
		// assumes $array{ $array{ 'modified' => 'date' }, ... }
		$cmp = function($a, $b) {
			if (strtotime($a['created']) == strtotime($b['created'])) return 0;
			if (strtotime($a['created']) > strtotime($b['created'])) return 1;
			return -1;
		};

		usort($changes, $cmp);
		$this->set('change_users', $changeUsers);
		$this->set('changes', $changes);

		$times = $this->Task->Time->find(
			'all',
			array(
				'conditions' => array(
					'Time.task_id' => $this->Task->id
				)
			)
		);
		$this->set('times', $times);
		$this->set('totalTime', $this->Task->Time->field('SUM(mins) AS totalMins', array('Time.task_id' => $this->Task->id)));
		$this->set('tasks', $this->Task->fetchLoggableTasks($this->Auth->user('id')));
		$collabs = $this->Task->Project->listCollaborators($project['Project']['id']);
		$collabs[0] = "None";
		ksort($collabs);

		$backlog = $this->Task->find('all', array(
			'conditions' => array('project_id =' => $project['Project']['id'], 'id !=' => $this->Task->id),
			'fields' => array('Task.public_id', 'Task.subject', 'Task.id'),
			'recursive' => -1,
		));
		$availableTasks = array();
		foreach ($backlog as $t) {
			$availableTasks[$t['Task']['public_id']] = "#".$t['Task']['public_id']." ".$t['Task']['subject'];
		}

		$subTasks = array();
		foreach ($task['DependsOn'] as $subTask) {
			$subTasks[$subTask['public_id']] = "#".$subTask['public_id']." ".$subTask['subject'];
			unset($availableTasks[$subTask['public_id']]);
		}
		$this->set('subTasks', $subTasks);

		$parentTasks = array();
		foreach ($task['DependedOnBy'] as $parentTask) {
			$parentTasks[$parentTask['public_id']] = "#".$parentTask['public_id']." ".$parentTask['subject'];
			unset($availableTasks[$parentTask['public_id']]);
		}
		$this->set('parentTasks', $parentTasks);
		$this->set('availableTasks', $availableTasks);
		$this->set('collaborators', $collabs);

	}

	public function assign($project = null, $public_id = null) {
		$project = $this->_getProject($project);
		$task = $this->Task->open($public_id);

		if (!$this->request->is('post') || !isset($this->request->data['Assignee']) || !isset($this->request->data['Assignee']['id'])) {
			return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $public_id));
		}
		
		$assigneeId = $this->request->data['Assignee']['id'];
		if ($assigneeId == 0 || $this->Task->Project->hasWrite($assigneeId)) {
			$this->Task->set('assignee_id', $assigneeId);
			$this->Flash->u($this->Task->save());
		} else {
			$this->Flash->error('The assignee could not be updated. The selected user is not a collaborator!');
		}
	
		unset($this->request->data['Assignee']);
	
		return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $public_id));
	}

/**
 * Post a comment for a task
 */
	public function comment($project = null, $public_id = null) {
		$project = $this->_getProject($project);
		$task = $this->Task->open($public_id);

		// No comment posted, redirect to the task
		if (!$this->request->is('post') || !isset($this->request->data['TaskComment'])) {
			return $this->redirect(array ('project' => $project['Project']['name'], 'action' => 'view', $public_id));
		}

		$this->Task->TaskComment->create();
		$data = $this->_cleanPost(array("TaskComment.comment"));

		$data['TaskComment']['task_id'] = $this->Task->id;
		$data['TaskComment']['user_id'] = $this->Auth->user('id');

		// NB do not add a flash message, as they'll see the new task has been added
		if ($this->Task->TaskComment->save($data)) {
			unset($this->request->data['TaskComment']);
		} else {
			$this->Flash->error(__('The comment could not be saved. Please try again.'));
		}

		return $this->redirect(array ('controller' => 'tasks', 'project' => $project['Project']['name'], 'action' => 'view', $public_id));

	}

/**
 * Remove a comment from a task
 */
	public function deleteComment($project = null, $id = null) {

		$project = $this->_getProject($project);
		$comment = $this->Task->TaskComment->findById($id);

		if (!$comment) {
			throw new NotFoundException(__("Cannot find a comment with ID $id"));
		}

		$taskId = $comment['Task']['public_id'];

		// Check that we own the comment or we are a project or system admin...
		if (
			$comment['User']['id'] != $this->Auth->user('id')
			&& !$this->Project->hasWrite($this->Auth->user('id'))
			&& !$this->Auth->user('is_admin')
		) {
			$this->Flash->error (__('You don\'t have permission to delete that comment'));
			return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $taskId));
		}


		if (!$this->request->is('post') || !isset($this->request->data['TaskCommentDelete'])) {
			return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $taskId));
		}


		if ($this->Task->TaskComment->delete($id)) {
			$this->Flash->info (__('The comment has been deleted successfully.'));
		} else {
			$this->Flash->error (__('The comment could not be deleted. Please try again.'));
		}

		return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $taskId));
	}

/**
 * Change an existing comment's text
 */
	public function updateComment ($project = null, $id = null) {

		$project = $this->_getProject($project);
		$comment = $this->Task->TaskComment->findById($id);

		if (!$comment) {
			throw new NotFoundException(__("Cannot find a comment with ID $id"));
		}

		$taskId = $comment['Task']['public_id'];
		// Only the comment owner can edit the comment
		if ($comment['User']['id'] != $this->Auth->user('id')) {
			$this->Flash->error (__('You don\'t have permission to edit that comment'));
			return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $taskId));
		}

		// Check we have new POST data for the comment...
		// TODO change form etc. to not contain TaskCommentEdit, wtf
		if (!$this->request->is('post') || !isset($this->request->data['TaskCommentEdit'])) {
			return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $taskId));
		}
		$data = array('TaskComment' => array(
			'comment' => $this->request->data['TaskCommentEdit']['comment'],
			'id' => $id,
		));

		if ($this->Task->TaskComment->save($data)) {
			$this->Flash->info(__('The comment has been updated successfully'));
			unset($this->request->data['TaskComment']);
		} else {
			$this->Flash->error(__('The comment could not be updated. Please try again.'));
		}

		return $this->redirect (array ('project' => $project['Project']['name'], 'action' => 'view', $taskId));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($project = null) {

		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('create a task'));
		$project = $this->_getProject($project);
		$current_user = $this->viewVars['current_user'];

		// Milestone pre-selected - parse and store
		if (!empty($this->request->query['milestone'])) {
			$selected_milestone_id = preg_replace('/[^\d]/', '', $this->request->query['milestone']);
		} else {
			$selected_milestone_id = 0;
		}

		// Ditto for user story
		if (!empty($this->request->query['story'])) {
			$selected_story_id = preg_replace('/[^\d]/', '', $this->request->query['story']);
		} else {
			$selected_story_id = 0;
		}

		// Pre-selected priority
		$selectedPriority = 0;
		if (!empty($this->request->query['priority'])) {
			$selectedPriority = $this->TaskPriority->nameToID($this->request->query['priority']);
		} elseif (isset($this->request->data['Task']['task_priority_id'])) {
			$selectedPriority = $this->TaskPriority->nameToID($this->request->data['Task']['task_priority_id']);
		}

		if ($this->request->is('ajax') || $this->request->is('post')) {
			$this->Task->create();
			$data = $this->_cleanPost(array("Task.subject", "Task.description", "Task.task_priority_id", "Task.task_status_id", "Task.milestone_id", "Task.story_id", "Task.task_type_id", "Task.assignee_id", "Task.time_estimate", "Task.story_points", "Task.story_id"));
			$data['DependsOn'] = @$this->request->data['DependsOn'];
			$data['DependedOnBy'] = @$this->request->data['DependedOnBy'];
			if (!isset($data['Task'])) {
				$data['Task'] = array();
			}

			$data['Task']['project_id']	= $project['Project']['id'];
			$data['Task']['owner_id']	= $current_user['id'];
			$data['Task']['task_status_id']	= 1;

			if (isset($data['Task']['milestone_id']) && $data['Task']['milestone_id'] == 0) {
				$data['Task']['milestone_id'] = null;
			}
			if (isset($data['Task']['story_id']) && $data['Task']['story_id'] == 0) {
				$data['Task']['story_id'] = null;
			}
			if (isset($data['Task']['task_type_id']) && $data['Task']['task_type_id'] == 0) {
				$data['Task']['task_type_id'] = 3; // TODO configurable default
			}

			if ($this->request->is('ajax')) {
				$this->autoRender = false;

				if ($this->Task->saveAll($data)) {
					echo '<div class="alert alert-success"><a class="close" data-dismiss="alert">x</a>Task successfully created.</div>';
				} else {
					echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">x</a>Could not add task to the project. Please, try again.</div>';
				}
			} else if ($this->request->is('post')) {
				// Do not redirect, allow them to save and add another task with the same details
				if ($this->Task->saveAll($data)) {
					$task = $this->Task->findById($this->Task->getLastInsertID());
					unset($this->request->data['Task']['subject']);
					unset($this->request->data['Task']['description']);
					unset($this->request->data['DependsOn']);
					$this->Flash->info(__("Task '%s' has been created", '<a href="'.Router::url(array(
						'controller' => 'tasks',
						'action' => 'view',
						'project' => $task['Project']['name'],
						$task['Task']['public_id']
					)).'">'.h($task['Task']['subject'])."</a>"));
				} else {
					$this->Flash->error(__("The task could not be saved. Please try again."));
				}
			}
		} else {
			// GET request: set default priority, type and assignment
			$this->request->data['Task']['task_type_id'] = 1;
			$this->request->data['Task']['assignee_id'] = 0;

			// TODO hard coded default, also clean this up and allow params to be passed for status/type etc.
			if (!$selectedPriority) {
				$selectedPriority = $this->TaskPriority->nameToID('major');
			}

			$this->request->data['Task']['task_priority_id'] = $selectedPriority;

			if ($selected_milestone_id) {
				$this->request->data['Task']['milestone_id'] = $selected_milestone_id;
			} else{
				$this->request->data['Task']['milestone_id'] = null;
			}

			if ($selected_story_id) {
				$this->request->data['Task']['story_id'] = $selected_story_id;
			} else{
				$this->request->data['Task']['story_id'] = null;
			}
		}

		$milestones = $this->Milestone->listMilestoneOptions();
		$stories = $this->Story->listStoryOptions();

		$taskPriorities	= $this->Task->TaskPriority->find('list', array('fields' => array('id', 'label'), 'order' => 'level DESC'));

		$backlog = $this->Task->find('all', array(
			'conditions' => array('project_id =' => $project['Project']['id'], 'id !=' => $this->Task->id),
			'fields' => array('Task.public_id', 'Task.subject', 'Task.id'),
			'recursive' => -1,
		));
		$availableTasks = array();
		foreach ($backlog as $t) {
			$availableTasks[$t['Task']['public_id']] = "#".$t['Task']['public_id']." ".$t['Task']['subject'];
		}

		$subTasks = array();
		$parentTasks = array();

		// If the user wants to create a subtask, put the parent task(s) in the correct list instead
		if (isset($this->request->query['parent'])) {
			$parents = preg_split('/\s*,\s*/', trim(@$this->request->query['parent']),   null, PREG_SPLIT_NO_EMPTY);
			$parents = array_filter($parents, function($a){return is_numeric($a);});
			foreach ($parents as $parent) {
				$parentTasks[$parent] = $availableTasks[$parent];
				unset($availableTasks[$parent]);
			}
		}

		$assignees = array();
		foreach ($this->Task->Project->listCollaborators($project['Project']['id']) as $assignee) {
			$assignees[$assignee['id']] = $assignee['title'];
		}
		$assignees[0] = "None";
		ksort($assignees);

		$this->set(compact('taskPriorities', 'milestones', 'stories', 'availableTasks', 'subTasks', 'parentTasks', 'assignees'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */

	public function edit($project = null, $public_id = null) {

		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __(''));
		// There is no separate edit form
		if (!$this->request->is('post') && !$this->request->is('put')) {
			throw new MethodNotAllowedException();
		}

		// Retrieve the projcet and task
		$project = $this->_getProject($project);
		$task = $this->Task->open($public_id);

		$data = $this->_cleanPost(array("Task.subject", "Task.description", "Task.task_priority_id", "Task.task_status_id", "Task.milestone_id", "Task.task_type_id", "Task.assignee_id", "Task.time_estimate", "Task.story_points", "Task.story_id", "Task.status", "Task.priority", "Task.type"));
		$data['DependsOn'] = @$this->request->data['DependsOn'];
		$data['DependedOnBy'] = @$this->request->data['DependedOnBy'];
		// Force the project ID to be correct
		$data['Task']['project_id'] = $project['Project']['id'];

		// Set the real task ID for saving
		$data['Task']['id'] = $this->Task->id;

		$saved = $this->Task->save($data);

		// Re-load all info about the task to return
		$task = $this->Task->findById($saved['Task']['id']);

		// Add in links to assignee, task, milestone...
		$task['Task']['uri'] = Router::url(array(
			'controller' => 'tasks',
			'action' => 'view',
			'project' => $task['Project']['name'],
			$task['Task']['public_id']));

		$task['Project']['uri'] = Router::url(array(
			'controller' => 'projects',
			'action' => 'view',
			'project' => $task['Project']['name']));

		$task['Milestone']['uri'] = Router::url(array(
			'controller' => 'milestones',
			'action' => 'view',
			'project' => $task['Project']['name'],
			$task['Milestone']['id']));

		$task['Assignee']['uri'] = Router::url(array(
			'controller' => 'users',
			'action' => 'view',
			$task['Assignee']['id']));

		$task['Owner']['uri'] = Router::url(array(
			'controller' => 'users',
			'action' => 'view',
			$task['Owner']['id']));

		foreach ($task['Time'] as $idx => $time) {
			$task['Time'][$idx]['uri'] = Router::url(array(
				'controller' => 'times',
				'action' => 'view',
				'project' => $task['Project']['name'],
				$time['id']));
		}

		foreach ($task['DependsOn'] as $idx => $depon) {
			$task['DependsOn'][$idx]['uri'] = Router::url(array(
				'controller' => 'tasks',
				'action' => 'view',
				'project' => $task['Project']['name'],
				$depon['public_id']));
		}

		foreach ($task['DependedOnBy'] as $idx => $depon) {
			$task['DependedOnBy'][$idx]['uri'] = Router::url(array(
				'controller' => 'tasks',
				'action' => 'view',
				'project' => $task['Project']['name'],
				$depon['public_id']));
		}
		$this->request->data = $task;

		// Show a message on save and redirect back to the task
		if ($this->request->is('ajax')) {
			$this->layout = 'ajax';
			$this->request->data['error'] = 'no_error';
			if (empty($this->request->data['Assignee']['email'])) {
				$this->request->data['Assignee']['gravatar'] = Gravatar::url($this->request->data['Assignee']['email'], array('d' => 'mm', 'url_only' => true));
			} else {
				$this->request->data['Assignee']['gravatar'] = Gravatar::url($this->request->data['Assignee']['email'], array('url_only' => true));
			}

			$this->set('data', $this->request->data);
			$this->render('/Elements/json');
			return;
		} else {

			$this->Flash->u($saved);
			return $this->redirect(array('project' => $project['Project']['name'], 'action' => 'view', $public_id));
		}

	}

/*
 * These functions are convenient helpers for changing task statuses
 */
	public function starttask($project = null, $public_id = null) {
		return $this->__updateTaskStatus($project, $public_id, 'in progress');
	}

	public function stoptask($project = null, $public_id = null) {
		return $this->__updateTaskStatus($project, $public_id, 'open');
	}

	public function opentask($project = null, $public_id = null) {
		return $this->__updateTaskStatus($project, $public_id, 'open');
	}

	public function closetask($project = null, $public_id = null) {
		return $this->__updateTaskStatus($project, $public_id, 'closed');
	}

	public function resolve($project = null, $public_id = null) {
		return $this->__updateTaskStatus($project, $public_id, 'resolved');
	}

	public function unresolve($project = null, $public_id = null) {
		return $this->__updateTaskStatus($project, $public_id, 'open');
	}

	public function freeze($project = null, $public_id = null) {
		return $this->__updateTaskStatus($project, $public_id, 'dropped');
	}

/**
 * __updateTaskStatus function. Does the bulk of the work for changing task statuses,
 * including error handling and adding comments if we have them.
 *
 * @access public
 * @param mixed $project (default: null)
 * @param mixed $id (default: null)
 * @return void
 */
	private function __updateTaskStatus($project = null, $public_id = null, $status_name = null) {

		$project = $this->_getProject($project);
		$task = $this->Task->open($public_id);
		$status = $this->TaskStatus->nameToId($status_name);
		$isAjax = $this->request->is("ajax");

		$this->Task->set('task_status_id', $status);
		$this->Task->project_id = $project['Project']['id'];
		$success = $this->Task->save();

		$messages = array();
		if ($success) {
			$messages[] = __("Task #%d updated - status set to %s", $public_id, $status_name);
		} else {
			$messages[] = __("Could not update status for task #%d", $public_id);
		}

		// If a user has made a comment, add it
		$data = $this->_cleanPost(array("TaskComment.comment"));
		if ($success && isset($data['TaskComment']['comment']) && $data['TaskComment']['comment'] != '') {
			$this->Task->TaskComment->create();

			$data['TaskComment']['task_id'] = $this->Task->id;
			$data['TaskComment']['user_id'] = $this->Auth->user('id');

			if ($this->Task->TaskComment->save($data)) {
				$messages[] = __("Comment added to task %d", $public_id);
				unset($this->request->data['TaskComment']);
			} else {
				$success = 0;
				$messages[] = __("Could not add comment to task #%d", $public_id);
			}
		}

		// Set appropriate error/success messages
		if ($isAjax) {
			if ($success) {
				$this->set("error", "no_error");
				$this->set("errorDescription", join("; ", $messages));
			} else {
				$this->set("error", "failed_to_save");
				$this->set("errorDescription", join("; ", $messages));
			}

			$this->set ("_serialize", array ("error", "errorDescription"));
		} else {
			if ($success) {
				$this->Flash->info(join("; ", $messages));
			} else {
				$this->Flash->error(join("; ", $messages));
			}
		}
		
		// Redirect to the view task page
		return $this->redirect(array('controller' => 'tasks', 'project' => $project['Project']['name'], 'action' => 'view', $public_id));
	}



	/* ************************************************* *
	*													 *
	*			API SECTION OF CONTROLLER				 *
	*			 CAUTION: PUBLIC FACING					 *
	*													 *
	* ************************************************** */

	function api_update($project_name = null, $public_id = null) {
		$this->layout = 'ajax';
		$data = array();

		if ($project_name == null) {
			$this->response->statusCode(400);
			$this->set('data', array('error' => 400, 'message' => __('Bad request, no project specified.')));
			$this->render('/Elements/json');
			return;

		} elseif ($public_id == null) {
			$this->response->statusCode(400);
			$this->set('data', array('error' => 400, 'message' => __('Bad request, no task ID specified.')));
			$this->render('/Elements/json');
			return;

		} elseif (!is_numeric($public_id)) {
			$this->response->statusCode(400);
			$this->set('data', array('error' => 400, 'message' => __('Bad request, task ID should be numeric.')));
			$this->render('/Elements/json');
			return;

		}

		$project = $this->_getProject($project_name);

		$task = $this->Task->find('first', array('conditions' => array(
			'Task.public_id' => $public_id,
			'Project.name' => $project_name,
		)));

		if (empty($task)) {
			$this->response->statusCode(404);
			$this->set('data', array('error' => 404, 'message' => __("Task with ID %s not found for project %s", $public_id, $project_name)));
			$this->render('/Elements/json');
			return;
		
		}

		// Make sure we're operating  on the correct task ID...
		$data = $this->_cleanPost(array("subject", "description", "task_priority_id", "task_status_id", "milestone_id", "task_type_id", "assignee_id", "time_estimate", "story_points", "story_id"));
		$data['id'] = $task['Task']['id'];
		$task = $this->Task->save($data);

		if ($task) {
			$this->response->statusCode(200);
			$data = $task['Task'];
			unset($data['id']);

			// Add in any extra data here... at the moment just used for refreshing the assignee gravatar easily
			if (isset($data['assignee_id']) && $data['assignee_id'] != 0) {
				$data['assignee_email'] = $this->Task->Assignee->field('email', array('id' => $data['assignee_id']));
				$data['assignee_name'] = $this->Task->Assignee->field('name', array('id' => $data['assignee_id']));
				$data['assignee_gravatar'] = Gravatar::url($data['assignee_email'], array('url_only' => true));
			} else {
				$data['assignee_email'] = '';
				$data['assignee_name'] = '';
				$data['assignee_gravatar'] = Gravatar::url(null, array('url_only' => true, 'd' => 'mm'));
			}
			$data['error'] = 'no_error';

			if (isset($data['milestone_id']) && $data['milestone_id'] != 0) {
				$data['milestone_subject'] = $this->Task->Milestone->field('subject', array('id' => $data['milestone_id']));
				$data['milestone_url'] = Router::url(array(
					"controller" => "milestones",
					"action" => "view",
					"project" => $project['Project']['name'],
					"api" => false,
					$data['milestone_id']
				));
				$data['milestone_isopen'] = $this->Task->Milestone->field('is_open', array('id' => $data['milestone_id']));
			} else {
				$data['milestone_subject'] = '(No milestone)';
				$data['milestone_isopen'] = 0;
			}
		} else {
			$this->response->statusCode(500);
			$data = array('error' => 500, 'message' => __('Task update failed'));
		}

		$this->set('data',$data);
		$this->render('/Elements/json');
	}

	public function tree($project = null, $public_id = null) {
		$this->set('pageTitle', $this->request['project']);
		$this->set('subTitle', __('Task dependency tree'));
		$project = $this->_getProject($project);
		$tree = $this->Task->getTree($project['Project']['id'], $public_id);
		$this->set('tree', $tree);
	}

}
