<?php
    // Loose ends to tie up
    $url['action'] = 'raw';
?>
<div class="span7">
    <?= $this->Bootstrap->breadcrumbs(array("divider" => "/")) ?>
</div>
<div class="span1">
    <?= $this->Html->link("raw", $url, array("class" => "btn btn-default raw_button")) ?>
</div>
<div class="span2">
    <?= $this->Bootstrap->button_dropdown($this->Bootstrap->icon('random')." <strong>Branch: </strong>".substr($branch, 0, 10), array("class" => "branch_button", "links" => $branches)) ?>
</div>
<div class="span10">
    <?= $this->Geshi->highlight('<pre lang="php">'.htmlentities($source).'</pre>') ?>
</div>
