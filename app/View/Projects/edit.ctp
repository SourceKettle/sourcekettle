<?php
/**
 *
 * View class for APP/projects/edit for the SourceKettle system
 * Edit allows editing of a specific project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link		  https://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Projects
 * @since		 SourceKettle v 0.1
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

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
	<dl>
		<dt>
			<h4><?= __('Time tracking') ?> <small>- <?= __('allow logging of time to projects and tasks') ?></small></h4>
		</dt>
		<dd>
			<?= $this->element('Setting/switch', array('lock' => false, 'id' => 'time-enabled', 'name' => 'ProjectSetting.Features.time_enabled', 'url' => $this->Html->url(array('controller' => 'projects', 'action' => 'changeSetting', 'project' => $project['Project']['name'])), 'sectionHide' => 'timeSection', 'value' => $sourcekettle_config['Features']['time_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['time_enabled']['locked'])) ?>
		</dd>
	</dl>
	<dl>
		<dt>
			<h4><?= __('Task management') ?> <small>- <?= __('allow users to add tasks and milestones to track progress') ?></small></h4>
		</dt>
		<dd>
			<?= $this->element('Setting/switch', array('lock' => false, 'id' => 'task-enabled', 'name' => 'ProjectSetting.Features.task_enabled', 'url' => $this->Html->url(array('controller' => 'projects', 'action' => 'changeSetting', 'project' => $project['Project']['name'])), 'sectionHide' => 'taskSection', 'value' => $sourcekettle_config['Features']['task_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['task_enabled']['locked'])) ?>
		</dd>
	</dl>
	<dl>
		<dt>
			<h4><?= __('User stories') ?> <small>- <?= __('Allow creation of user stories (NB will be unavailable if task tracking is disabled!)') ?></small></h4>
		</dt>
		<dd>
			<?= $this->element('Setting/switch', array('lock' => false, 'id' => 'story-enabled', 'name' => 'ProjectSetting.Features.story_enabled', 'url' => $this->Html->url(array('controller' => 'projects', 'action' => 'changeSetting', 'project' => $project['Project']['name'])), 'sectionHide' => 'storySection', 'value' => $sourcekettle_config['Features']['story_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['story_enabled']['locked'])) ?>
		</dd>
	</dl>
	<dl>
		<dt>
			<h4><?= __('Epics') ?> <small>- <?= __('Allow creation of epics for grouping stories - enables story map view') ?></small></h4>
		</dt>
		<dd>
			<?= $this->element('Setting/switch', array('lock' => false, 'id' => 'epic-enabled', 'name' => 'ProjectSetting.Features.epic_enabled', 'url' => $this->Html->url(array('controller' => 'projects', 'action' => 'changeSetting', 'project' => $project['Project']['name'])), 'sectionHide' => 'epicSection', 'value' => $sourcekettle_config['Features']['epic_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['epic_enabled']['locked'])) ?>
		</dd>
	</dl>
	<dl>
		<dt>
			<h4><?= __('4-column Kanban chart') ?> <small>- <?= __('Do you want to use a 4-column kanban chart (resolved and closed states seperate), or the simplified 3-column chart (open/in progress/completed)?') ?></small></h4>
		</dt>
		<dd>
			<?= $this->element('Setting/switch', array('lock' => false, 'id' => '4col-kanban-enabled', 'name' => 'ProjectSetting.Features.4col_kanban_enabled', 'url' => $this->Html->url(array('controller' => 'projects', 'action' => 'changeSetting', 'project' => $project['Project']['name'])), 'value' => $sourcekettle_config['Features']['4col_kanban_enabled']['value'], 'readOnly' => false)) ?>
		</dd>
	</dl>
	<dl>
		<dt>
			<h4><?= __('Source code management') ?> <small>- <?= __('allow creation of a source code repository for a project') ?></small></h4>
		</dt>
		<dd>
			<?= $this->element('Setting/switch', array('lock' => false, 'id' => 'source-enabled', 'name' => 'ProjectSetting.Features.source_enabled', 'url' => $this->Html->url(array('controller' => 'projects', 'action' => 'changeSetting', 'project' => $project['Project']['name'])), 'sectionHide' => 'sourceSection', 'value' => $sourcekettle_config['Features']['source_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['source_enabled']['locked'])) ?>
		</dd>
	</dl>
	<dl>
		<dt>
			<h4><?= __('File uploads') ?> <small>- <?= __('allow users to upload files to projects') ?></small></h4>
		</dt>
		<dd>
			<?= $this->element('Setting/switch', array('lock' => false, 'id' => 'attachment-enabled', 'name' => 'ProjectSetting.Features.attachment_enabled', 'url' => $this->Html->url(array('controller' => 'projects', 'action' => 'changeSetting', 'project' => $project['Project']['name'])), 'sectionHide' => 'attachmentSection', 'value' => $sourcekettle_config['Features']['attachment_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['attachment_enabled']['locked'])) ?>
		</dd>
	</dl>

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
