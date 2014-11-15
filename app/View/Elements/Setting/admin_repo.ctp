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
            <tr>
                <td>
                    <h4><?= __('Repository location') ?> <small>- <?= __('Where the repositories are stored on disk') ?></small></h4>
                </td>
                <td>
					<?= $this->element('Setting/text_fields', array('action'=>'setEmail', 'items' => array(
						'sysadmin_email' => $sourcekettle_config['SourceRepository']['base']['value'],
					))) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= __('SSH user') ?> <small>- <?= __('Users will connect using this username and their SSH key') ?></small></h4>
                </td>
                <td>
					<?= $this->element('Setting/text_fields', array('action'=>'setEmail', 'items' => array(
						'send_email_from' => $sourcekettle_config['SourceRepository']['user']['value'],
					))) ?>
                </td>
            </tr>


        </tbody>
    </table>
</div>
