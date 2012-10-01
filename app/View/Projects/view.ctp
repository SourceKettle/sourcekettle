<?php
/**
 *
 * View class for APP/projects/view for the DevTrack system
 * View will render a specific project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Projects
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('project.overview', null, array ('inline' => false));

?>

<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <div class="span10">
                <div class="well">
                    <div class="row-fluid overview">
                        <div class="span4">
                            <h3><?= $this->DT->t('summary.issues.title') ?></h3>
                            <hr />
                            <div class="row-fluid">
                                <div class="span6">
                                    <ul class="unstyled">
                                        <li class="open-tasks"><?= $number_of_open_tasks ?> - <?= $this->DT->t('summary.issues.open') ?></li>
                                        <li class="closed-tasks"><?= $number_of_closed_tasks ?> - <?= $this->DT->t('summary.issues.closed') ?></li>
                                        <li class="total-tasks"><?= $number_of_tasks ?> - <?= $this->DT->t('summary.issues.total') ?></li>
                                        <li><?= $percent_of_tasks ?>% <?= $this->DT->t('summary.issues.percent') ?></li>
                                    </ul>
                                </div>
                                <div class="span6">
                                    <? echo $this->GoogleChart->create()->setType('pie')->setSize(100, 100)->addData(array($number_of_open_tasks, $number_of_closed_tasks)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <h3>Next Milestone</h3>
                            <hr />
                            <ul class="unstyled">
                                <? if ($milestone) : ?>
                                <li><strong><?= $this->Html->link(
                                    $milestone['Milestone']['subject'],
                                    array(
                                        'project'=>$project['Project']['name'],
                                        'controller'=>'milestones',
                                        'action'=>'view',
                                        $milestone['Milestone']['id']
                                    )) ?></strong></li>
                                <br>
                                <li>Due: <?= $milestone['Milestone']['due'] ?></li>
                                <li><?= $milestone['Milestone']['percent'] ?>% complete</li>
                                <?= $this->Bootstrap->progress(array("width" => (int) $milestone['Milestone']['percent'], "striped" => true)) ?>
                                <? else: ?>
                                <li><?= $this->Html->link('Create a milestone', array('project'=>$project['Project']['name'],'controller'=>'milestones','action'=>'add')) ?></li>
                                <? endif; ?>
                            </ul>
                        </div>
                        <div class="span4">
                            <h3>Quick Stats</h3>
                            <hr />
                            <ul class="unstyled">
                                <li><strong><?= $numCollab ?> <?= Inflector::pluralize('user', $numCollab) ?></strong> are working on this project.</li>
                                <li>Last activity was <strong><?= $this->Time->timeAgoInWords($project['Project']['modified']) ?></strong>.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="span10" style="text-align:center">
                <h3>Recent events for the project</h3>
            </div>
            <div class="span10">
                <?= $this->element('history_ajax') ?>
            </div>
        </div>
    </div>
</div>
