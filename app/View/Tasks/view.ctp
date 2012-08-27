<?php
/**
 *
 * View class for APP/tasks/view for the DevTrack system
 * Allows a user to view a task for a project
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

$this->Html->css('tasks.view', null, array ('inline' => false));

?>

<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
    <div class="span10">
        <div class="row">
            <?= $this->element('Topbar/task') ?>
            <div class="span10">

                <div class="row-fluid">
                    <div class="span12">
                        <div class="btn-toolbar">
                            <div class="btn-group">
                                <?= $this->Bootstrap->button($this->DT->t('bar.task').$task['Task']['id'], array("class" => "disabled")) ?>
                                <?= $this->Bootstrap->button_link($this->DT->t('bar.edit'), array('project' => $project['Project']['name'], 'action' => 'edit', $task['Task']['id']), array("style" => "primary")) ?>
                            </div>
                            <div class="btn-group">
                                <?= $this->Bootstrap->button($this->DT->t('bar.assign')) ?>
                                <?= $this->Bootstrap->button($this->DT->t('bar.selfassign')) ?>
                                <?= $this->Bootstrap->button($this->DT->t('bar.resolve')) ?>
                                <?= $this->Bootstrap->button($this->DT->t('bar.close')) ?>
                                <?= $this->Bootstrap->button($this->DT->t('bar.delete'), array("style" => "danger")) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row-fluid">

                    <div class="span1">
                        <?= $this->Html->link(
                            $this->Gravatar->image($task['Owner']['email'], array('d' => 'mm')),
                            array('controller' => 'users', 'action' => 'view', $task['Owner']['id']),
                            array('escape' => false, 'class' => 'thumbnail')
                        ) ?>
                    </div>
                    <div class="span10">
                        <div class="well col">
                            <span class="pull-right"><?= $this->Task->priority($task['Task']['task_priority_id']) ?></span>
                            <h5>
                                <?= $this->Bootstrap->icon('pencil') ?>
                                <small>
                                    <?= $task['Owner']['name'] ?>
                                    <?= $this->DT->t('history.create.action') ?>
                                    <?= $this->Time->timeAgoInWords($task['Task']['created']) ?>
                                </small>
                            </h5>
                            <span class="pull-right"><?= $this->Task->type($task['Task']['task_type_id']) ?></span>
                            <h3><?= $task['Task']['subject'] ?></h3>
                            <hr />
                            <p><?= $task['Task']['description'] ?></p>
                        </div>
                    </div>
                    <div class="span1"></div>

                </div>

                <?php
                    foreach ($changes as $change) {
                        if ( isset($change['ProjectHistory']) ) {
                            echo $this->element('Task/change_box', array('change' => $change));
                        } else {
                            echo $this->element('Task/comment_box', array('comment' => $change));
                        }
                    }
                ?>

            </div>
        </div>
    </div>
</div>
