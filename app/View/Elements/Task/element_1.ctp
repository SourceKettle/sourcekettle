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
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements.Task
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (!isset($draggable)){
    $draggable = false;
}
$url = array('api' => false, 'project' => $task['Project']['name'], 'controller' => 'tasks', 'action' => 'view', $task['Task']['id']);
?>
<div onclick="location.href='<?= $this->Html->url($url) ?>';" draggable="<? if ($task['Task']['dependenciesComplete'] && $draggable){ echo 'true'; } else { echo 'false';}?>">
    <div class="task">
        <div class="well type_bar_<?= $task['TaskType']['name'] ?>">
            <div class="row-fluid">
                <div>
                    <div class="span10">
                        <p>
                            <?= $this->Html->link('<strong>#'.$task['Task']['id'].'</strong> - '.$task['Task']['subject'], $url, array('escape' => false)) ?>
                        </p>
                        <?= $this->Task->priority($task['Task']['task_priority_id']) ?>
                        <?= $this->Task->statusLabel($task['Task']['task_status_id']) ?>

                        <?
                        if (!empty($task['DependsOn'])){
                            if (!$task['Task']['dependenciesComplete']){
                                echo "<span class='label label-important'>Dependencies</span>";
                            } else {
                                echo "<span class='label label-success'>Dependencies</span>";
                            }
                        }
                        ?>
                    </div>
                    <div class="span2">
                        <?= $this->Gravatar->image($task['Assignee']['email'], array('d' => 'mm'), array('alt' => $task['Assignee']['name'])) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
