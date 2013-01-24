<?php
/**
 *
 * Section element for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Task.View
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<h3><?= $this->DT->t('details.title') ?></h3>
<div>
    <div class="span12">
        <dl class="dl-horizontal span6">
            <dt><?= $this->DT->t('details.creator') ?>:</dt>
            <dd>
                <?= $this->Html->link(
                    $task['Owner']['name'],
                    array('controller' => 'users', 'action' => 'view', $task['Owner']['id'])
                ) ?>
            </dd>
            <dt><?= $this->DT->t('details.type') ?>:</dt>
            <dd><?= $this->Task->type($task['Task']['task_type_id']) ?></dd>
            <dt><?= $this->DT->t('details.priority') ?>:</dt>
            <dd><?= $this->Task->priority($task['Task']['task_priority_id']) ?></dd>
            <dt><?= $this->DT->t('details.milestone') ?>:</dt>
            <dd>
            <?= (isset($task['Milestone']['subject'])) ? $this->Html->link(
                    $task['Milestone']['subject'],
                    array('controller' => 'milestones', 'action' => 'view', 'project' => $task['Project']['name'], $task['Milestone']['id'])
                )  : 'Not set' ?>
            </dd>

            <dt>Depends on:</dt>

            <dd>
            <?php
            foreach($task['DependsOn'] as $dep){
                echo $this->Html->link(
                    '<strong>#'.$dep['id'].'</strong> - '.$this->Text->truncate ($dep['subject'], 30),
                    array(
                        'api' => false,
                        'controller' => 'tasks',
                        'project' => $project['Project']['name'],
                        'action' => 'view',
                        $dep['id']
                    ),
                    array('escape' => false)
                );
                echo "<br>";

            }
            if (!empty($task['DependsOn'])){
                if (!$task['Task']['dependenciesComplete']){
                    echo "<span class='badge badge-important'>Dependencies not completed</span>";
                } else {
                    echo "<span class='badge badge-success'>Dependencies completed</span>";
                }
            }
            ?>
            </dd>
        </dl>
        <dl class="dl-horizontal span6">
            <dt><?= $this->DT->t('details.assignee') ?>:</dt>
            <dd>
                <?= (isset($task['Assignee']['name'])) ? $this->Html->link(
                    $task['Assignee']['name'],
                    array('controller' => 'users', 'action' => 'view', $task['Assignee']['id'])
                )  : 'Not set' ?>
            </dd>
            <dt><?= $this->DT->t('details.status') ?>:</dt>
            <dd><?= $this->Task->status($task['Task']['task_status_id']) ?></dd>

            <dt><?= $this->DT->t('details.created') ?>:</dt>
            <dd><?= $this->Time->timeAgoInWords($task['Task']['created']) ?></dd>
            <dt><?= $this->DT->t('details.updated') ?>:</dt>
            <dd><?= $this->Time->timeAgoInWords($task['Task']['modified']) ?></dd>
            <dt>Depended on by:</dt>
            <dd>
            <?php
            foreach($task['DependedOnBy'] as $dep){
                echo $this->Html->link(
                    '<strong>#'.$dep['id'].'</strong> - '.$this->Text->truncate ($dep['subject'], 30),
                    array(
                        'api' => false,
                        'controller' => 'tasks',
                        'project' => $project['Project']['name'],
                        'action' => 'view',
                        $dep['id']
                    ),
                    array('escape' => false)
                );
                echo "<br>";
            } ?>
            </dd>
        </dl>
    </div>
</div>