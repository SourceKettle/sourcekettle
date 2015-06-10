<?php
/**
 *
 * View class for APP/tasks/add for the SourceKettle system
 * Add a new task for a project
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

$this->Html->css('tasks', null, array ('inline' => false));
$this->Html->scriptBlock ("
		jQuery(function() {
			$('#unselect-all').click (function() {
				$('#DependsOnDependsOn option:selected').removeAttr ('selected');
			});
		});
	", array ("inline" => false));

?>
<div class="row-fluid">
	<div class="span10 offset1">

	<?= $this->Form->create('Task', array('class' => 'well form-horizontal')) ?>
	<div class="row-fluid">
	<div class="span10">
		<?php
		echo $this->Bootstrap->input("subject", array(
			"input" => $this->Form->text("subject", array("class" => "span9", "placeholder" => __('Quick, yet informative, description'), "maxlength" => 50, "autofocus" => "")),
			"label" => __('Subject'),
			"help_inline" => __("50 characters max")
		));

		echo $this->Bootstrap->input("milestone_id", array(
			"input" => $this->Form->input("milestone_id", array(
				"label" => false,
				"class" => "span5"
			)),
			"label" => __('Milestone').' '.$this->Bootstrap->icon('road'),
		));

		echo $this->Bootstrap->input("story_id", array(
			"input" => $this->Form->input("story_id", array(
				"label" => false,
				"class" => "span5"
			)),
			"label" => __('User Story').' '.$this->Bootstrap->icon('book'),
		));

		echo $this->Bootstrap->input("task_priority_id", array(
			"input" => $this->Form->input("task_priority_id", array(
				"label"   => false,
				"default" => "2",
				"class"   => "span5"
			)),
			"label" => __('Priority'),
		));
		echo $this->Bootstrap->input("assignee_id", array(
			"input" => $this->Form->input("assignee_id", array(
				"label"   => false,
				"default" => "2",
				"class"   => "span5",
			)),
			"label" => __('Assigned to')
		));

		// Deprecated I guess? We're not actually displaying it anywhere, and story points are a lot more useful
		/*echo $this->Bootstrap->input("time_estimate", array(
			"input" => $this->Form->input("time_estimate", array(
				// Force text field, as we convert  time string to integer
				"type" => "text",
				"label" => false,
				"class" => "span3",
				"placeholder" => "e.g. 2d 4h 3m",
			)),
			"help_inline" => __("Roughly how much time will the task take to finish?"),
			"label" => __('Time Estimate'),
		));*/

		echo $this->Bootstrap->input("story_points", array(
			"input" => $this->Form->input("story_points", array(
				"label" => false,
				"class" => "span3",
			)),
			"help_inline" => __("An abstract estimate of how complex the task is to implement"),
			"label" => __('Story Points'),
		));
		
		echo $this->Bootstrap->input("description", array( 
			"input" => $this->Markitup->editor("description", array(
				"class" => "span8",
				"label" => false,
				"placeholder" => __('Longer and more descriptive explanation...')
			)),
			"label" => false,
		));

		echo '<div class="row-fluid span10 offset2">';
		echo $this->element("linked_list", array(
			"listSpan" => 4,
			"itemSpan" => 12,
			"lists" => array(
				__("Subtasks/blocked by") => array('id' => 'subtasks-list', 'items' => $subTasks, 'tooltip' => __('The new task will depend on anything in this list')),
				__("Unrelated tasks") => array('id' => 'backlog-list', 'items' => $availableTasks, 'tooltip' => __('The new task will not depend on anything in this list')),
				__("Subtask of/blocks") => array('id' => 'parents-list', 'items' => $parentTasks, 'tooltip' => __('Anything in this list will depend on the new task')),
			),
		));
		echo "</div>";
		echo $this->Bootstrap->button(__('Save'), array("style" => "primary", 'class' => 'controls'));
		?>
	</div>
	<div class="span2 sidebarRight">
		<?= $this->Bootstrap->button(__('Save'), array("style" => "primary", 'class' => 'controls span12'));?>
		<h5><?= __('Issue type') ?></h5>
		<?php

		// TODO set default type based on config
		if (!isset($this->request->data['Task']['task_type_id'])) {
			$this->request->data['Task']['task_type_id'] = 1;
		}
		
		$options = array();
		foreach ($task_types as $id => $type) {
			$options[$id] = '<div class="tasktype label label-'.$type['class'].'">'.$type['name'].'</div>';
		}
		echo $this->Bootstrap->radio("task_type_id", array(
			"options" => $options,
			"label" => false,
			"control" => false
		));
		?>
	</div>
</div>
<?= $this->Form->end() ?>
<?= $this->Html->scriptBlock("
	$('form').submit(function(){
		$('#subtasks-list').sortable('toArray').forEach(function(taskId){
			hidden = document.createElement('input');
			hidden.type = 'hidden';
			hidden.name = 'data[DependsOn][]';
			hidden.value = taskId;
			$('form').append(hidden);
		});
		$('#parents-list').sortable('toArray').forEach(function(taskId){
			hidden = document.createElement('input');
			hidden.type = 'hidden';
			hidden.name = 'data[DependedOnBy][]';
			hidden.value = taskId;
			$('form').append(hidden);
		});
	});
", array('inline' => false)); ?>
			</div>
		</div>
