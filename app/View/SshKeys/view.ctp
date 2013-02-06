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

echo $this->Bootstrap->page_header($this->request->data['User']['name']); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/users') ?>
    </div>
    <div class="span6">
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
                <? foreach ($this->request->data['SshKey'] as $key) : ?>
                    <tr>
                        <td><?= h($key['comment']) ?></td>
                        <td style='overflow: hidden; max-width: 388px; word-wrap: break-word;'><tt><?= $this->Text->truncate(h($key['key']), 40) ?></tt></td>
                        <td class='span1'><?= $this->Bootstrap->button_form("Delete", $this->Html->url(array('controller' => 'sshKeys', 'action' => 'delete' , $key['id']), true), array('style' => 'danger'), "Are you sure you want to delete the SSH key '" . h($key['comment']) . "'?") ?></td>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
    </div>
    <div class="span4">
        <h3>What be all the hype about SSH keys?</h3>
        <p>
            Here at SourceKettle, we think the world of computers moves pretty fast! It's hard for us to keep up with all the lastest and greatest tech, and as such,
            if you would like to know about SSH keys and how they work on your device, please head over to Google.
        </p>
        <p>
            You'll get far with a search such as:<br>'How to setup SSH Keys on [operating system here]'<br>
        </p>
        <p>
            We can however tell you the following:
            <dl>
                <dt>Public keys only</dt>
                <dd>Please don't give us your private keys! That's like giving out the PIN for your credit card!</dd>
                <dt>Public keys look somthing like this</dt>
                <dd>
                ssh-rsa A3AAB3j7nxirGz8Z2bddNdMm0UB/uEFZa
                tasKgDQrOEvJ9LQjMq2qolTBzROgdg6Mo9DsWZCq4
                Q48p06JyQLbMx7hKuZkBH0d5jxeTGEGW4utk3E/==
                <br>
                But longer...
            </dd>
            </dl>
        </p>
</div>
