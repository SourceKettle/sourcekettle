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

						<?= $this->Task->storyPointsControl($task) ?>
						<?= $this->Task->priorityDropdownButton($task, false) ?>
						<?= $this->Task->statusDropdownButton($task) ?>
						<?= $this->Task->milestoneLabel($task) ?>
						</span>
						</span>
					</div>

					<div class="span3 pull-right task-lozenge-assignee task-lozenge-main">
					<?= $this->Task->assigneeDropdownButton($task) ?>
					</div>
			</div>
		</div>
	</div>
</div>
</li>
