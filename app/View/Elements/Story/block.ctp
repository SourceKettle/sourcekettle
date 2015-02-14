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
?>

<div class="story-block well span3">
	<h4><?=$this->Html->link(
		h($story['Story']['subject']), array(
			"controller" => "stories",
			"action" => "view",
			"project" => $story['Project']['name'],
			$story['Story']['public_id']
		)
	)?>
	<?=$link_remove?>
	<?=$link_edit?>
	</h4>
	<p>
		<?=h($this->Text->truncate($story['Story']['description'], 100))?>
	</p>
</div>
