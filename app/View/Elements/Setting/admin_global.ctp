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
                    <?= $this->element('Setting/switch', array('lock' => false, 'id' => 'register-enabled', 'model' => 'Setting', 'name' => 'Users.register_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => true)), 'value' => $sourcekettle_config['Users']['register_enabled']['value'])) ?>
                </td>
            </tr>
			<?= $this->element('Setting/text_fields', array(
				'url' => array('controller' => 'settings', 'action' => 'set', 'admin' => true),
				'model' => 'Setting',
				'items' => array(
					array(
						'name' => 'Users.sysadmin_email',
						'label' => __('Support email address'),
						'description' => __('Users will be told to email this address for tech support'),
						'value' => $sourcekettle_config['Users']['sysadmin_email']['value'],
						'readOnly' => false,
					),
					array(
						'name' => 'Users.send_email_from',
						'label' => __('Sending email address'),
						'description' => __('Emails sent by the system will come from this address'),
						'value' => $sourcekettle_config['Users']['send_email_from']['value'],
						'readOnly' => false,
					),
					array(
						'name' => 'UserInterface.alias',
						'label' => __('System alias'),
						'description' => __("Update the system name, if you prefer not to call it 'SourceKettle'"),
						'value' => $sourcekettle_config['UserInterface']['alias']['value'],
						'readOnly' => false,
					),
				),
			)) ?>


        </tbody>
    </table>
</div>
