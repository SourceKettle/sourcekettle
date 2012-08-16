<?php
/**
 *
 * View class for APP/tasks/index for the DevTrack system
 * Shows a list of tasks for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Tasks
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('tasks.index', null, array ('inline' => false));
echo $this->Bootstrap->page_header($project['Project']['name']." <small>Tasks for the Project</small>");?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <?= $this->element('Topbar/task') ?>
            <div class="span10">
                <div class="row-fluid">
                    <div class="span4">
                        <div class="well col">
                            <h2>Backlog</h2>
                            <hr />
                            <?= $this->element('Task/Board/'.((!empty($backlog))?'full_column':'empty_backlog'), array('tasks' => $backlog, 'e' => $backlog_empty)) ?>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="well col">
                            <h2>In Progress</h2>
                            <hr />
                            <?= $this->element('Task/Board/'.((!empty($inProgress))?'full_column':'empty_in_progress'), array('tasks' => $inProgress, 'e' => $inProgress_empty)) ?>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="well col">
                            <h2>Completed</h2>
                            <hr />
                            <?= $this->element('Task/Board/'.((!empty($completed))?'full_column':'empty_completed'), array('tasks' => $completed, 'e' => $completed_empty)) ?>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="well col">
                            <h2>Ice Box</h2>
                            <hr />
                            <? if (!empty($iceBox)) : ?>
                                <? foreach ($iceBox as $task) : ?>
                                    <?= $this->element('Task/element', array('task' => $task)) ?>
                                <? endforeach; ?>
                            <? else: ?>
                                <?= $this->element('Task/Board/empty_icebox') ?>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
