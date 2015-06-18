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
		<p><?=$this->Html->link($this->Text->truncate($milestone['Milestone']['subject'], 30), array(
			'controller' => 'milestones',
			'action' => 'view',
			'project' => $project['Project']['name'],
			$milestone['Milestone']['id'],
		))?></p>
		<p><?=__("%d story points", $points)?></p>
	</th>
	<? foreach ($stories as $story) { ?>
		<td>
		<ul class="well sprintboard-droplist" data-milestone="<?=h($milestone['Milestone']['id'])?>" data-story="<?=h($story['Story']['public_id'])?>">
		<? foreach ($milestone['Task'] as $task) {
			if ($task['story_id'] != $story['Story']['id']) {
				continue;
			}
			echo $this->element("Task/minilozenge", array("span" => 12, "projectName" => $project['Project']['name'], "task" => array('Task'=>$task,), "draggable" => true));
		} ?>
		</ul>
		</td>
	<? } ?>
</tr>
<? } ?>

<tr>
<? $formUrl = $this->Html->url(array("controller" => "milestones", "action" => "add", "project" => $project['Project']['name']))?>
	<?= $this->Form->create('Milestone', array("url" => $formUrl, "type" => "get"))?>
	<th><?=__("No milestone")?> <?=$this->Form->submit(__("New milestone"))?></th>
	<? foreach ($stories as $story) { ?>
		<td>
		<ul class="well sprintboard-droplist" data-milestone="0" data-story="<?=h($story['Story']['public_id'])?>">
		<? foreach ($story['Task'] as $task) {
			if ($task['milestone_id'] != 0) {
				continue;
			}
			echo $this->element("Task/minilozenge", array("checkbox" => true, "span" => 12, "projectName" => $project['Project']['name'], "task" => array('Task'=>$task), "draggable" => true));
		} ?>
		</ul>
		</td>
	<? } ?>
	<?= $this->Form->end() ?>
</tr>

</table>
