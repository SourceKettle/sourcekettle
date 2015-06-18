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
	<div class="span8 offset2">
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
</div>

<div class="row-fluid">
	<div class="span8 offset2">

		<div class="well">
			<h3><?=__("Features")?></h3>
			<p><?=__("Please note that some features may be locked system-wide, meaning you will not be able to enable/disable them.")?></p>

			<dl>
				<dt>
					<h4><?= __('Task management') ?> <small>- <?= __('allow users to add tasks and milestones to track progress') ?></small></h4>
				</dt>
				<dd>
					<?= $this->element('Setting/switch', array('lock' => false, 'id' => 'task-enabled', 'model' => 'ProjectSetting', 'name' => 'Features.task_enabled', 'url' => $this->Html->url(array('controller' => 'projects', 'action' => 'changeSetting', 'project' => $project['Project']['name'])), 'sectionHide' => 'taskSection', 'value' => $sourcekettle_config['Features']['task_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['task_enabled']['locked'])) ?>
				</dd>
			</dl>
			<span id='taskSection' <? if (!$sourcekettle_config['Features']['task_enabled']['value']) {echo 'style="display:none"';}?>>
			<dl>
				<dt>
					<h4><?= __('4-column Kanban chart') ?> <small>- <?= __('Do you want to use a 4-column kanban chart (resolved and closed states seperate), or the simplified 3-column chart (open/in progress/completed)?') ?></small></h4>
				</dt>
				<dd>
					<?= $this->element('Setting/switch', array('lock' => false, 'id' => '4col-kanban-enabled', 'model' => 'ProjectSetting', 'name' => 'Features.4col_kanban_enabled', 'url' => $this->Html->url(array('controller' => 'projects', 'action' => 'changeSetting', 'project' => $project['Project']['name'])), 'value' => $sourcekettle_config['Features']['4col_kanban_enabled']['value'], 'readOnly' => false)) ?>
				</dd>
			</dl>

			<dl>
				<dt>
					<h4><?= __('User stories') ?> <small>- <?= __('Allow creation of user stories (NB will be unavailable if task tracking is disabled!)') ?></small></h4>
				</dt>
				<dd>
					<?= $this->element('Setting/switch', array('lock' => false, 'id' => 'story-enabled', 'model' => 'ProjectSetting', 'name' => 'Features.story_enabled', 'url' => $this->Html->url(array('controller' => 'projects', 'action' => 'changeSetting', 'project' => $project['Project']['name'])), 'sectionHide' => 'storySection', 'value' => $sourcekettle_config['Features']['story_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['story_enabled']['locked'])) ?>
				</dd>
			</dl>
			<span id="storySection" <? if (!$sourcekettle_config['Features']['story_enabled']['value']) {echo 'style="display:none"';}?>>
			<?/*<dl>
				<dt>
					<h4><?= __('Epics') ?> <small>- <?= __('Allow creation of epics for grouping stories - enables story map view') ?></small></h4>
				</dt>
				<dd>
					<?= $this->element('Setting/switch', array('lock' => false, 'id' => 'epic-enabled', 'model' => 'ProjectSetting', 'name' => 'Features.epic_enabled', 'url' => $this->Html->url(array('controller' => 'projects', 'action' => 'changeSetting', 'project' => $project['Project']['name'])), 'sectionHide' => 'epicSection', 'value' => $sourcekettle_config['Features']['epic_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['epic_enabled']['locked'])) ?>
				</dd>
			</dl>*/?>
			</span>

			</span>

			<dl>
				<dt>
					<h4><?= __('Time tracking') ?> <small>- <?= __('allow logging of time to projects and tasks') ?></small></h4>
				</dt>
				<dd>
					<?= $this->element('Setting/switch', array('lock' => false, 'id' => 'time-enabled', 'model' => 'ProjectSetting', 'name' => 'Features.time_enabled', 'url' => $this->Html->url(array('controller' => 'projects', 'action' => 'changeSetting', 'project' => $project['Project']['name'])), 'sectionHide' => 'timeSection', 'value' => $sourcekettle_config['Features']['time_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['time_enabled']['locked'])) ?>
				</dd>
			</dl>

			<dl>
				<dt>
					<h4><?= __('File uploads') ?> <small>- <?= __('allow users to upload files to projects') ?></small></h4>
				</dt>
				<dd>
					<?= $this->element('Setting/switch', array('lock' => false, 'id' => 'attachment-enabled', 'model' => 'ProjectSetting', 'name' => 'Features.attachment_enabled', 'url' => $this->Html->url(array('controller' => 'projects', 'action' => 'changeSetting', 'project' => $project['Project']['name'])), 'sectionHide' => 'attachmentSection', 'value' => $sourcekettle_config['Features']['attachment_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['attachment_enabled']['locked'])) ?>
				</dd>
			</dl>

		</div>
	</div>

</div>

<div class="row-fluid">
	<div class="span8 offset2">

		<div class="well">
			<h3><?=__("Defaults")?></h3>
			<p><?=__("Please note that some defaults may be locked system-wide, meaning you will not be able to change them.")?></p>
			<?= $this->element('Setting/dropdown_fields', array(
				'model' => 'ProjectSetting',
				'url' => array('controller' => 'projects', 'action' => 'changeSetting', 'project' => $project['Project']['name']),
				'items' => array(
					array(
						'name' => 'Defaults.task_type',
						'label' => __('Task type'),
						'description' => __('When adding a new task, which task type is pre-selected'),
						'value' => $sourcekettle_config['Defaults']['task_type']['value'],
						'options' => $task_types,
						'locked' => $sourcekettle_config['Defaults']['task_type']['locked'],
						'readOnly' => $sourcekettle_config['Defaults']['task_type']['locked'],
					),
					array(
						'name' => 'Defaults.task_priority',
						'label' => __('Task priority'),
						'description' => __('When adding a new task, which priority is pre-selected'),
						'value' => $sourcekettle_config['Defaults']['task_priority']['value'],
						'options' => $task_priorities,
						'locked' => $sourcekettle_config['Defaults']['task_priority']['locked'],
						'readOnly' => $sourcekettle_config['Defaults']['task_priority']['locked'],
					),
					array(
						'name' => 'Defaults.task_status',
						'label' => __('Task status'),
						'description' => __('When adding a new task, which task status is pre-selected'),
						'value' => $sourcekettle_config['Defaults']['task_status']['value'],
						'options' => $task_statuses,
						'locked' => $sourcekettle_config['Defaults']['task_status']['locked'],
						'readOnly' => $sourcekettle_config['Defaults']['task_status']['locked'],
					),
					array(
						'name' => 'Defaults.task_assignee_id',
						'label' => __('Task assignee'),
						'description' => __('When adding a new task, which assignee is pre-selected'),
						'value' => $sourcekettle_config['Defaults']['task_assignee_id']['value'],
						'options' => $collaborators,
						'locked' => $sourcekettle_config['Defaults']['task_assignee_id']['locked'],
						'readOnly' => $sourcekettle_config['Defaults']['task_assignee_id']['locked'],
					),
				),
				'addLock' => false,
			)) ?>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span8 offset2">
	<? if ($noRepo) {?>
		<div class="well">
			<h3><?=__("Project is repository-less!")?></h3>
			<?=__("Need to add a repository? %s", $this->Html->link(__('Go here!'), array('controller' => 'projects', 'action' => 'add_repo', 'project' => $project['Project']['name'])))?>
		</div>
	<? } else { ?>
		<div class="well">
			<dl>
				<dt>
					<h3><?= __('Repository access') ?> <small>- <?= __('Enable/disable access to source code repository - you should probably leave this switched on!') ?></small></h3>
				</dt>
				<dd>
					<?= $this->element('Setting/switch', array('lock' => false, 'id' => 'attachment-enabled', 'model' => 'ProjectSetting', 'name' => 'Features.source_enabled', 'url' => $this->Html->url(array('controller' => 'projects', 'action' => 'changeSetting', 'project' => $project['Project']['name'])), 'sectionHide' => 'attachmentSection', 'value' => $sourcekettle_config['Features']['source_enabled']['value'], 'readOnly' => $sourcekettle_config['Features']['source_enabled']['locked'])) ?>
				</dd>
			</dl>
		</div>
	<? } ?>
	</div>
</div>
<div class="row-fluid">
	<div class="span8 offset2">

		<div class="well">
			<h3><?=__("Delete this project")?></h3>
			<p><?=__("Please note, this action is <strong>not</strong> reversible. This will also delete any material associated with this project.")?></p>
			<?= $this->Bootstrap->button_link(__("Delete this project"), array("controller" => "projects", "action" => "delete", "project" => $project['Project']['name']), array("style" => "danger")) ?>
		</div>
	</div>
</div>
