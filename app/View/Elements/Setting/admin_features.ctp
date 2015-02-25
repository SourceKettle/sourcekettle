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
    <h3><?= __('Features') ?></h3>
	<p><?=__('Various features can be enabled or disabled. They may be overridden by individual project settings. Locking the setting will stop it from being overridden.')?></p>
    <div class="alert alert-info">
        <?= __('<strong>Warning!</strong> modifying these settings will restrict <strong>ALL</strong> projects, not just new ones.') ?>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="70%"><?= __('Description') ?></th>
                <th><?= __('Options') ?></th>
                <th><?= __('Locked?') ?></th>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td>
                    <h4><?= __('Time tracking') ?> <small>- <?= __('allow logging of time to projects and tasks') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => false, 'id' => 'time-enabled', 'name' => 'Setting.Features.time_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => 'true')), 'sectionHide' => 'timeSection', 'value' => $sourcekettle_config['Features']['time_enabled']['value'])) ?>
                </td>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => true, 'id' => 'time-enabled', 'name' => 'Setting.Features.time_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => 'true', 'lock')), 'sectionHide' => 'timeSection', 'value' => $sourcekettle_config['Features']['time_enabled']['locked'])) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= __('Source code management') ?> <small>- <?= __('allow creation of a source code repository for a project') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => false, 'id' => 'source-enabled', 'name' => 'Setting.Features.source_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => 'true')), 'sectionHide' => 'sourceSection', 'value' => $sourcekettle_config['Features']['source_enabled']['value'])) ?>
                </td>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => true, 'id' => 'source-enabled', 'name' => 'Setting.Features.source_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => 'true', 'lock')), 'sectionHide' => 'sourceSection', 'value' => $sourcekettle_config['Features']['source_enabled']['locked'])) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= __('Task management') ?> <small>- <?= __('allow users to add tasks and milestones to track progress') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => false, 'id' => 'task-enabled', 'name' => 'Setting.Features.task_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => 'true')), 'sectionHide' => 'taskSection', 'value' => $sourcekettle_config['Features']['task_enabled']['value'])) ?>
                </td>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => true, 'id' => 'task-enabled', 'name' => 'Setting.Features.task_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => 'true', 'lock')), 'sectionHide' => 'taskSection', 'value' => $sourcekettle_config['Features']['task_enabled']['locked'])) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= __('User stories') ?> <small>- <?= __('Allow creation of user stories (NB will be unavailable if task tracking is disabled!)') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => false, 'id' => 'story-enabled', 'name' => 'Setting.Features.story_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => 'true')), 'sectionHide' => 'storySection', 'value' => $sourcekettle_config['Features']['story_enabled']['value'])) ?>
                </td>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => true, 'id' => 'task-enabled', 'name' => 'Setting.Features.task_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => 'true', 'lock')), 'sectionHide' => 'taskSection', 'value' => $sourcekettle_config['Features']['task_enabled']['locked'])) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= __('File uploads') ?> <small>- <?= __('allow users to upload files to projects') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => false, 'id' => 'attachment-enabled', 'name' => 'Setting.Features.attachment_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => 'true')), 'sectionHide' => 'attachmentSection', 'value' => $sourcekettle_config['Features']['attachment_enabled']['value'])) ?>
                </td>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => true, 'id' => 'attachment-enabled', 'name' => 'Setting.Features.attachment_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => 'true', 'lock')), 'sectionHide' => 'attachmentSection', 'value' => $sourcekettle_config['Features']['attachment_enabled']['locked'])) ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
