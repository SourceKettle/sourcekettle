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
    <h3><?= __('Default values') ?></h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="60%"><?= __('Description') ?></th>
                <th><?= __('Options') ?></th>
            </tr>
        </thead>
        <tbody>
			<? // TODO should be dropdown lists of the available things ?>
			<?= $this->element('Setting/text_fields', array(
				'items' => array(
					array(
						'name' => 'Setting.Defaults.task_type',
						'label' => __('Task type'),
						'description' => __('When adding a new task, which task type is pre-selected'),
						'value' => $sourcekettle_config['Defaults']['task_type']['value'],
					),
					array(
						'name' => 'Setting.Defaults.task_priority',
						'label' => __('Task priority'),
						'description' => __('When adding a new task, which priority is pre-selected'),
						'value' => $sourcekettle_config['Defaults']['task_priority']['value'],
					),
					array(
						'name' => 'Setting.Defaults.task_status',
						'label' => __('Task status'),
						'description' => __('When adding a new task, which task status is pre-selected'),
						'value' => $sourcekettle_config['Defaults']['task_status']['value'],
					),
				),
			)) ?>

        </tbody>
    </table>
</div>
