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

$priority = array(
    'blocker' => 'important',
    'urgent' => 'warning',
    'major' => 'info',
    'minor' => 'inverse'
);
$type = array(
    'bug' => '',
    'duplicate' => '',
    'enhancement' => '',
    'invalid' => '',
    'question' => '',
    'wontfix' => ''
);
?>
<div draggable="true">
    <div class="task">
        <span class="pull-right">
            <?= $this->Bootstrap->label(ucfirst($task['TaskPriority']['name']), $priority[$task['TaskPriority']['name']]) ?>
        </span>
        <div class="well">
            <div class="row-fluid">
                <div><?= $this->Gravatar->image($task['Assignee']['email'], array('d' => 'mm'), array('alt' => $task['Assignee']['name'], 'class' => 'span1')) ?>
                <div class="span10">
                    <h5>
                        <small><?= $this->Html->link($task['Task']['id'], array('project' => $task['Project']['name'], 'controller' => 'tasks', 'action' => 'view', $task['Task']['id'])) ?></small>
                        -
                        <?= $this->Html->link($task['Task']['subject'], array('project' => $task['Project']['name'], 'controller' => 'tasks', 'action' => 'view', $task['Task']['id'])) ?>
                    </h5>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>