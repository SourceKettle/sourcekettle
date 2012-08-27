<?php
/**
 *
 * View class for APP/tasks/edit for the DevTrack system
 * Allows a user to edit a task for a project
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

$this->Html->css('tasks.add', null, array ('inline' => false));

?>

<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <?= $this->element('Topbar/task') ?>
            <div class="span10">
                <?= $this->Form->create('Task', array('class' => 'well form-horizontal')) ?>
                <div class="row-fluid">
                    <div class="span10">
                        <?php
                        echo $this->Bootstrap->input("subject", array(
                            "input" => $this->Form->text("subject", array("class" => "span12", "placeholder" => $this->DT->t('form.subject.placeholder'))),
                            "label" => $this->DT->t('form.subject.label'),
                        ));

                        echo $this->Bootstrap->input("task_priority_id", array(
                            "input" => $this->Form->input("task_priority_id", array(
                                "label" => false,
                                "class" => "span3"
                            )),
                            "label" => $this->DT->t('form.priority.label'),
                        ));

                        echo $this->Bootstrap->input("milestone_id", array(
                            "input" => $this->Form->input("milestone_id", array(
                                "label" => false,
                                "class" => "span6",
                            )),
                            "label" => $this->DT->t('form.milestone.label').' '.$this->Bootstrap->icon('road'),
                        ));

                        echo $this->Bootstrap->input("description", array(
                            "input" => $this->Form->input("description", array(
                                "type" => "textarea",
                                "class" => "span12",
                                "label" => false,
                                "placeholder" => $this->DT->t('form.description.placeholder')
                            )),
                            "label" => $this->DT->t('form.description.label'),
                        ));

                        echo $this->Bootstrap->button($this->DT->t('form.submit'), array("style" => "primary", 'class' => 'controls'));
                        ?>
                    </div>
                    <div class="span2 sidebarRight">
                        <h5><?= $this->DT->t('form.type.label') ?></h5>
                        <?php
                        echo $this->Bootstrap->radio("task_type_id", array(
                            "options" => array(
                                1 => '<div class="tasktype label label-important">bug</div>',
                                2 => '<div class="tasktype label label-warning">duplicate</div>',
                                3 => '<div class="tasktype label label-success">enhancement</div>',
                                4 => '<div class="tasktype label">invalid</div>',
                                5 => '<div class="tasktype label label-info">question</div>',
                                6 => '<div class="tasktype label label-inverse">wontfix</div>',
                            ),
                            "label" => false,
                            "control" => false
                        ));
                        ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
