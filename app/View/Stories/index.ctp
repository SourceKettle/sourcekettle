<?php
$this->Html->css('tasks', null, array ('inline' => false));
$this->Html->css('stories', null, array ('inline' => false));

?>

<div class="row-fluid">
    <?= $this->Element("Story/topbar") ?>
</div>

<div class="row-fluid">
<?
$i = 0;
foreach ($stories as $story) {
	if ($i++ % 2 == 0) {
		echo '</div><div class="row-fluid">';
	}
	echo $this->element('Story/block', array('story' => $story, 'span' => 6));
}?>

</div>
