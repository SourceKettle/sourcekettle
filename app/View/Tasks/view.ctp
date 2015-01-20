<?php
/**
 *
 * View class for APP/tasks/view for the SourceKettle system
 * Allows a user to view a task for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Tasks
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('tasks', array ('inline' => false));
$this->Html->script("tasks", array ('inline' => false));
$apiUrl = $this->Html->url(array('controller' => 'tasks', 'action' => 'update', 'api' => true, 'project' => $task['Project']['name']));
?>

<?= $this->Task->allDropdownMenus() ?>

<?= $this->DT->pHeader(__("Task card and log")) ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
    <div class="span10">
        <div class="row">
            <div class="span10 well task-lozenge task-card" data-taskid="<?=h($task['Task']['public_id'])?>" data-api-url="<?=$apiUrl?>">

                <div class="row-fluid task-view-top">
			<div class="span3 task-view-priority">
            		<h5><?= __("Priority") ?></h5>
			<?= $this->Task->priorityDropdownButton($task, true) ?>
			</div>

			<div class="span6 task-view-subject">
			<h3>#<?=$task['Task']['public_id']?>:

				<span class="task-subject-text"><?= $task['Task']['subject'] ?></span>

				<span class="edit-form input-append hide">
				<?= $this->Form->create('Task', array ('url' => array('controller' => 'tasks', 'action' => 'edit', 'project' => $project['Project']['name'], $task['Task']['public_id']))); ?>
    				<?= $this->Form->textarea("subject", array("value" => $task['Task']['subject'], "rows" => 1)); ?>
				<?= $this->Bootstrap->button(__("Update"), array("style" => "primary")); ?>
				<?= $this->Form->end(); ?>

				</span>

                	<button type="button" class="close edit"><?= $this->Bootstrap->icon('pencil'); ?></button>
			</h3>

                        <small>
        			<?= $this->Html->link(
			            $this->Gravatar->image($task['Owner']['email'], array('d' => 'mm', 'size' => 16)),
			            array('controller' => 'users', 'action' => 'view', $task['Owner']['id']),
			            array('escape' => false,) 
			        ) ?>
                		<?= $this->Html->link(
                    		$task['Owner']['name'],
                    		array('controller' => 'users', 'action' => 'view', $task['Owner']['id'])
                		) ?>
                            <?= __("created this task") ?>
                            <?= $this->Time->timeAgoInWords($task['Task']['created']) ?>
                        </small>
			</div>
			<div class="span3 task-view-assignee">
            		<h5><?= __("Assigned to") ?></h5>
	    		<?=$this->Task->assigneeDropdownButton($task, 23, true, true, true)?>
			</div>
		</div>

                <div class="row-fluid task-view-middle">
			<div class="span6 offset3 task-view-description">
			<h4><?=__("Description")?></h4>
                	<button type="button" class="close edit"><?= $this->Bootstrap->icon('pencil'); ?></button>
            		<div class="task-description-text"><?= $this->Markitup->parse($task['Task']['description']) ?></div>
			
			<span class="edit-form hide">
				<?= $this->Form->create('Task', array ('url' => array('controller' => 'tasks', 'action' => 'edit', 'project' => $project['Project']['name'], $task['Task']['public_id']))); ?>
    				<?= $this->Form->textarea("description", array("value" => $task['Task']['description'], "class" => "task-description-input", "rows" => 10)); ?>
				<?= $this->Bootstrap->button(__("Update"), array("style" => "primary")); ?>
				<?= $this->Form->end(); ?>
				</span>
			</div>
		</div>

                <div class="row-fluid task-view-bottom">
			<div class="span3 task-view-points">
			<h5><?=__("Estimate")?></h5>
                	<?=$this->Task->storyPointsControl($task, true)?></dd>
			</div>
			<div class="span6 task-view-selectors">
			<h5><?=__("Attributes")?></h5>
            		<?= $this->Task->typeDropdownButton($task) ?>
	    		<?= $this->Task->milestoneDropdownButton($task, 23, true, true)?>
			<?= $this->Task->statusDropdownButton($task, true) ?>
			</div>
			<div class="span3 task-view-time-logged">
			<h5><?=__("Time logged")?></h5>
			<?= TimeString::renderTime($totalTime, 's') ?>
			<?= $this->Html->link(
				'<i class="icon-plus" title="'.__("Log time").'"></i> '.__("Log time"), array(
				"controller" => "times",
				"action" => "add",
				"project" => $project['Project']['name'],
				"?" => array("task_id" => $task['Task']['public_id']),
			), array("escape" => false))?>
			</div>
		</div>
	</div>
	</div>
	<div class="row-fluid">
	<div class="span12 task-history">
		<h3><?=__("Task history")?></h3>
                <?php
                    foreach ($changes as $change) {
                        if ( isset($change['ProjectHistory']) ) {
                            echo $this->element('Task/change_box', array('change' => $change));
                        } else {
                            echo $this->element('Task/comment_box', array('comment' => $change));
                        }
                    }
                ?>

                 <div class="row-fluid">

                    <div class="span8 offset2">
                        <div class="well col">
                            <?php
                            echo $this->Form->create('TaskComment', array('class' => 'form', 'url' => array('controller' => 'tasks', 'action' => 'comment', 'project' => $project['Project']['name'], $task['Task']['public_id'])));

				echo $this->Bootstrap->input("comment", array(
					"input" => $this->Markitup->editor("comment", array(
						"class" => "span11",
						"label" => false,
						"placeholder" => __("Add a new comment to this task...")
					)),
					"label" => false,
				));

                            echo $this->Bootstrap->button(__("Comment"), array("style" => "primary", 'class' => 'controls'));
                            echo $this->Form->end();
                            ?>
                        </div>
                    </div>

                </div>
	
            </div>
        </div>
    </div>
</div>
