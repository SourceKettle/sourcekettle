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
?>

<div class="story-block well">
	<h4><?=$this->Html->link(
		h($this->Text->truncate($story['Story']['subject'], 20)), array(
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
	<p>
		<?=h($this->Text->truncate($story['Story']['description'], 100))?>
	</p>
	<p>
		<?=__("%d/%d story points complete", $pointsComplete, $pointsTotal)?>
	</p>

	<? if (@$includeTasks) {?>
		<? foreach($story['Task'] as $task) {?>
			<?=$this->element('Task/minilozenge', array('task' => array('Task' => $task, 'Project' => $project['Project'])))?>
		<? } ?>
	<? } ?>
</div>
