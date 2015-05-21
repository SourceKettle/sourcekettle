<?php
/**
 *
 * View class for APP/tasks/view for the SourceKettle system
 * Allows a user to view a task for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link		  http://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Tasks
 * @since		 SourceKettle v 0.1
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('tasks', array ('inline' => false));
$this->Html->script("tasks", array ('inline' => false));
$apiUrl = $this->Html->url(array('controller' => 'tasks', 'action' => 'edit', 'project' => $task['Project']['name']));
?>

<?= $this->Task->allDropdownMenus() ?>

<?= $this->Form->create('Task', array ('url' => array('controller' => 'tasks', 'action' => 'edit', 'project' => $project['Project']['name'], $task['Task']['public_id']))); ?>
<div class="row-fluid">
	<div class="span12 well task-lozenge task-card" data-taskid="<?=h($task['Task']['public_id'])?>" data-api-url="<?=$apiUrl?>">

		<div class="row-fluid task-view-top">
	<div class="span3 task-view-priority">
			<h5><?= __("Priority") ?></h5>
	<?= $this->Task->priorityDropdownButton($task, true) ?>
	</div>

	<div class="span6 task-view-subject">
	<h3>#<?=$task['Task']['public_id']?>:

		<span class="task-subject-text"><?= $task['Task']['subject'] ?></span>
				<button type="button" class="close edit"><?= $this->Bootstrap->icon('pencil'); ?></button>

		<span class="edit-form input-append hide">
			<?= $this->Form->textarea("subject", array("rows" => 1)); ?>
		<?= $this->Bootstrap->button(__("Update"), array("style" => "primary")); ?>
		</span>

		<p class="task-milestone"><small class="milestone-label">
		<?=isset($task['Milestone']['subject'])? __("Milestone: %s", $this->Html->link(
			$task['Milestone']['subject'], array(
				'controller' => 'milestones',
				'project' => $task['Project']['name'],
				'action' => 'view',
				$task['Milestone']['id']
		))): __("No milestone")?>
		</small>
			<?= $this->Task->milestoneDropdownButton($task, 23, true, true)?>
		</p>

	</h3>

	</div>
	<div class="span3 task-view-assignee">
			<h5><?= __("Assigned to") ?></h5>
		<?=$this->Task->assigneeDropdownButton($task, 23, true, true, true)?>
	</div>
	</div>

	<div class="row-fluid task-view-middle">
	<div class="span6 offset3 task-view-description">
	<small>
	<?= $this->Html->link(
		$this->Gravatar->image($task['Owner']['email'], array('d' => 'mm', 'size' => 16)),
		array('controller' => 'users', 'action' => 'view', $task['Owner']['id']),
		array('escape' => false,) 
	) ?>
	<?= $this->Html->link(
		$task['Owner']['name'],
		array('controller' => 'users', 'action' => 'view', $task['Owner']['id'])
	) ?>
	<?= __("created this task") ?>
	<?= $this->Time->timeAgoInWords($task['Task']['created']) ?>
	</small>
	<h4><?=__("Description")?></h4>
		<button type="button" class="close edit"><?= $this->Bootstrap->icon('pencil'); ?></button>
		<div class="well task-description-text"><?= $this->Markitup->parse($task['Task']['description']) ?></div>
	
	<span class="edit-form hide">
			<?= $this->Form->textarea("description", array("class" => "task-description-input", "rows" => 10)); ?>
		<?= $this->Bootstrap->button(__("Update"), array("style" => "primary")); ?>
		</span>
	</div>
	</div>
	<div class="row-fluid task-view-bottom">
	<div class="span3 task-view-points">
	<h5><?=__("Estimate")?></h5>
			<?=$this->Task->storyPointsControl($task, true)?></dd>
	</div>
	<div class="span6 task-view-selectors">
	<h5><?=__("Attributes")?></h5>
			<?= $this->Task->typeDropdownButton($task) ?>
	<?= $this->Task->statusDropdownButton($task, true) ?>
	</div>
	<div class="span3 task-view-time-logged">
	<h5><?=__("Time logged")?></h5>
	<?=$this->Html->link(TimeString::renderTime($totalTime, 's'), array(
		'controller' => 'times',
		'action' => 'tasklog',
		'project' => $task['Project']['name'],
		$task['Task']['public_id']
	))?>
	<span class="btn btn-small">
	<?= $this->Html->link(
		'<i class="icon-plus" title="'.__("Log time").'"></i> '.__("Log time"), array(
		"controller" => "times",
		"action" => "add",
		"project" => $project['Project']['name'],
		"?" => array("task_id" => $task['Task']['public_id']),
	), array("escape" => false))?>
	</span>
	</div>
	</div>
</div>

<?= $this->Form->end(); ?>

<div class="row-fluid text-center">
	<h3><?=__("Dependencies")?></h3>
	<?=$this->Html->link(__("View dependency tree"), array('controller' => 'tasks', 'action' => 'tree', 'project' => $project['Project']['name'], $task['Task']['public_id']))?>
	</div>
	
		<div class="row-fluid" data-api-url="<?=$apiUrl?>">
		<?= $this->element("linked_list", array(
						"listSpan" => 4,
						"itemSpan" => 12,
						"lists" => array(
								__("Subtasks/blocked by") => array('id' => 'subtasks-list', 'items' => $subTasks, 'tooltip' => __('The new task will depend on anything in this list')),
								__("Unrelated tasks") => array('id' => 'backlog-list', 'items' => $availableTasks, 'tooltip' => __('The new task will not depend on anything in this list'), 'hidden' => false),
								__("Subtask of/blocks") => array('id' => 'parents-list', 'items' => $parentTasks, 'tooltip' => __('Anything in this list will depend on the new task')),
						),
		)) ?>
	<?= $this->Html->scriptBlock("ajaxDependencyLists(".h($task['Task']['project_id']).", ".h($task['Task']['public_id']).", $('#subtasks-list'), $('#backlog-list'), $('#parents-list'));", array("inline" => false)) ?>
</div>


<div class="row-fluid">
	<div class="span12 task-history">
		<h3><?=__("Task history")?></h3>
				<?php
					foreach ($changes as $change) {
						if ( isset($change['ProjectHistory']) ) {
							echo $this->element('Task/change_box', array('change' => $change));
						} else {
							echo $this->element('Task/comment_box', array('comment' => $change));
						}
					}
				?>

				 <div class="row-fluid">

					<div class="span8 offset2">
						<div class="well col">
							<?php
							echo $this->Form->create('TaskComment', array('class' => 'form', 'url' => array('controller' => 'tasks', 'action' => 'comment', 'project' => $project['Project']['name'], $task['Task']['public_id'])));

				echo $this->Bootstrap->input("comment", array(
					"input" => $this->Markitup->editor("comment", array(
						"class" => "span11",
						"label" => false,
						"placeholder" => __("Add a new comment to this task...")
					)),
					"label" => false,
				));

							echo $this->Bootstrap->button(__("Comment"), array("style" => "primary", 'class' => 'controls'));
							echo $this->Form->end();
							?>
						</div>
					</div>

				</div>
	
			</div>
		</div>
