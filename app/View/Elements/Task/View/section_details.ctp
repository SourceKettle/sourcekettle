<?php
/**
 *
 * Section element for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Task.View
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<h3><?= __("Task details") ?></h3>
<div>
    <div class="span12">
        <dl class="dl-horizontal span6">
            <dt><?= __("Created by:") ?></dt>
            <? if (isset($task['Owner']['id'])) {?>
	    <dd>
                <?= $this->Html->link(
                    $task['Owner']['name'],
                    array('controller' => 'users', 'action' => 'view', $task['Owner']['id'])
                ) ?>
            </dd>
	    <? } else { ?>
            <dd class="muted">
		<?=__("Not set")?>
            </dd>
	    <? } ?>
            <dt><?= __("Task type") ?>:</dt>
            <dd><?= $this->Task->type($task['Task']['task_type_id']) ?></dd>
            <dt><?= __("Task priority") ?>:</dt>
            <dd><?= $this->Task->priority($task['Task']['task_priority_id']) ?></dd>
            <dt><?= __("Fix milestone") ?>:</dt>
            <? if (isset($task['Milestone']['subject'])) { ?>
                <dd>
                    <?= $this->Html->link(
                        $task['Milestone']['subject'],
                        array('controller' => 'milestones', 'action' => 'view', 'project' => $task['Project']['name'], $task['Milestone']['id'])
                    ) ?>
                </dd>
            <? } else { ?>
                <dd class="muted"><?= __("Not set")?></dd>
            <? } ?>
            <dt><?=__('Estimate')?>:</dt>
            <? if (isset($task['Task']['time_estimate'])) { ?>
                <dd><?=$task['Task']['time_estimate']?></dd>
            <? } else { ?>
                <dd class="muted"><?= __("No time estimate")?></dd>
            <? } ?>

            <? if (isset($task['Task']['story_points'])) { ?>
                <dd><?=$task['Task']['story_points']?> <?= __("points")?></dd>
            <? } else { ?>
                <dd class="muted"><?= __("No story point estimate")?></dd>
            <? } ?>

            <dt><?= __("Depends on")?>:</dt>

            <dd>
            <?php
            foreach($task['DependsOn'] as $dep){
                echo $this->Html->link(
                    '<strong>#'.$dep['public_id'].'</strong> - '.$this->Text->truncate (h($dep['subject']), 30),
                    array(
                        'api' => false,
                        'controller' => 'tasks',
                        'project' => $project['Project']['name'],
                        'action' => 'view',
                        $dep['public_id']
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
            } else {
                echo '<span class="muted">n/a</span>';
            }
            ?>
            </dd>
        </dl>
        <dl class="dl-horizontal span6">
            <dt><?= __("Assigned to") ?>:</dt>
            <? if (isset($task['Assignee']['name'])) { ?>
                <dd>
                    <?= $this->Html->link(
                        $task['Assignee']['name'],
                        array('controller' => 'users', 'action' => 'view', $task['Assignee']['id'])
                    ) ?>
                </dd>
            <? } else { ?>
                <dd class="muted">Not set</dd>
            <? } ?>
            <dt><?= __("Task status") ?>:</dt>
            <dd><?= $this->Task->status($task['Task']['task_status_id']) ?></dd>

            <dt><?= __("Created") ?>:</dt>
            <dd><?= $this->Time->timeAgoInWords($task['Task']['created']) ?></dd>
            <dt><?= __("Last updated") ?>:</dt>
            <dd><?= $this->Time->timeAgoInWords($task['Task']['modified']) ?></dd>
            <dt><?= __("Depended on by")?>:</dt>
            <dd>
            <?php
            foreach($task['DependedOnBy'] as $dep){
                echo $this->Html->link(
                    '<strong>#'.$dep['id'].'</strong> - '.$this->Text->truncate (h($dep['subject']), 30),
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
            if (empty($task['DependedOnBy'])) {
                echo '<span class="muted">n/a</span>';
            } ?>
            </dd>
        </dl>
    </div>
</div>
