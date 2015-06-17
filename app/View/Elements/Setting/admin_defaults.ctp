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
		<?= $this->element('Setting/dropdown_fields', array(
			'model' => 'Setting',
			'url' => array('controller' => 'settings', 'action' => 'set', 'admin' => true),
			'items' => array(
				array(
					'name' => 'Defaults.task_type',
					'label' => __('Task type'),
					'description' => __('When adding a new task, which task type is pre-selected'),
					'value' => $sourcekettle_config['Defaults']['task_type']['value'],
					'options' => $task_types,
					'locked' => $sourcekettle_config['Defaults']['task_type']['locked'],
					'readOnly' => false,
				),
				array(
					'name' => 'Defaults.task_priority',
					'label' => __('Task priority'),
					'description' => __('When adding a new task, which priority is pre-selected'),
					'value' => $sourcekettle_config['Defaults']['task_priority']['value'],
					'options' => $task_priorities,
					'locked' => $sourcekettle_config['Defaults']['task_priority']['locked'],
					'readOnly' => false,
				),
				array(
					'name' => 'Defaults.task_status',
					'label' => __('Task status'),
					'description' => __('When adding a new task, which task status is pre-selected'),
					'value' => $sourcekettle_config['Defaults']['task_status']['value'],
					'options' => $task_statuses,
					'locked' => $sourcekettle_config['Defaults']['task_status']['locked'],
					'readOnly' => false,
				),
			),
			'addLock' => true,
		)) ?>
		<?/*= $this->element('Setting/text_fields', array(
			'model' => 'Setting',
			'url' => array('controller' => 'settings', 'action' => 'set', 'admin' => true),
			'items' => array(
				array(
					'name' => 'Defaults.time_minutes',
					'label' => __('Time length'),
					'description' => __('When logging time, default number of minutes to select'),
					'value' => $sourcekettle_config['Defaults']['time_minutes']['value'],
					'locked' => $sourcekettle_config['Defaults']['time_minutes']['locked'],
					'readOnly' => false,
				),
			),
			'addLock' => true,
		)) */?>

        </tbody>
    </table>
</div>
