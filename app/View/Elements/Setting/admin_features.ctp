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
    <h3><?= __('Global project settings') ?></h3>
    <div class="alert alert-info">
        <?= __('<strong>Warning!</strong> modifying these settings will restrict <strong>ALL</strong> projects, not just new ones.') ?>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="70%"><?= __('Description') ?></th>
                <th><?= __('Options') ?></th>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td>
                    <h4><?= __('Time tracking') ?> <small>- <?= __('allow logging of time to projects and tasks') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/on_off_buttons', array('action'=>'setFeatureTime', 'value' => $features['time'])) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= __('Source code management') ?> <small>- <?= __('allow creation of a source code repository for a project') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/on_off_buttons', array('action'=>'setFeatureSource', 'value' => $features['source'])) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= __('Task management') ?> <small>- <?= __('allow users to add tasks and milestones to track progress') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/on_off_buttons', array('action'=>'setFeatureTask', 'value' => $features['task'])) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= __('File uploads') ?> <small>- <?= __('allow users to upload files to projects') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/on_off_buttons', array('action'=>'setFeatureAttachment', 'value' => $features['attachment'])) ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
