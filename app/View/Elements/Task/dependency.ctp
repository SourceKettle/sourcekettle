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

// TODO this doesn't link to the right place - needs project name
$url = array('api' => false, 'controller' => 'tasks', 'project' => $project, 'action' => 'view', $task['id']);
?>
<div onclick="location.href='<?= $this->Html->url($url) ?>';" draggable="false">
    <?/*<div class="type_bar_small type_bar_<?= $task['TaskType']['name'] ?>"></div>*/?>
    <div class="task">
        <div class="well">
            <div class="row-fluid">
                <div>
                    <div class="span10">
                        <p>
                            <?= $this->Html->link('<strong>#'.$task['id'].'</strong> - '.$this->Text->truncate ($task['subject'], 30), $url, array('escape' => false)) ?>
                        </p>
                        <?= $this->Task->priority($task['task_priority_id']) ?>
                        <?= $this->Task->statusLabel($task['task_status_id']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
