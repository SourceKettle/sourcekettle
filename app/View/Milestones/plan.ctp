<?php
/**
 *
 * View class for APP/tasks/sprint for the SourceKettle system
 * Shows a list of tasks for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Tasks
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('tasks', array ('inline' => false));
$this->Html->css("milestones.index", array ('inline' => false));

?>
<?= $this->Task->allDropdownMenus() ?>
<?= $this->DT->pHeader(__("Milestone planner: '%s'", $milestone['Milestone']['subject'])) ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>


    <?= $this->element('Milestone/topbar', array('name' => $milestone['Milestone']['subject'], 'id' => $milestone['Milestone']['id'])) ?>

    <!-- Planner board -->
    <div class="span10">  <div class="row">

    <!-- Unattached row -->
	<div class="row-fluid span12">
        <?= $this->element('Task/Board/column',
            array(
				'tasks' => $wontHave,
				'milestoneID' => 0,
				'status' => 'dropped',
				'title' => __('Project Backlog'),
				'tooltip' => __('These tasks will not be completed during this milestone'),
				'span' => '12',
				'task_span' => '4',
				'classes' => 'sprintboard-icebox',
				'draggable' => $hasWrite,
			)
        ) ?>

	<!-- End project backlog -->
	</div>

    <!-- Could-have, might-have -->
	<div class="row-fluid span12">

        <?= $this->element('Task/Board/column',
            array(
				'tasks' => $mightHave,
				'milestoneID' => $milestone['Milestone']['id'],
				'priority' => 'minor',
				'title' => __('Minor'),
				'tooltip' => __('These tasks are not important for the milestone, and can be easily dropped'),
				'span' => '6',
				'task_span' => '6',
				'classes' => 'sprintboard-column',
				'addLink' => false,
				'draggable' => $hasWrite,
			)
        ) ?>
        <?= $this->element('Task/Board/column',
            array(
				'tasks' => $couldHave,
				'milestoneID' => $milestone['Milestone']['id'],
				'priority' => 'major',
				'title' => __('Major'),
				'tooltip' => __('These tasks will be completed if possible, but if time runs out they will be dropped'),
				'span' => '6',
				'task_span' => '6',
				'classes' => 'sprintboard-column',
				'addLink' => false,
				'draggable' => $hasWrite,
			)
        ) ?>	


	<!-- End could-have/might-have -->
	</div>

    <!-- Must-have, should-have -->
	<div class="row-fluid span12">

        <?= $this->element('Task/Board/column',
            array(
				'tasks' => $shouldHave,
				'milestoneID' => $milestone['Milestone']['id'],
				'priority' => 'urgent',
				'title' => __('Urgent'),
				'tooltip' => __('These tasks are not vital to the milestone, but should not be dropped unless time is very short'),
				'span' => '6',
				'task_span' => '6',
				'classes' => 'sprintboard-column',
				'addLink' => false,
				'draggable' => $hasWrite,
			)
        ) ?>
        <?= $this->element('Task/Board/column',
            array(
				'tasks' => $mustHave,
				'milestoneID' => $milestone['Milestone']['id'],
				'priority' => 'blocker',
				'title' => __('Blocker'),
				'tooltip' => __('The highest priority - these tasks MUST be completed for the milestone to be a success'),
				'span' => '6',
				'task_span' => '6',
				'classes' => 'sprintboard-column',
				'addLink' => false,
				'draggable' => $hasWrite,
			)
        ) ?>	


	<!-- End must-have/should-have -->
	</div>



    <!-- End milestone board -->
	</div> </div>
</div>

