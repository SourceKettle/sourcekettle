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
$url = $this->Html->url(array('controller' => 'tasks', 'action' => 'view', 'project' => $projectName, $task['Task']['public_id']));
if ($draggable) {
	echo "<li class='task-lozenge draggable$span'>";
} else {
	echo "<li class='task-lozenge $span'>";
}
?>

<div id="task_<?= $task['Task']['public_id'] ?>" 
  class="task-container">
	<div class="task">
		<div class="well taskwell type_bar_<?= h($task['Task']['TaskType']['name']) ?>">
			<div class="row-fluid">

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

			</div>
		</div>
	</div>
</div>
</li>
