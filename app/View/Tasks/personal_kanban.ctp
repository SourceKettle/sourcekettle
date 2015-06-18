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

// TODO cleanup
$o = array_reduce($open, function($sum, $a) {return $sum + ($a['Task']['story_points'] == null? 0 : $a['Task']['story_points']);}, 0);
$i = array_reduce($inProgress, function($sum, $a) {return $sum + ($a['Task']['story_points'] == null? 0 : $a['Task']['story_points']);}, 0);
$r = array_reduce($resolved, function($sum, $a) {return $sum + ($a['Task']['story_points'] == null? 0 : $a['Task']['story_points']);}, 0);
$c = array_reduce($closed, function($sum, $a) {return $sum + ($a['Task']['story_points'] == null? 0 : $a['Task']['story_points']);}, 0);
$t = $o + $i + $r + $c;

$percent_o = ($t == 0) ? 0 : $o / $t * 100;
$percent_i = ($t == 0) ? 0 : $i / $t * 100;
$percent_r = ($t == 0) ? 0 : $r / $t * 100;
$percent_c = ($t == 0) ? 0 : $c / $t * 100;

$percent_none = ($t == 0) ? 100 : 0;

?>

<?= $this->Task->allDropdownMenus() ?>

<div class="row-fluid">
	<div class="span12 progress-milestone">
		<div class="progress progress-striped">
                    <div id="points-open" class="bar bar-danger"  style="width: <?= $percent_o ?>%;" title="<?=__('%d story points open', $o)?>"><?= ($o > 0)  ? __('%dpt open', $o) : ''?></div>
                    <div id="points-inprogress" class=" bar bar-warning" style="width: <?= $percent_i ?>%;" title="<?=__('%d story points in progress', $i)?>"><?= ($i > 0)  ? __('%dpt in progress', $i) : ''?></div>
                    <div id="points-resolved" class="bar bar-success" style="width: <?= $percent_r ?>%;" title="<?=__('%d story points resolved', $r)?>"><?= ($r > 0)  ? __('%dpt resolved', $r) : ''?></div>
                    <div id="points-closed" class="bar bar-info"    style="width: <?= $percent_c ?>%;" title="<?=__('%d story points closed', $c)?>"><?= ($c > 0)  ? __('%dpt closed', $c) : ''?></div>
                    <div id="points-none" class="bar bar-inactive"    style="width: <?= $percent_none?>%;"><?=__('No tasks with story points in this milestone')?></div>
                </div>
	</div>
</div>
	
	

<div class="row-fluid">
        <?= $this->element('Task/Board/column',
            array('tasks' => $open, 'status' => 'open', 'title' => __('Open'), 'span' => '3', 'task_span' => 12, 'classes' => 'sprintboard-column', 'draggable' => true)
        ) ?>

        <?= $this->element('Task/Board/column',
            array('tasks' => $inProgress, 'status' => 'in progress', 'title' => __('In Progress'), 'span' => '3', 'task_span' => 12, 'classes' => 'sprintboard-column', 'draggable' => true)
        ) ?>
        <?= $this->element('Task/Board/column',
            array('tasks' => $resolved, 'status' => 'resolved', 'title' => __('Resolved'), 'span' => '3', 'task_span' => 12, 'classes' => 'sprintboard-column', 'draggable' => true)
        ) ?>
        <?= $this->element('Task/Board/column',
            array('tasks' => $closed, 'status' => 'closed', 'title' => __('Closed'), 'span' => '3', 'task_span' => 12, 'classes' => 'sprintboard-column', 'draggable' => true)
        ) ?>

</div>


