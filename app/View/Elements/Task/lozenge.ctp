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

$includeMilestoneLabel = isset($includeMilestoneLabel) ? $includeMilestoneLabel : true;
$localStoryLink = isset($localStoryLink) ?: false;

$apiUrl = $this->Html->url(array('controller' => 'tasks', 'action' => 'edit', 'project' => $task['Project']['name']));
$url = array('api' => false, 'project' => $task['Project']['name'], 'controller' => 'tasks', 'action' => 'view', $task['Task']['public_id']);

if($draggable){
	echo "<li class='task-lozenge draggable$span' data-taskid='".h($task['Task']['public_id'])."' data-api-url='$apiUrl' data-taskStatus='".$task['TaskStatus']['name']."'>";
} else {
	echo "<li class='task-lozenge $span' data-taskid='".h($task['Task']['public_id'])."' data-api-url='$apiUrl'>";
}
?>

<div id="task_<?= $task['Task']['public_id'] ?>" 
  class="task-container"
  data-taskid="<?= $task['Task']['public_id'] ?>">
	<a name="task_<?= $task['Task']['public_id'] ?>"></a>
	<div class="task">
		<div class="well taskwell type_bar_<?= h($task['TaskType']['name']) ?>">
			<div class="row-fluid">

					<div class="span9 task-lozenge-main">
						<span class="task-subject row-fluid">
						<p class="span12">
							<?= $this->Html->link(
								'<strong>#'.h($task['Task']['public_id']).'</strong> - '.h($task['Task']['subject']),
								$url,
								array(
									'escape' => false,
									'title' => '#'.h($task['Task']['public_id']).' - '.h($task['Task']['subject']),
								)
							) ?>
						</p>
						</span>

						<span class="row-fluid">
						<span class="span12 task-controls">

						<?= $this->Task->statusDropdownButton($draggable? $task: $task['Task']['task_status_id']) ?>
						<?= $this->Task->storyPointsControl($draggable? $task: $task['Task']['story_points']) ?>
						<?= $this->Task->priorityDropdownButton($draggable? $task: $task['Task']['task_priority_id'], false) ?>
						<? if ($includeMilestoneLabel) { echo $this->Task->milestoneLabel($task); } ?>
						<? if ($sourcekettle_config['Features']['story_enabled']['value']) { ?>
							<?= $this->Task->storyLabel($task, $localStoryLink) ?>
						<? } ?>
						</span>
						</span>
					</div>

					<div class="span3 task-lozenge-assignee task-lozenge-main">
					<?= $this->Task->assigneeDropdownButton($task, 90, $draggable) ?>
					</div>
			</div>
		</div>
	</div>
</div>
</li>
