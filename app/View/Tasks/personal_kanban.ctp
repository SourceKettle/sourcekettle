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

$this->Html->css('tasks.index', null, array ('inline' => false));
$this->Html->css("milestones.index", null, array ('inline' => false));
$this->Html->script("milestones.droplist", array ('inline' => false));

$draggable = true;

// API base for updating tasks - note we have to generate a link per task as the projects are different...
$api_base = $this->Html->link(array(
	'controller' => 'tasks',
	'action' => 'update',
	'api' => true
));
// TODO fix up the javascript to work across projects//$this->Html->scriptBlock("$(initTaskDroplists('$api_base'));", array ('inline' => false));

?>

<?= $this->DT->pHeader(__("My kanban chart")) ?>
<div class="row">

    <!-- Milestone board -->
    <div class="span12">
	
	<div class="row"><div class="span2 offset4">
	</div></div>

	<div class="row">

    <!-- Primary columns -->
	<div class="row-fluid span12">

        <?= $this->element('Task/Board/column',
            array('tasks' => $backlog, 'status' => 'open', 'title' => __('Backlog'), 'span' => '4', 'task_span' => 12, 'classes' => 'sprintboard-column', 'draggable' => $draggable)
        ) ?>

        <?= $this->element('Task/Board/column',
            array('tasks' => $inProgress, 'status' => 'in progress', 'title' => __('In Progress'), 'span' => '4', 'task_span' => 12, 'classes' => 'sprintboard-column', 'draggable' => $draggable)
        ) ?>
        <?= $this->element('Task/Board/column',
            array('tasks' => $completed, 'status' => 'resolved', 'title' => __('Completed'), 'span' => '4', 'task_span' => 12, 'classes' => 'sprintboard-column', 'draggable' => $draggable)
        ) ?>

	<!-- End primary columns -->
	</div>

    <!-- End milestone board -->
	</div> </div>

</div>

