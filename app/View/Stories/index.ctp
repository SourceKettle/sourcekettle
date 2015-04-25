<?php
$this->Html->css('tasks', null, array ('inline' => false));
$this->Html->css('stories', null, array ('inline' => false));

?>

<div class="row-fluid">
    <?= $this->Element("Story/topbar") ?>
</div>

<table class="story-map table">

<? foreach ($stories as $story) { ?>
	<?=$this->element('Story/block', array('story' => $story))?>
<? } ?>


</table>
