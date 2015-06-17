<?php
/**
 *
 * View class for APP/tasks/sprint for the SourceKettle system
 * Shows a list of tasks for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link		  http://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Tasks
 * @since		 SourceKettle v 0.1
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

if ($sourcekettle_config['Features']['4col_kanban_enabled']['value']) {
	$colSpan = 3;
} else {
	$colSpan = 4;
}

// TODO cleanup
$o = $milestone['Tasks']['open']['points'];
$i = $milestone['Tasks']['in progress']['points'];
$r = $milestone['Tasks']['resolved']['points'];
$c = $milestone['Tasks']['closed']['points'];
$t = $points_total;

$percent_o = ($t == 0) ? 0 : $o / $t * 100;
$percent_i = ($t == 0) ? 0 : $i / $t * 100;
$percent_r = ($t == 0) ? 0 : $r / $t * 100;
$percent_c = ($t == 0) ? 0 : $c / $t * 100;

$percent_none = ($t == 0) ? 100 : 0;

$this->Html->css('tasks', null, array ('inline' => false));
$this->Html->css("milestones.index", null, array ('inline' => false));
?>
<?= $this->Task->allDropdownMenus() ?>

<div class="row-fluid">
	<?= $this->element('Milestone/topbar', array('name' => $milestone['Milestone']['subject'], 'id' => $milestone['Milestone']['id'])) ?>
</div>

	

<div class="row-fluid">

<?= $this->element('Task/Board/column', array(
	'tasks' => $open, 
	'status' => 'open', 
	'title' => __('Open'), 
	'tooltip' => __('Tasks that are not complete'), 
	'span' => $colSpan, 
	'task_span' => 12, 
	'classes' => 'sprintboard-column', 
	'draggable' => $hasWrite, 
	'milestoneID' => $milestone['Milestone']['id'], 
	'addLink' => false,
	'includeMilestoneLabel' => false,
	'localStoryLink' => true,
	'total' => $milestone['Tasks']['open']['points'],
)) ?>

<?= $this->element('Task/Board/column', array(
	'tasks' => $inProgress, 
	'status' => 'in progress', 
	'title' => __('In Progress'), 
	'tooltip' => __('Tasks the team are working on'), 
	'span' => $colSpan, 
	'task_span' => 12, 
	'classes' => 'sprintboard-column', 
	'draggable' => $hasWrite,
	'includeMilestoneLabel' => false,
	'localStoryLink' => true,
	'total' => $milestone['Tasks']['in progress']['points'],
)) ?>

<? if ($sourcekettle_config['Features']['4col_kanban_enabled']['value']) {
	echo $this->element('Task/Board/column', array(
		'tasks' => $resolved, 
		'status' => 'resolved', 
		'title' => __('Resolved'), 
		'tooltip' => __('Tasks that are finished'), 
		'span' => $colSpan, 
		'task_span' => 12, 
		'classes' => 'sprintboard-column', 
		'draggable' => $hasWrite,
		'includeMilestoneLabel' => false,
		'localStoryLink' => true,
		'total' => $milestone['Tasks']['resolved']['points'],
	));

	echo $this->element('Task/Board/column', array(
		'tasks' => $closed, 
		'status' => 'closed', 
		'title' => __('Closed'), 
		'tooltip' => __('Tasks that have been tested and signed off'), 
		'span' => $colSpan, 
		'task_span' => 12, 
		'classes' => 'sprintboard-column', 
		'draggable' => $hasWrite,
		'includeMilestoneLabel' => false,
		'localStoryLink' => true,
		'total' => $milestone['Tasks']['closed']['points'],
	));
} else {
	echo $this->element('Task/Board/column', array(
		'tasks' => $resolved, 
		'status' => 'resolved', 
		'title' => __('Resolved'), 
		'tooltip' => __('Tasks that are finished'), 
		'span' => $colSpan, 
		'task_span' => 12, 
		'classes' => 'sprintboard-column', 
		'draggable' => $hasWrite,
		'includeMilestoneLabel' => false,
		'localStoryLink' => true,
		'total' => $milestone['Tasks']['resolved']['points'] + $milestone['Tasks']['closed']['points'],
	));

} ?>

</div>

<div class="row-fluid">
	<?= $this->element('Task/Board/column', array(
		'tasks' => $dropped, 
		'status' => 'dropped', 
		'title' => __('Deferred tasks'), 
		'tooltip' => __('Tasks that we did not have time for, we will work on them in a later milestone'), 
		'span' => '12', 
		'task_span' => $colSpan, 
		'classes' => 'sprintboard-icebox', 
		'draggable' => $hasWrite,
		'includeMilestoneLabel' => false,
		'localStoryLink' => true,
	)) ?>

</div>

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
<? if($sourcekettle_config['Features']['story_enabled']['value']) {?>

	<h3><?=__("User stories related to this milestone")?></h3>
	<? foreach ($stories as $story) { ?>
		<div class="row-fluid">
		<?= $this->element('Story/block', array(
			'story' => $story,
			'includeTasks' => true,
			'includeAnchor' => true,
			'localTaskLink' => true,
			'milestoneId' => $milestone['Milestone']['id'],
		)) ?>
		</div>
	<? } ?>
<? } ?>
