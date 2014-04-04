<?php
/**
 *
 * Element for APP/tasks/index for the DevTrack system
 * Shows a task box for a task
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Task
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (!isset($draggable)){
    $draggable = false;
}
$url = array('api' => false, 'project' => $task['Project']['name'], 'controller' => 'tasks', 'action' => 'view', $task['Task']['id']);
?>
<div id="task_<?= $task['Task']['id'] ?>" 
  class="task-container"
  <?
  // If it's a draggable item in the milestone board, do NOT make the whole thing a click target...
  if(!$draggable){?>
  onclick="location.href='<?= $this->Html->url($url) ?>';"
  <?}?>
  data-taskid="<?= $task['Task']['id'] ?>">
    <div class="task">
        <div class="well type_bar_<?= h($task['TaskType']['name']) ?>">
            <div class="row-fluid">
                <div>
                    <div class="span10">
                        <p>
                            <?= $this->Html->link('<strong>#'.$task['Task']['id'].'</strong> - '.h($task['Task']['subject']), $url, array('escape' => false)) ?>
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
