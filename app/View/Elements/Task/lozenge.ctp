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
$this->Html->script(array('/bootstrap-tooltip/bootstrap-tooltip'), array('inline' => false));
$this->Html->scriptBlock("$('.task-lozenge p.task-subject a').tooltip()", array('inline' => false));
$this->Html->script("tasks", array ('inline' => false));
if (!isset($draggable)){
	$draggable = false;
}
if(isset($span) && $span){
	$span=" span$span";
} else {
	$span="";
}
$apiUrl = $this->Html->url(array(
  	'controller' => 'tasks',
	'action' => 'update',
	'project' => $task['Project']['name'],
	'api' => true,
));
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
	<div class="task">
		<div class="well taskwell type_bar_<?= h($task['TaskType']['name']) ?>">
			<div class="row-fluid">
				<div>
					<div class="span10">
						<p class="task-subject">
							<?= $this->Html->link(
								'<strong>#'.h($task['Task']['public_id']).'</strong> - '.h($task['Task']['subject']),
								$url,
								array(
									'escape' => false,
									'title' => '#'.h($task['Task']['public_id']).' - '.h($task['Task']['subject']),
								)
							) ?>
							<?= $this->Html->link($this->Bootstrap->icon("pencil"), array(
								'controller' => 'tasks',
								'action' => 'edit',
								'project' => $task['Project']['name'],
								$task['Task']['public_id'],
							), array('escape' => false, 'title' => __("Edit task"))) ?>
						</p>

						<?= $this->Task->storyPointsLabel($task) ?>
						<?= $this->Task->priorityDropdownButton($task['Task']['public_id'], $task['Task']['task_priority_id'], false) ?>
						<?= $this->Task->statusDropdownButton($task['Task']['public_id'], $task['Task']['task_status_id']) ?>
						<?= $this->Task->milestoneLabel($task) ?>
						
					</div>
					<?= $this->Task->assigneeLabel($task) ?>
				</div>
			</div>
		</div>
	</div>
</div>
</li>
