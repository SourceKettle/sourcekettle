<?php
    // Icons for object types
    $icons = array(
        'blob' => 'file',
        'tree' => 'folder-open',
    );
?>
<div class="span10">
    <?= $this->Bootstrap->breadcrumbs(array("divider" => "/")) ?>
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
