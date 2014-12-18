<?php


/**
 * TaskShell - admin commands to add/remove tasks on the command line
 *
 * @author amn@ecs.soton.ac.uk
 */
class TaskShell extends AppShell {

	public $uses = array('User', 'Collaborator', 'Task', 'TaskType', 'TaskStatus', 'TaskPriority', 'Project');

	public $minPasswordLength = 8;

	public function getOptionParser() {
		$parser = parent::getOptionParser();

		// TODO should be moved to model class?
		$priorities = $this->TaskPriority->find('list');
		$statuses = $this->TaskStatus->find('list');
		$types = $this->TaskType->find('list');

		$parser->addSubCommand('add', array(
			'help' => __('Add a task to a project'),
			'parser' => array(
				'description' => array(
					__("Use this command to add a new task to a project."),
				),
				'arguments' => array(
					'project' => array(
						'help' => __("The project name"),
						'required' => true,
					),
					'subject' => array(
						'help'  => __("The task subject"),
						'required' => true,
					),
				),
				'options' => array(
					'owner' => array(
						'short' => 'o',
						'help' => __("The user ID or email address to own the task"),
					),
					'description' => array(
						'short' => 'd',
						'help' => __("The longer task description"),
					),
					'type' => array(
						'short' => 't',
						'help' => __("The task type"),
						'choices' => $types,
						'default' => 'bug', // TODO load from config
					),
					'priority' => array(
						'short' => 'p',
						'help' => __("The task priority"),
						'choices' => $priorities,
						'default' => 'major', // TODO load from config
					),
					'status' => array(
						'short' => 's',
						'help' => __("The task status"),
						'choices' => $statuses,
						'default' => 'open',
					),
					'assignee' => array(
						'short' => 'a',
						'help' => __("The user ID or email address to assign the task to"),
					),
					'milestone-id' => array(
						'short' => 'm',
						'help' => __("The milestone ID to attach the task to"),
					),
					'time-estimate' => array(
						'short' => 'i',
						'help' => __("How long the task is expected to take (e.g. 4h20m)"),
					),
					'story-points' => array(
						'short' => 'r',
						'help' => __("How many story points the task is worth"),
					),
					// TODO dependencies? Maybe a separate function? Do we need it?
					
				)
			)
		));

		$now = new DateTime();
		$parser->addSubCommand('comment', array(
			'help' => __('Add a comment to a task'),
			'parser' => array(
				'description' => array(
					__("Use this command to add a new task to a project."),
				),
				'arguments' => array(
					'project' => array(
						'help' => __("The project name"),
						'required' => true,
					),
					'user' => array(
						'help'  => __("The user ID or email address of the user making the comment"),
						'required' => true,
					),
					'id' => array(
						'help'  => __("The task ID, as shown in the web interface"),
						'required' => true,
					),
					'text' => array(
						'help'  => __("The text of the comment"),
						'required' => true,
					),
				),
				'options' => array(
					'ignore-fail' => array(
						'short' => 'i',
						'boolean' => true,
						'help' => __("Exit successfully even if the comment could not be saved. Used by git update hook."),
					),
					'timestamp' => array(
						'short' => 't',
						'help' => __("When the comment was made (defaults to now)"),
						'default' => $now->format('Y-m-d H:i:s'),
					),
					// TODO dependencies? Maybe a separate function? Do we need it?
					
				)
			)
		));

		return $parser;
	}

/**
 * Given an ID or email address, checks that it refers to a valid user who
 * collaborates on the project. TODO put this in a model class.
 */
	private function __checkCollaborator($idOrEmail, $projectID) {

		// Matches an ID number
		if (preg_match('/^\s*(\d+)\s*$/', $idOrEmail, $matches)){
			$user = $this->User->findById($matches[1]);

		// Assume email address
		} else {
			$user = $this->User->findByEmail($idOrEmail);
		}

		// Couldn't find them...
		if (!$user) {
			$this->error(__("Could not find a user with ID/email '$idOrEmail'!"));
		}

		// Not a project collaborator...
		$ok = $this->Collaborator->find('first', array('conditions' => array(
			'User.id' => $user['User']['id'],
			'Project.id' => $projectID
		)));

		if (!$ok) {
			$this->error(__("User '".$user['User']['email']."' is not a collaborator on the project!"));
		}

		return $user['User']['id'];
		
	}

/**
 * Adds a task
 */
	public function add() {

		$this->out("Adding a task");

		$projectName = $this->args[0];
		$subject = $this->args[1];

		$project = $this->Project->findByName($projectName);

		if (empty($project)) {
			$this->error(__("No project called '$projectName' exists!"));
		}

		// Parse the time estimate into a number of minutes
		$timeEstimate = TimeString::parseTime(@$this->params['time-estimate']);
		
		// Get the owner and assignee IDs
		$assigneeID = 0;
		if (isset($this->params['assignee'])) {
			$assigneeID = $this->__checkCollaborator($this->params['assignee'], $project['Project']['id']);
		}

		$ownerID = 0;
		if (isset($this->params['owner'])) {
			$ownerID = $this->__checkCollaborator($this->params['owner'], $project['Project']['id']);
		}

		$storyPoints = @$this->params['story-points'];
		if ($storyPoints && !preg_match('/^\d+$/', $storyPoints)) {
			$this->error("Invalid number of story points: '".$this->params['story-points']."'");
		}

		// No existing task, create one
		$this->out(__("Creating a new task..."));

		$this->Task->create();
		$ok = $this->Task->save(array(
			'Task' => array(
				'subject'       => $subject,
				'project_id'    => $project['Project']['id'],
				'description'   => @$this->params['description'],
				'time_estimate' => $timeEstimate,
				'assignee_id'   => $assigneeID,
				'story_points'  => $storyPoints,
				'milestone_id'  => @$this->params['milestone-id'],
				'deleted'       => 0,
				'type'          => $this->params['type'],
				'priority'      => $this->params['priority'],
				'status'        => $this->params['status'],
				'owner_id'      => $ownerID,
			),
		), array('callbacks' => false));

		if (!$ok) {
			$this->error(__("Failed to create task '$subject'!"));
		} else {
			$this->out(__("Task '$subject' created."));
		}
	}

	public function comment () {
		list($projectName, $user, $id, $comment) = $this->args;

		$timestamp = $this->params['timestamp'];

		// Validate project...
		$this->Task->Project->recursive = -1;
		$project = $this->Task->Project->findByNameOrId($projectName, $projectName);

		if (!$project) {
			if ($this->params['ignore-fail']) {
				exit(0);
			}
			$this->error(__("Unknown project '$projectName'"));
			exit(1);
		}

		// Validate user...
		$this->Task->Owner->recursive = -1;
		$user = $this->Task->Owner->findByEmailOrId($user, $user);

		if (!$user) {
			if ($this->params['ignore-fail']) {
				exit(0);
			}
			$this->error(__("Unknown user '$user'"));
			exit(1);
		}

		// Validate task...
		$this->Task->recursive = -1;
		$task = $this->Task->findByProjectIdAndPublicId($project['Project']['id'], $id);

		if (!$task) {
			if ($this->params['ignore-fail']) {
				exit(0);
			}
			$this->error(__("Unknown task #$id on project '$projectName'"));
			exit(1);
		}


		// Check for duplicates
		$this->Task->TaskComment->recursive = -1;
		$existing = $this->Task->TaskComment->find('first', array(
			'conditions' => array(
				'task_id' => $task['Task']['id'],
				'user_id' => $user['Owner']['id'],
				'created' => $timestamp,
				'comment' => $comment
			)
		));

		if (!empty($existing)) {
			if ($this->params['ignore-fail']) {
				exit(0);
			}
			$this->error(__("Duplicate comment detected"));
			exit(1);
		}

		// All good so far... create the comment!
		$this->Task->TaskComment->create();
		$ok = $this->Task->TaskComment->save(array(
			'TaskComment' => array(
				'task_id' => $task['Task']['id'],
				'user_id' => $user['Owner']['id'],
				'comment' => $comment,
				'created' => $timestamp,
			),
		));
debug($ok);
		if (!$ok) {
			if ($this->params['ignore-fail']) {
				exit(0);
			}
			$this->error(__("Failed to comment on $projectName/#$id"));
			exit(1);
		}

	}

}

