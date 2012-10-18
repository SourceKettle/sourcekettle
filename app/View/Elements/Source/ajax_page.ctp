<?php
$this->Html->scriptBlock("$('.progress').fadeIn('slow')", array('inline' => false));
?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <?= $this->element('Source/topbar', array('branches' => $branches, 'branch' => $branch)) ?>
        <?= ((isset($header)) ? $header : '') ?>
        <div id="slider" class="span10">
            <div class="row-fluid">
                <div class=" span2 offset5">
                    <div class="progress progress-striped active hide">
                        <div class="bar" style="width: 100%;">Loading</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
