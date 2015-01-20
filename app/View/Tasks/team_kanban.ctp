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

$this->Html->css('tasks', null, array ('inline' => false));
$this->Html->css("milestones.index", null, array ('inline' => false));

?>

<?= $this->Task->allDropdownMenus() ?>
<?= $this->DT->pHeader(__("Team kanban chart: %s", $team['Team']['name'])) ?>
<div class="row">

    <!-- Milestone board -->
    <div class="span12">
	
	<div class="row"><div class="span2 offset4">
	</div></div>

	<div class="row"><div class="span2 offset4">
	<span class="label">Story points complete: <span id="points_complete"><?=$points_complete?></span> / <span id="points_total"><?=$points_total?></span></span>
	</div></div>

	<div class="row">

    <!-- Primary columns -->
	<div class="row-fluid span12">

        <?= $this->element('Task/Board/column',
            array('tasks' => $open, 'status' => 'open', 'title' => __('Open'), 'span' => '4', 'task_span' => 12, 'classes' => 'sprintboard-column', 'draggable' => false)
        ) ?>

        <?= $this->element('Task/Board/column',
            array('tasks' => $inProgress, 'status' => 'in progress', 'title' => __('In Progress'), 'span' => '4', 'task_span' => 12, 'classes' => 'sprintboard-column', 'draggable' => false)
        ) ?>
        <?= $this->element('Task/Board/column',
            array('tasks' => $resolved, 'status' => 'resolved', 'title' => __('Resolved'), 'span' => '4', 'task_span' => 12, 'classes' => 'sprintboard-column', 'draggable' => false)
        ) ?>

	<!-- End primary columns -->
	</div>

    <!-- End milestone board -->
	</div> </div>

</div>

