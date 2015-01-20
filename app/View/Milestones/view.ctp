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

if ($sourcekettle_config['Features']['4col_kanban_enabled']['value']) {
	$colSpan = 3;
} else {
	$colSpan = 4;
}

$this->Html->css('tasks', null, array ('inline' => false));
$this->Html->css("milestones.index", null, array ('inline' => false));
?>
<?= $this->Task->allDropdownMenus() ?>

<?= $this->DT->pHeader(__("Milestone board: '%s'", $milestone['Milestone']['subject'])) ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>

    <?= $this->element('Milestone/topbar', array('name' => $milestone['Milestone']['subject'], 'id' => $milestone['Milestone']['id'])) ?>

    <!-- Milestone board -->
    <div class="span10">
	
	<div class="row"><div class="span2 offset4">
	<span class="label">Story points complete: <span id="points_complete"><?=$points_complete?></span> / <span id="points_total"><?=$points_total?></span></span>
	</div></div>

	<div class="row">

    <!-- Primary columns -->
	<div class="row-fluid span12">

        <?= $this->element('Task/Board/column',
            array('tasks' => $open, 'status' => 'open', 'title' => __('Open'), 'tooltip' => __('Tasks that are not complete'), 'span' => $colSpan, 'task_span' => 12, 'classes' => 'sprintboard-column', 'draggable' => $hasWrite, 'milestoneID' => $milestone['Milestone']['id'], 'addLink' => false)
        ) ?>

        <?= $this->element('Task/Board/column',
            array('tasks' => $inProgress, 'status' => 'in progress', 'title' => __('In Progress'), 'tooltip' => __('Tasks the team are working on'), 'span' => $colSpan, 'task_span' => 12, 'classes' => 'sprintboard-column', 'draggable' => $hasWrite)
        ) ?>
        <?= $this->element('Task/Board/column',
            array('tasks' => $resolved, 'status' => 'resolved', 'title' => __('Resolved'), 'tooltip' => __('Tasks that are finished'), 'span' => $colSpan, 'task_span' => 12, 'classes' => 'sprintboard-column', 'draggable' => $hasWrite)
        ) ?>
	<? if ($sourcekettle_config['Features']['4col_kanban_enabled']['value']) {
		echo $this->element('Task/Board/column', array(
			'tasks' => $closed, 'status' => 'closed', 'title' => __('Closed'), 'tooltip' => __('Tasks that have been tested and signed off'), 'span' => $colSpan, 'task_span' => 12, 'classes' => 'sprintboard-column', 'draggable' => $hasWrite)
        	);
	} ?>

	<!-- End primary columns -->
	</div>

    <!-- Icebox row -->
	<div class="row-fluid span12">
        <?= $this->element('Task/Board/column',
            array('tasks' => $dropped, 'status' => 'dropped', 'title' => __('Dropped tasks'), 'tooltip' => __('Tasks that we did not have time for, they will not be worked on in this milestone'), 'span' => '12', 'task_span' => 4, 'classes' => 'sprintboard-icebox', 'draggable' => $hasWrite)
        ) ?>

	<!-- End icebox -->
	</div>

    <!-- End milestone board -->
	</div> </div>

</div>

