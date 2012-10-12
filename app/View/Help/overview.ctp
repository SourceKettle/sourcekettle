<?php
/**
 *
 * View class for APP/help/overview for the DevTrack system
 * Display the help page for the project overview part of the application
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Help
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('projects.overview', null, array ('inline' => false));
$this->Html->css('pages/help', null, array ('inline' => false));

echo $this->Bootstrap->page_header('Help!<small> the project overview page...</small>'); ?>

<div class="row">
	<div class="span2">
		<?= $this->element('Sidebar/help') ?>
	</div>
	<div class="span10">
		<div class="well">
			<h3>Project overview</h3>
			<p>The front page of a project contains a large amount of data, firstly, we are going to cover the status bar. The status bar consists of 3 major components, the task breakdown, the latest open milestone and some additional facts about the project.</p>

			<h4>The task breakdown</h4>
			<p>The task breakdown (highlighted in <strong>exhibit 1</strong>) shows the statuses of all the tasks that have been created for the project. These tasks are grouped by status.  '<span class="open-tasks"><a href='#'>open</a></span>' covers 'open' and 'in progress' tasks, whereas '<span class="closed-tasks"><a href='#'>closed</a></span>' covers 'resolved' and 'closed' tasks.</p>
		</div>

		<div class="row-fluid">
			<div class="span10 offset1">
				<?= $this->element('Help/project_info', array('c' => 1)) ?>
				<div class="alert alert-info">
					<strong>Exhibit 1:</strong> The tasks breakdown shows the statuses of all the tasks created for a project.
				</div>
			</div>
		</div>

		<div class="well">
			<h4>The latest open milestone</h4>
			<p>Just to the right of the task breakdown we find the latest open milestone. Here you can find its progress and information on when the milestone is due. If you don't see a milestone here, it is because there are <strong>no open milestones</strong> in the project.</p>
		</div>

		<div class="row-fluid">
			<div class="span10 offset1">
				<?= $this->element('Help/project_info', array('c' => 2)) ?>
				<div class="alert alert-info">
					<strong>Exhibit 2:</strong> The milestone breakdown shows you the progress of the latest open milestone.
				</div>
			</div>
		</div>

		<div class="well">
			<h4>Additional project information</h4>
			<p>Finally, on the far right we have additional information about the project (highlighted in <strong>exhibit 1</strong>). This traditionally includes the last time activity was seen on the project and how many people are working on it.</p>
		</div>

		<div class="row-fluid">
			<div class="span10 offset1">
				<?= $this->element('Help/project_info', array('c' => 3)) ?>
				<div class="alert alert-info">
					<strong>Exhibit 3:</strong> Additional project information shows you detailed information about the project.
				</div>
			</div>
		</div>

	</div>
</div>
