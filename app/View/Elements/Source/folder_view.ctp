<?php
    // Icons for object types
    $icons = array(
        'blob' => 'file',
        'tree' => 'folder-open',
        'commit' => 'share',
    );
?>
<div class="span10">
    <?= $this->Bootstrap->breadcrumbs(array("divider" => "/")) ?>
</div>
<div class="span10">
    <table class="well table table-striped">
        <tr>
            <th><?= $this->Bootstrap->icon(null) ?> name</th>
            <th>edited</th>
            <th>message</th>
        </tr>
    <? foreach ($files as $file) : ?>
        <? $url[] = $file['name']; ?>
        <tr>
            <td><?= $this->Bootstrap->icon((isset($icons[$file['type']])) ? $icons[$file['type']] : 'warning-sign').' '.$this->Html->link($file['name'], $url, array('escape' => false)) ?></td>
            <td><?= $this->Time->timeAgoInWords($file['updated']) ?></td>
            <td><?= substr(ucfirst($file['message']), 0, 100) ?></td>
        </tr>
        <? array_pop($url); ?>
    <? endforeach; ?>
    </table>
</div>
