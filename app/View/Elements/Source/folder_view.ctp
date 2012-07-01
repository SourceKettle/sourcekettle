<?php
    // Icons for object types
    $icons = array(
        'blob' => 'file',
        'tree' => 'folder-open',
    );
?>
<div class="span8">
    <?= $this->Bootstrap->breadcrumbs(array("divider" => "/")) ?>
</div>
<div class="span2">
    <?= $this->Bootstrap->button_dropdown($this->Bootstrap->icon('random')." <strong>Branch: </strong>".substr($branch, 0, 10), array("class" => "branch_button", "links" => $branches)) ?>
</div>
<div class="span10">
    <table class="well table table-striped">
    <? foreach ($files as $file) : ?>
        <? $url[] = $file['name']; ?>
        <tr>
            <td><?= $this->Bootstrap->icon((isset($icons[$file['type']])) ? $icons[$file['type']] : 'warning-sign').' '.$this->Html->link($file['name'], $url, array('escape' => false)) ?></td>
        </tr>
        <? array_pop($url); ?>
    <? endforeach; ?>
    </table>
</div>
