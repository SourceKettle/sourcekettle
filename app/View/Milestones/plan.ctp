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
<div class="row-fluid">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>

	<div class="span10">
		<div class="row-fluid">
    	<?= $this->element('Milestone/topbar', array('name' => $milestone['Milestone']['subject'], 'id' => $milestone['Milestone']['id'])) ?>
		</div>

		<div class="row-fluid">
        <?= $this->element('Task/Board/column',
            array(
				'tasks' => $wontHave,
				'milestoneID' => 0,
				'status' => 'dropped',
				'title' => __('Backlog - tasks not in this milestone'),
				'tooltip' => __('These tasks are not attached to a milestone, so we have not planned to do them'),
				'span' => '12',
				'task_span' => '4',
				'classes' => 'sprintboard-icebox',
				'draggable' => $hasWrite,
			)
        ) ?>

		</div>

		<div class="row-fluid">

        <?= $this->element('Task/Board/column',
            array(
				'tasks' => $mightHave,
				'milestoneID' => $milestone['Milestone']['id'],
				'priority' => 'minor',
				'title' => __('Minor'),
				'tooltip' => __('These tasks are not important for the milestone, and can be easily dropped'),
				'span' => '3',
				'task_span' => '12',
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
				'span' => '3',
				'task_span' => '12',
				'classes' => 'sprintboard-column',
				'addLink' => false,
				'draggable' => $hasWrite,
			)
        ) ?>	


        <?= $this->element('Task/Board/column',
            array(
				'tasks' => $shouldHave,
				'milestoneID' => $milestone['Milestone']['id'],
				'priority' => 'urgent',
				'title' => __('Urgent'),
				'tooltip' => __('These tasks are not vital to the milestone, but should not be dropped unless time is very short'),
				'span' => '3',
				'task_span' => '12',
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
				'span' => '3',
				'task_span' => '12',
				'classes' => 'sprintboard-column',
				'addLink' => false,
				'draggable' => $hasWrite,
			)
        ) ?>	

	</div>

</div>

