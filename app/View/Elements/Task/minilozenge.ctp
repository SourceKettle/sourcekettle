<?php
/**
 *
 * Element for APP/tasks/index for the SourceKettle system
 * Shows a task box for a task
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link		  http://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Elements.Task
 * @since		 SourceKettle v 0.1
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->Html->script("tasks", array ('inline' => false));
$this->Html->script("/jquery-ui/jquery.ui.touch-punch.min", array ('inline' => false));
if (!isset($draggable)){
	$draggable = false;
	if (isset($task['__hasWrite'])) {
		$draggable = $task['__hasWrite'];
	}
}
if(isset($span) && $span){
	$span=" span$span";
} else {
	$span="";
}

$localTaskLink = isset($localTaskLink) ?: false;
$milestoneId = isset($milestoneId) ? $milestoneId : 0;

// True if the task is from "another" milestone. Used in story blocks when viewed from the milestone kanban
// chart to indicate which tasks are in both the milestone and the story.
$isOtherMilestone = ($milestoneId > 0 && $milestoneId != $task['Task']['milestone_id']);


if (!isset($checkbox)) {
	$checkbox = false;
}
$apiUrl = $this->Html->url(array('controller' => 'tasks', 'action' => 'edit', 'project' => $task['Project']['name']));

if ($localTaskLink) {
	$url = $this->Html->url(array('controller' => 'tasks', 'action' => 'view', 'project' => $task['Project']['name'], $task['Task']['public_id']));
} else {
	$url = "#task_".$task['Task']['public_id'];
}

if ($draggable) {
	$span="draggable$span";
}
echo "<li class='task-minilozenge $span' data-api-url='$apiUrl' data-taskid='".h($task['Task']['public_id'])."'>";
?>

<div id="task_<?= h($task['Task']['public_id']) ?>" 
  class="task-container">
	<div class="task">
		<div class="well taskwell<?=($isOtherMilestone ? ' other-milestone':'')?>">
			<div class="row-fluid">
				<p class="span12">
				<? if ($checkbox) {
					echo $this->Form->checkbox("Task[]", array("hiddenField" => false, "value" => $task['Task']['public_id']));
				} 
				if ($isOtherMilestone) {
					echo $this->Html->link(
						'<strong>#'.h($task['Task']['public_id']).'</strong> - '.h($task['Task']['subject']),
						$this->Html->url(array('controller' => 'tasks', 'action' => 'view', 'project' => $task['Project']['name'], $task['Task']['public_id'])),
						array(
							'escape' => false,
							'title' => '#'.h($task['Task']['public_id']).' - '.h($task['Task']['subject']),
						)
					);
				} else {
					echo $this->Html->link(
						'<strong>#'.h($task['Task']['public_id']).'</strong> - '.h($task['Task']['subject']),
						$url,
						array(
							'escape' => false,
							'title' => '#'.h($task['Task']['public_id']).' - '.h($task['Task']['subject']),
						)
					);
				} ?>
				</p>

			</div>
		</div>
	</div>
</div>
</li>
