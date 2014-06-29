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

		$projectName = $this->args[0];
		$subject = $this->args[1];

		$due = $this->params['due-date'];
		if (!preg_match('/^\s*(\d{4}\-\d{1,2}\-\d{1,2})\s*$/', $due, $matches)) {
			$this->error("Invalid due date '$due'! (Format: YYYY-MM-DD)");
		} else {
			$due = $matches[1];
		}

		$project = $this->Project->findByName($projectName);

		if (empty($project)) {
			$this->error(__("No project called '$projectName' exists!"));
		}

		$milestone = $this->Milestone->findByProjectIdAndSubject($project['Project']['id'], $subject);

		if (!empty($milestone)) {
			$this->error(__("A milestone '$subject' for project '$projectName' already exists!"));
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
			$this->out(__("Milestone '$subject' created with ID ".$ok['Milestone']['id']));
		}
	}

}

