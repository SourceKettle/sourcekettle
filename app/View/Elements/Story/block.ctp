<?php
$link_edit = $this->Html->link(
	$this->Bootstrap->icon('pencil'),
	array(
		'project' => $story['Project']['name'],
		'action' => 'edit',
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
		'project' => $story['Project']['name'],
		'action' => 'delete',
		$story['Story']['public_id']
	),
	array(
		'class' => 'close delete story-quicklink',
		'title' => __('Delete story'),
		'escape' => false
	)
);
$points = array_sum(array_map(function($a){return $a['story_points'];}, $story['Task']));
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
		<?=__("%d story points", $points)?>
	</p>
</div>
