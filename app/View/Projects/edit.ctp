<?php
/**
 *
 * View class for APP/projects/edit for the SourceKettle system
 * Edit allows editing of a specific project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          https://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Projects
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->Html->css('bootstrap-switch-2.0.1/build/css/bootstrap2/bootstrap-switch.min.css', null, array ('inline' => false));
$this->Html->script('bootstrap-switch.min', array ('inline' => false));
$this->Html->script('switches', array ('inline' => false));

$smallText = " <small>Edit Project</small>";
debug($sourcekettle_config['Features']);
echo $this->Bootstrap->page_header($project['Project']['name'] . $smallText);?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row-fluid">
            <div class="span7">
                <div class="well">
                    <h3><?=__("Project description")?></h3>
                    <?=$this->Form->create('Project', array('class' => 'form-inline')); ?>
					<?=$this->Bootstrap->input("description", array( 
						"input" => $this->Markitup->editor("description", array(
							"class" => "span7",
							"label" => false,
						)),
						"label" => false,
					));?>

                    <h3><?=__("Is the project public?")?></h3>

                    <p><?= $this->Form->checkbox("public") ?> <?=__("Yes, I would like to allow other SourceKettle users to browse my project")?></p>

                    <?=$this->Bootstrap->button("Submit", array("style" => "primary", 'class' => 'controls'));?>

                    <?=$this->Form->end();?>
                </div>
            </div>
            <div class="span5">

                <div class="well">
                    <h3><?=__("Features")?></h3>
					<p><?=__("Please note that some features may be locked system-wide, meaning you will not be able to enable/disable them.")?></p>
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
                    <?= $this->element('Setting/switch', array('lock' => false, 'id' => 'time-enabled', 'name' => 'Features,time_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'projectSet')), 'sectionHide' => 'timeSection', 'value' => $sourcekettle_config['Features']['time_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['time_enabled']['locked'])) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= __('Source code management') ?> <small>- <?= __('allow creation of a source code repository for a project') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => false, 'id' => 'task-enabled', 'name' => 'Features,task_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'projectSet')), 'sectionHide' => 'taskSection', 'value' => $sourcekettle_config['Features']['task_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['task_enabled']['locked'])) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= __('Task management') ?> <small>- <?= __('allow users to add tasks and milestones to track progress') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => false, 'id' => 'source-enabled', 'name' => 'Features,source_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'projectSet')), 'sectionHide' => 'sourceSection', 'value' => $sourcekettle_config['Features']['source_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['source_enabled']['locked'])) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= __('File uploads') ?> <small>- <?= __('allow users to upload files to projects') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => false, 'id' => 'attachment-enabled', 'name' => 'Features,attachment_enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'projectSet')), 'sectionHide' => 'attachmentSection', 'value' => $sourcekettle_config['Features']['attachment_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['attachment_enabled']['locked'])) ?>
                </td>
            </tr>
        </tbody>
    </table>

                </div>

				<? if ($noRepo) {?>
				<div class="well">
					<h3><?=__("Project is repository-less!")?></h3>
					<?=__("Need to add a repository? %s", $this->Html->link(__('Go here!'), array('controller' => 'projects', 'action' => 'add_repo', 'project' => $project['Project']['name'])))?>
				</div>
				<? } ?>

                <div class="well">
                    <h3><?=__("Delete this project")?></h3>
                    <p><?=__("Please note, this action is <strong>not</strong> reversible. This will also delete any material associated with this project.")?></p>
                    <?= $this->Bootstrap->button_link(__("Delete this project"), array("controller" => "projects", "action" => "delete", "project" => $project['Project']['name']), array("style" => "danger")) ?>
                </div>
            </div>
        </div>
    </div>
</div>
