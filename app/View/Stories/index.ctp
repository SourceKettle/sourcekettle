<?php
$this->Html->css('tasks', null, array ('inline' => false));
$this->Html->css('stories', null, array ('inline' => false));

?>

<div class="row-fluid">
    <?= $this->Element("Story/topbar") ?>
</div>

<table class="story-map table">

<tr>
<th></th>
<? foreach ($stories as $story) { ?>
	<td><?=$this->element('Story/block', array('story' => $story))?></td>
<? } ?>
</tr>

<? foreach ($milestones as $milestone) {
	$points = array_sum(array_map(function($a){return $a['story_points'];}, $milestone['Task']));
?>
<tr>
	<th>
		<p><?=$milestone['Milestone']['subject']?></p>
		<p><?=__("%d story points", $points)?></p>
	</th>
	<? foreach ($stories as $story) { ?>
		<td>
		<ul class="well sprintboard-droplist" data-milestone="<?=h($milestone['Milestone']['id'])?>" data-story="<?=h($story['Story']['public_id'])?>">
		<? foreach ($milestone['Task'] as $task) {
			if ($task['story_id'] != $story['Story']['id']) {
				continue;
			}
			echo $this->element("Task/minilozenge", array("projectName" => $project['Project']['name'], "task" => array('Task'=>$task,), "draggable" => true));
		} ?>
		</ul>
		</td>
	<? } ?>
</tr>
<? } ?>

<tr>
	<th><?=__("No milestone")?></th>
	<? foreach ($stories as $story) { ?>
		<td>
		<ul class="sprintboard-droplist">
		<? foreach ($story['Task'] as $task) {
			if ($task['milestone_id'] != 0) {
				continue;
			}
			echo $this->element("Task/minilozenge", array("projectName" => $project['Project']['name'], "task" => array('Task'=>$task)));
		} ?>
		</ul>
		</td>
	<? } ?>
</tr>

</table>
