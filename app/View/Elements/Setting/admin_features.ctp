<?php
/**
 *
 * Settings element for APP/settings/admin_index for the DevTrack system
 * View will render global project settings
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Setting
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="well">
    <h3><?= $this->DT->t('projects.header.text') ?></h3>
    <div class="alert alert-info">
        <?= $this->DT->t('projects.warning') ?>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="70%"><?= $this->DT->t('table.col1') ?></th>
                <th><?= $this->DT->t('table.col2') ?></th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($features as $feature => $val) : ?>
            <tr>
                <td>
                    <h4><?= $this->DT->t('projects.'.$feature.'.text') ?> <small>- <?= $this->DT->t('projects.'.$feature.'.description') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/on_off_buttons', array('action'=>'setFeature'.ucfirst($feature), 'value' => $val)) ?>
                </td>
            </tr>
            <? endforeach; ?>
        </tbody>
    </table>
</div>
