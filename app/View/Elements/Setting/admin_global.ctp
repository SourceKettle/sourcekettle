<?php
/**
 *
 * Settings element for APP/settings/admin_index for the DevTrack system
 * View will render global configuration settings
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements.Setting
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="well">
    <h3><?= $this->DT->t('global.header.text') ?></h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="60%"><?= $this->DT->t('table.col1') ?></th>
                <th><?= $this->DT->t('table.col2') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <h4><?= $this->DT->t('global.register.text') ?> <small>- <?= $this->DT->t('global.register.description') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/on_off_buttons', array('action'=>'setRegistration', 'value' => $register)) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= $this->DT->t('global.email.text') ?> <small>- <?= $this->DT->t('global.email.description') ?></small></h4>
                </td>
                <td>
                <?= $this->Form->create('Settings', array('action'=>'setEmail')) ?>
                    <div class="input-append">
                        <?= $this->Form->text("sysadmin_email", array('id' => 'appendedInputButton', 'class' => 'xlarge', "value" => $sysadmin_email)) ?>
                        <?= $this->Bootstrap->button("Update", array('escape' => false, 'style' => 'primary')) ?>
                    </div>
                <?= $this->Form->end() ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>