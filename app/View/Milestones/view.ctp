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
$this->Html->script("jquery-ui.min", array ('inline' => false));
$this->Html->script("jquery.ui.touch-punch.min", array ('inline' => false));
$this->Html->script("milestones.droplist", array ('inline' => false));
?>

<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>


    <?= $this->element('Milestone/topbar', array('name' => $milestone['Milestone']['subject'], 'id' => $milestone['Milestone']['id'])) ?>

    <!-- Milestone board -->
    <div class="span10">  <div class="row">

    <!-- Primary columns -->
	<div class="row-fluid span12">

        <?= $this->element('Task/Board/column',
            array('tasks' => $backlog, 'status' => 'open', 'title' => __('Backlog'), 'span' => '4', 'classes' => 'sprintboard-column', 'draggable' => $draggable)
        ) ?>

        <?= $this->element('Task/Board/column',
            array('tasks' => $inProgress, 'status' => 'in progress', 'title' => __('In Progress'), 'span' => '4', 'classes' => 'sprintboard-column', 'draggable' => $draggable)
        ) ?>
        <?= $this->element('Task/Board/column',
            array('tasks' => $completed, 'status' => 'resolved', 'title' => __('Completed'), 'span' => '4', 'classes' => 'sprintboard-column', 'draggable' => $draggable)
        ) ?>

	<!-- End primary columns -->
	</div>

    <!-- Icebox row -->
	<div class="row-fluid span12">
        <?= $this->element('Task/Board/column',
            array('tasks' => $iceBox, 'status' => 'dropped', 'title' => __('Ice Box'), 'span' => '12', 'classes' => 'sprintboard-icebox', 'draggable' => $draggable)
        ) ?>

	<!-- End icebox -->
	</div>

    <!-- End milestone board -->
	</div> </div>

</div>

