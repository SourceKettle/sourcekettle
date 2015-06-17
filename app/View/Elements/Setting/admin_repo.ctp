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
    <h3><?= __('Source repository settings') ?></h3>
	<div class="alert alert-error"><?=__("Caution! Changing these settings after the system is installed will probably cause a lot of trouble! Do not touch unless you're SURE you know what you're doing!")?></div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="60%"><?= __('Description') ?></th>
                <th><?= __('Options') ?></th>
            </tr>
        </thead>
        <tbody>
			<?= $this->element('Setting/text_fields', array(
				'model' => 'Setting',
				'url' => array('controller' => 'settings', 'action' => 'set', 'admin' => true),
				'items' => array(
					array(
						'name' => 'SourceRepository.base',
						'label' => __('Repository location'),
						'description' => __('Where the repositories are stored on disk'),
						'value' => $sourcekettle_config['SourceRepository']['base']['value'],
						'readOnly' => false,
					),
					array(
						'name' => 'SourceRepository.user',
						'label' => __('SSH user'),
						'description' => __('Users will connect to their repositories via SSH using this username and their SSH key'),
						'value' => $sourcekettle_config['SourceRepository']['user']['value'],
						'readOnly' => false,
					),
				),
			)) ?>

        </tbody>
    </table>
</div>
