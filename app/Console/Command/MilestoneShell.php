<?php


/**
 * MilestoneShell - admin commands to add/remove milestones on the command line
 *
 * @author amn@ecs.soton.ac.uk
 */
class MilestoneShell extends AppShell {

	public $uses = array('Milestone', 'Project');

	public $minPasswordLength = 8;

	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$due = new DateTime();
		$due->add(new DateInterval('P1M'));

		$parser->addSubCommand('add', array(
			'help' => __('Add a milestone to a project'),
			'parser' => array(
				'description' => array(
					__("Use this command to add a new milestone to a project."),
				),
				'arguments' => array(
					'project' => array(
						'help' => __("The project name"),
						'required' => true,
					),
					'subject' => array(
						'help'  => __("The milestone subject"),
						'required' => true,
					),
				),
				'options' => array(
					'description' => array(
						'short' => 'd',
						'help' => __("The longer milestone description"),
					),
					'due-date' => array(
						'short' => 't',
						'help' => __("The due date for the milestone"),
						'default' => $due->format('Y-m-d'),
					),
				)
			)
		));


		return $parser;
	}

/**
 * Adds a milestone
 */
	public function add() {

		$this->out("Adding a milestone");

		$project = $this->args[0];
		$subject = $this->args[1];

		$due = $this->params['due-date'];
		if (!preg_match('/^\s*(\d{4}\-\d{1,2}\-\d{1,2})\s*$/', $due, $matches)) {
			$this->error("Invalid due date '$due'! (Format: YYYY-MM-DD)");
		} else {
			$due = $matches[1];
		}

		$project = $this->Project->find('first', array(
			'conditions' => array(
				'Project.name' => $project
			)
		));

		if (empty($project)) {
			$this->error(__("No project called '$name' exists!"));
		}

		$milestone = $this->Milestone->find('first', array(
			'conditions' => array(
				'Milestone.project_id' => $project['Project']['id'],
				'Milestone.subject' => $subject
			)
		));

		if (!empty($milestone)) {
			$this->error(__("A milestone '$subject' for project '$project' already exists!"));
		}

		// No existing account, create one
		$this->out(__("Creating a new milestone..."));

		$this->Milestone->create();
		$ok = $this->Milestone->save(array( 'Milestone' => array(
			'subject'      => $subject,
			'project_id'   => $project['Project']['id'],
			'description'  => @$this->params['description'],
			'deleted'      => 0,
			'is_open'      => 1,
			'due'          => $due,
		)), array('callbacks' => false));

		if (!$ok) {
			$this->error(__("Failed to create milestone '$subject'!"));
		} else {
			$this->out(__("Milestone '$subject' created."));
		}
	}

/**
 * Disables a milestone, found by email address
 */
	public function disable() {
		$this->out("Disabling a milestone");
		$email = $this->args[0];

		// Find existing milestone
		$found = $this->Milestone->find('first', array(
			'conditions' => array(
				'email' => $email
			)
		));

		// If it does not exist, error out
		if (empty($found)) {
			$this->error(__("Could not find an account with that email address!"));
		}

		// Save with updated status
		$this->Milestone->id = $found['Milestone']['id'];

		if (!$this->Milestone->saveField('is_active', 0)) {
			$this->error(__("Failed to disable user '$email'!"));
		} else {
			$this->out(__("Milestone '$email' disabled."));
		}
	}

/**
 * Enables a milestone, found by email address
 */
	public function enable() {
		$this->out("Enabling a milestone");
		$email = $this->args[0];

		// Find existing milestone
		$found = $this->Milestone->find('first', array(
			'conditions' => array(
				'email' => $email
			)
		));

		// If it does not exist, error out
		if (empty($found)) {
			$this->error(__("Could not find an account with that email address!"));
		}

		// Save with updated status
		$this->Milestone->id = $found['Milestone']['id'];

		if (!$this->Milestone->saveField('is_active', 1)) {
			$this->error(__("Failed to enable user '$email'!"));
		} else {
			$this->out(__("Milestone '$email' enabled."));
		}
	}

/**
 * Promotes a normal user to sysadmin status
 */
	public function promote() {
		$this->out("Promoting a normal milestone to sysadmin");
		$email = $this->args[0];

		// Find existing milestone
		$found = $this->Milestone->find('first', array(
			'conditions' => array(
				'email' => $email
			)
		));

		// If it does not exist, error out
		if (empty($found)) {
			$this->error(__("Could not find an account with that email address!"));
		}

		// Save with updated status
		$this->Milestone->id = $found['Milestone']['id'];

		if (!$this->Milestone->saveField('is_admin', 1)) {
			$this->error(__("Failed to promote user '$email'!"));
		} else {
			$this->out(__("Milestone '$email' promoted to system administrator."));
		}
	}

/**
 * Demotes a sysadmin to normal user status
 */
	public function demote() {
		$this->out("Demoting a sysadmin to a normal milestone");
		$email = $this->args[0];

		// Find existing milestone
		$found = $this->Milestone->find('first', array(
			'conditions' => array(
				'email' => $email
			)
		));

		// If it does not exist, error out
		if (empty($found)) {
			$this->error(__("Could not find an account with that email address!"));
		}

		// Save with updated status
		$this->Milestone->id = $found['Milestone']['id'];

		if (!$this->Milestone->saveField('is_admin', 0)) {
			$this->error(__("Failed to demote user '$email'!"));
		} else {
			$this->out(__("Milestone '$email' demoted to normal user."));
		}
	}

}

