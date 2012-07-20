<?php
    // Icons for object types
    $icons = array(
        'blob' => 'file',
        'tree' => 'folder-open',
        'commit' => 'share',
    );
?>
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
            <?php
                if ($file['type'] != 'commit') {
                    $link = $this->Html->link($file['name'], $url, array('escape' => false));
                } else {
                    if (preg_match('#(git|http)://(?P<url>\S+)#', $file['remote'], $match)){
                        $link = $this->Html->link($file['name'], 'http://'.$match['url'], array('escape' => false));
                    } else {
                        $link = $file['name']; 
                    }
                }
            ?>
            <td><?= $this->Bootstrap->icon((isset($icons[$file['type']])) ? $icons[$file['type']] : 'warning-sign') ?> <?= $link ?></td>
            <td><?= $this->Time->timeAgoInWords($file['updated']) ?></td>
            <td><?= substr(ucfirst($file['message']), 0, 100) ?></td>
        </tr>
        <? array_pop($url); ?>
    <? endforeach; ?>
    </table>
</div>
