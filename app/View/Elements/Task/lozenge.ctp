<?php
/**
 *
 * Element for APP/tasks/index for the SourceKettle system
 * Shows a task box for a task
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Task
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (!isset($draggable)){
    $draggable = false;
}

if(isset($span) && $span){
	$span=" span$span";
} else {
	$span="";
}
$url = array('api' => false, 'project' => $task['Project']['name'], 'controller' => 'tasks', 'action' => 'view', $task['Task']['public_id']);
	if($draggable){
		echo "<li class='draggable' data-taskid='".h($task['Task']['public_id'])."'>";
	} else {
		echo "<li>";
	}
?>
<div id="task_<?= $task['Task']['public_id'] ?>" 
  class="task-container<?=$span?>"
  <?
  // If it's a draggable item in the milestone board, do NOT make the whole thing a click target...
  if(!$draggable){?>
  onclick="location.href='<?= $this->Html->url($url) ?>';"
  <?}?>
  data-taskid="<?= $task['Task']['public_id'] ?>">
    <div class="task">
        <div class="well type_bar_<?= h($task['TaskType']['name']) ?>">
            <div class="row-fluid">
                <div>
                    <div class="span10">
                        <p>
                            <?= $this->Html->link('<strong>#'.$task['Task']['public_id'].'</strong> - '.h($task['Task']['subject']), $url, array('escape' => false)) ?>
                        </p>
                        <?= $this->Task->priority($task['Task']['task_priority_id']) ?>
                        <?= $this->Task->statusLabel($task['Task']['task_status_id']) ?>

                        <?
                        if (!empty($task['DependsOn'])){
                            if (!$task['Task']['dependenciesComplete']){
                                echo "<span class='label label-important' title='Dependencies incomplete'>D</span>";
                            } else {
                                echo "<span class='label label-success' title='Dependencies complete'>D</span>";
                            }
                        }

						// Display story points or time estimate if we have one
						if (!empty($task['Task']['story_points'])) {
							echo "<span class='label' title='Story points'>";
							printf(ngettext("%d point", "%d points", $task['Task']['story_points']), $task['Task']['story_points']);
							echo "</span>";
						
						} elseif (!empty($task['Task']['time_estimate']) && TimeString::parseTime($task['Task']['time_estimate']) > 0) {
							echo "<span class='label' title='Time estimate'>";
							echo "Est. ".$task['Task']['time_estimate'];
							echo "</span>";
						}
                        ?>
                    </div>
                    <div class="span2">
					  <?if(isset($task['Assignee']['id'])){?>
                        <?= $this->Gravatar->image($task['Assignee']['email'], array(), array('alt' => $task['Assignee']['name'])) ?>
					  <?} else {?>
                        <?= $this->Gravatar->image('', array('d' => 'mm'), array('alt' => $task['Assignee']['name'])) ?>
					  <?}?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</li>
