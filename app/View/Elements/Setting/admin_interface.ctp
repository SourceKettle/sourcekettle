<?php
/**
 *
 * Settings element for APP/settings/admin_index for the SourceKettle system
 * View will render global project settings
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
    <h3><?= __('User interface settings') ?></h3>
	<p><?=__('These are the default settings for the system. They may be overridden by individual user or project settings. Locking the setting will stop it from being overridden.')?></p>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="70%"><?= __('Description') ?></th>
                <th><?= __('Options') ?></th>
                <th><?= __('Locked?') ?></th>
            </tr>
        </thead>
        <tbody>
			<?= $this->Form->create('Setting', array('url' => array('action'=>'set'))) ?>
            <tr>
                <td>
                    <h4><?= __('Theme') ?> <small>- <?= __('Customise the appearance of %s', $sourcekettle_config['UserInterface']['alias']['value']) ?></small></h4>
                </td>
                <td>
					<?= $this->element('Setting/themes') ?>
                </td>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => true, 'id' => 'theme', 'name' => 'Setting.UserInterface.theme', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => true, 'lock')), 'value' => $sourcekettle_config['UserInterface']['theme']['locked'])) ?>
                </td>
            </tr>
			<?/*= $this->element('Setting/text_fields', array(
				'items' => array(
					array(
						'name' => 'Setting.UserInterface.terminology',
						'label' => __('Terminology'),
						'description' => __('use the terminology from your favourite agile methodology'),
						'value' => $sourcekettle_config['UserInterface']['terminology']['value'],
						'readOnly' => false,
					),
				)
			))*/?>
	</tr>
<?= $this->Form->end() ?>
        </tbody>
    </table>
</div>
