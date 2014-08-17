<?php
/**
 *
 * Element for APP/attachments/[index|video|image|other] for the SourceKettle system
 * Renders a table of files
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Attachment
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

?>
<table class="well table table-striped">
    <tr>
        <th><?= __("Filename") ?></th>
        <th width="10%"><?=__("Size")?></th>
        <th width="20%"><?= __("Created")?> </th>
        <? if ($isAdmin) : ?>
            <th width="10%"><?=__("Options")?></th>
        <? endif; ?>
    </tr>
<? foreach ($attachments as $attachment) :
    $link = array(
        'project' => $project['Project']['name'],
        'action' => 'view',
        $attachment['Attachment']['id']
    );
    $delete = array(
        'project' => $project['Project']['name'],
        'action' => 'delete',
        $attachment['Attachment']['id']
    );

    $size = $attachment['Attachment']['size'];
    $unit = 1;

    while ($size > 1024) {
        $unit++;
        $size = $size / 1024;
    }

    switch ($unit) {
        case 1:
            $unit = 'b';
            break;
        case 2:
            $unit = 'Kb';
            break;
        case 3:
            $unit = 'Mb';
            break;
        case 4:
            $unit = 'Gb';
            break;
        case 5:
            $unit = 'Tb';
            break;
    }
?>
    <tr>
        <td><?= $this->Html->link($attachment['Attachment']['name'], $link) ?></td>
        <td><?= round($size, 1) ?> <?= $unit ?></td>
        <td><?= $this->Time->timeAgoInWords($attachment['Attachment']['created']) ?></td>
        <? if ($isAdmin) : ?>
            <td><?= $this->Bootstrap->button_form($this->Bootstrap->icon('eject', 'white'), $delete, array('size'=>'mini', 'style'=>'danger', 'escape'=>false)) ?></td>
        <? endif; ?>
    </tr>
<? endforeach; ?>
</table>
