
<div class="row-fluid">
    <?= $this->Element("Story/topbar") ?>
</div>

<? foreach ($stories as $story) {
	echo $this->element('Story/block', array('story' => $story));
} ?>
