<?php
/**
 *
 * View class for APP/ssh_keys/view for the SourceKettle system
 * Displays all the users SSH Keys.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          https://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.SSH_Keys
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header($this->request->data['User']['name']);

?>

<div class="row-fluid">
    <div class="span2">
        <?= $this->element('Sidebar/users') ?>
    </div>
    <div class="span10">
            <table class="well table table-striped">
                <thead>
                    <tr>
                        <th>Comment</th>
                        <th>Key</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                <? if (empty($this->request->data['SshKey'])) : ?>
                    <tr>
                        <td colspan="3" style="text-align:center">Nothing here yet! <?= $this->Html->link('Add a key here...', array('controller' => 'sshKeys', 'action' => 'add')) ?></td>
                    </tr>
                <? endif; ?>
                <? foreach ($this->request->data['SshKey'] as $key) : 
					// Remove middle of SSH key for display, showing mostly the end
					$truncated_key =  $this->Text->truncate(h($key['key']), 15);
					$truncated_key .= $this->Text->tail(h($key['key']), 25, array('ellipsis' => ''));?>
                    <tr>
                        <td><?= h($key['comment']) ?></td>
                        <td style='overflow: hidden; max-width: 388px; word-wrap: break-word;'><tt><?=$truncated_key?></tt></td>
                        <td class='span1'><?= $this->Bootstrap->button_form("Delete", $this->Html->url(array('controller' => 'sshKeys', 'action' => 'delete' , $key['id']), true), array('style' => 'danger'), "Are you sure you want to delete the SSH key '" . h($key['comment']) . "'?") ?></td>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
    </div>
</div>
