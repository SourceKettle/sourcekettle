<?php
/**
 *
 * Settings element for APP/settings/admin_index for the SourceKettle system
 * View will render global configuration settings
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Setting
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="well">
    <h3><?= __('System-wide configuration options') ?></h3>
	<p><?=__('These options are set system-wide and cannot be overridden by user or project settings.')?></p>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="60%"><?= __('Description') ?></th>
                <th><?= __('Options') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <h4><?= __('Allow Registration') ?> <small>- <?= __('allow new users to create accounts') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => false, 'id' => 'register-enabled', 'name' => 'Users,register_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => true)), 'value' => $sourcekettle_config['Users']['register_enabled']['value'])) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= __('Support email address') ?> <small>- <?= __('users will be told to email this address for tech support') ?></small></h4>
                </td>
                <td>
					<?= $this->element('Setting/text_fields', array('action'=>'setEmail', 'items' => array(
						'sysadmin_email' => $sourcekettle_config['Users']['sysadmin_email']['value'],
					))) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= __('Sending email address') ?> <small>- <?= __('emails sent by the system will come from this address') ?></small></h4>
                </td>
                <td>
					<?= $this->element('Setting/text_fields', array('action'=>'setEmail', 'items' => array(
						'send_email_from' => $sourcekettle_config['Users']['send_email_from']['value'],
					))) ?>
                </td>
            </tr>


        </tbody>
    </table>
</div>
