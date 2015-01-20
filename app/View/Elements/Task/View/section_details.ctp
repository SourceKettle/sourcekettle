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
 
$apiUrl = $this->Html->url(array('controller' => 'tasks', 'action' => 'update', 'api' => true, 'project' => $task['Project']['name']));
?>
<h3><?= __("Task details") ?></h3>
<div>
    <div class="span12 task-lozenge" data-taskid="<?=h($task['Task']['public_id'])?>" data-api-url="<?=$apiUrl?>">
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
            <dd><?= $this->Task->typeDropdownButton($task) ?></dd>
            <dt><?= __("Task priority") ?>:</dt>
			<dd><?= $this->Task->priorityDropdownButton($task, true) ?></dd>

            <dt><?= __("Fix milestone") ?>:</dt>
	    <dd><?=$this->Task->milestoneDropdownButton($task, 23, true, true)?></dd>

            <dt><?=__('Estimate')?>:</dt>
            <? if (isset($task['Task']['time_estimate'])) { ?>
                <dd><?=$task['Task']['time_estimate']?></dd>
            <? } else { ?>
                <dd class="muted"><?= __("No time estimate")?></dd>
            <? } ?>

            <? if (isset($task['Task']['story_points'])) { ?>
                <dd><?=$this->Task->storyPointsControl($task, true)?></dd>
            <? } else { ?>
                <dd class="muted"><?= __("No story point estimate")?></dd>
            <? } ?>

            <dt><?= __("Subtasks")?>:</dt>

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
	    <dd><?=$this->Task->assigneeDropdownButton($task, 23, true, true)?></dd>
            <dt><?= __("Task status") ?>:</dt>
			<dd><?= $this->Task->statusDropdownButton($task, true) ?></dd>

            <dt><?= __("Created") ?>:</dt>
            <dd><?= $this->Time->timeAgoInWords($task['Task']['created']) ?></dd>
            <dt><?= __("Last updated") ?>:</dt>
            <dd><?= $this->Time->timeAgoInWords($task['Task']['modified']) ?></dd>
            <dt><?= __("Subtask of")?>:</dt>
            <dd>
            <?php
            foreach($task['DependedOnBy'] as $dep){
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
            if (empty($task['DependedOnBy'])) {
                echo '<span class="muted">n/a</span>';
            } ?>
            </dd>
			<dd><?=$this->Html->link(__("View dependency tree"), array(
				'controller' => 'tasks',
				'action' => 'tree',
				'project' => $project['Project']['name'],
				$task['Task']['public_id'],
			))?></dd>
        </dl>
    </div>
</div>
