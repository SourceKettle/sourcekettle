<?php
/**
 *
 * View class for APP/tasks/sprint for the DevTrack system
 * Shows a list of tasks for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Tasks
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('tasks.index', null, array ('inline' => false));
?>

<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <?= $this->element('Milestone/topbar_view', array('name' => $milestone['Milestone']['subject'], 'id' => $milestone['Milestone']['id'])) ?>
            <div class="span10">
                <div class="row-fluid">

                    <div class="span4">
                        <div class="well col">
                            <h2><?= $this->DT->t('column.backlog.title') ?></h2>
                            <hr />
                            <?= $this->element('Task/Board/'.((!empty($backlog))?'full_column':'empty'),
                                array('tasks' => $backlog, 'e' => $backlog_empty, 'c' => 'backlog')
                            ) ?>
                        </div>
                    </div>

                    <div class="span4">
                        <div class="well col">
                            <h2><?= $this->DT->t('column.inprogress.title') ?></h2>
                            <hr />
                            <?= $this->element('Task/Board/'.((!empty($inProgress))?'full_column':'empty'),
                                array('tasks' => $inProgress, 'e' => $inProgress_empty, 'c' => 'inprogress')
                            ) ?>
                        </div>
                    </div>

                    <div class="span4">
                        <div class="well col">
                            <h2><?= $this->DT->t('column.completed.title') ?></h2>
                            <hr />
                            <?= $this->element('Task/Board/'.((!empty($completed))?'full_column':'empty'),
                                array('tasks' => $completed, 'e' => $completed_empty, 'c' => 'completed')
                            ) ?>
                        </div>
                    </div>


                </div>

                <div class="row-fluid">
                    <div class="span12">
                        <div class="well col">
                            <h2><?= $this->DT->t('column.icebox.title') ?></h2>
                            <hr />
                            <? if (!empty($iceBox)) : ?>
                                <? for ($x = 0; $x < sizeof($iceBox); $x = $x + 3) : ?>
                                    <div class="row-fluid">
                                        <div class="span4">
                                            <?= $this->element('Task/element_1', array('task' => $iceBox[$x], 'draggable' => true)) ?>
                                        </div>
                                        <div class="span4">
                                            <?= (isset($iceBox[$x + 1])) ? $this->element('Task/element_1', array('task' => $iceBox[$x + 1], 'draggable' => true)) : '' ?>
                                        </div>
                                        <div class="span4">
                                            <?= (isset($iceBox[$x + 2])) ? $this->element('Task/element_1', array('task' => $iceBox[$x + 2], 'draggable' => true)) : '' ?>
                                        </div>
                                    </div>
                                <? endfor; ?>
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
