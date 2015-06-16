<?php
$link_edit = $this->Html->link(
	$this->Bootstrap->icon('pencil'),
	array(
		'controller' => 'stories',
		'action' => 'edit',
		'project' => $story['Project']['name'],
		$story['Story']['public_id']
	),
	array(
		'class' => 'close edit',
		'title' => __('Edit story'),
		'escape' => false
	)
);
$link_addtask = $this->Html->link(
	$this->Bootstrap->icon('file'),
	array(
		'project' => $story['Project']['name'],
		'controller' => 'tasks',
		'action' => 'add',
		'?' => array('story' => $story['Story']['public_id']),
	),
	array(
		'class' => 'close edit',
		'title' => __('Add a new task to the story'),
		'escape' => false
	)
);
$link_remove = $this->Html->link(
	$this->Bootstrap->icon('remove-circle'),
	array(
		'controller' => 'stories',
		'action' => 'delete',
		'project' => $story['Project']['name'],
		$story['Story']['public_id']
	),
	array(
		'class' => 'close delete story-quicklink',
		'title' => __('Delete story'),
		'escape' => false
	)
);
$pointsTotal = array_sum(array_map(function($a){return $a['story_points'];}, $story['Task']));
$pointsComplete = array_sum(array_map(function($a){
	if(in_array($a['TaskStatus']['name'], array('resolved', 'closed'))) {
		return $a['story_points'];
	}
}, $story['Task']));
$pointsPct = ($pointsTotal == 0 ? 0 : (int) ($pointsComplete/$pointsTotal * 100));
$localTaskLink = isset($localTaskLink) ?: false;
$milestoneId = isset($milestoneId) ? $milestoneId : 0;
$span = isset($span) ? $span : 12;
?>
<div class="story-block well span<?=$span?>" id="story_<?=$story['Story']['public_id']?>">
       	<?= $this->Bootstrap->progress(array("width" => $pointsPct, "striped" => true)) ?>
	<a name="story_<?=$story['Story']['public_id']?>"></a>
	<h4><?=$this->Html->link(
		h($story['Story']['subject']), array(
			"controller" => "stories",
			"action" => "view",
			"project" => $story['Project']['name'],
			$story['Story']['public_id']
		)
	)?>
	<?=$link_remove?>
	<?=$link_addtask?>
	<?=$link_edit?>
	</h4>
    <ul class="unstyled">
	<li><?=h($this->Text->truncate($story['Story']['description']), 150)?></li>
	<li><em><?=__("%d/%d story points complete (%d%%)", $pointsComplete, $pointsTotal, $pointsPct) ?></em></li>
    </ul>

	<? if (@$includeTasks) {?>
	<div class="row-fluid">
	<ul class="sprintboard-droplist span12">
		<? foreach($story['Task'] as $task) {
			echo $this->element('Task/minilozenge', array(
				'task' => array('Task' => $task, 'Project' => $project['Project']),
				'span' => 3,
				'milestoneId' => $milestoneId,
				$localTaskLink,
			));
		} ?>
	</ul>
	</div>
	<? } ?>
</div>
