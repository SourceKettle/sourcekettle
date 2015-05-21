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

$this->Html->css('tasks', null, array ('inline' => false));
$this->Html->css("milestones.index", null, array ('inline' => false));
?>
<?= $this->Task->allDropdownMenus() ?>

<div class="row-fluid">
	<?= $this->element('Milestone/topbar', array('name' => $milestone['Milestone']['subject'], 'id' => $milestone['Milestone']['id'])) ?>
</div>

	
<div class="row-fluid">
	<div class="span2 offset5">
	<span class="label">Story points complete: <span id="points_complete"><?=$points_complete?></span> / <span id="points_total"><?=$points_total?></span></span>
	</div>
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
		'localStoryLink' => true,
	)) ?>

</div>

<? if($sourcekettle_config['Features']['story_enabled']['value']) {?>

	<h3><?=__("User stories")?></h3>
	<? foreach ($stories as $story) { ?>
		<div class="row-fluid">
		<a name="story_<?=$story['Story']['public_id']?>"></a>
		<?= $this->element('Story/block', array(
			'story' => $story,
			'includeTasks' => true,
			'localTaskLink' => true,
		)) ?>
		</div>
	<? } ?>
<? } ?>
