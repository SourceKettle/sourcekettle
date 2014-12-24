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

$this->Html->css('tasks.view', null, array ('inline' => false));
$this->Html->script("tasks.view", array ('inline' => false));

echo $this->element('Task/modal_close');
echo $this->element('Task/modal_assign');

if (in_array($task['TaskStatus']['name'], array('open', 'in progress'))) {
    echo $this->element('Task/modal_resolve');
} else if ($task['TaskStatus']['name'] == 'resolved'){
    echo $this->element('Task/modal_unresolve');
}?>

<?= $this->DT->pHeader(__("A task for the Project")) ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
    <div class="span10">
        <div class="row">
            <?= $this->element('Task/topbar', array('id' => $task['Task']['public_id'], 'dependenciesComplete' => $task['Task']['dependenciesComplete'])) ?>
            <div class="span10">

                <div class="row-fluid">

                    <div class="span1">
                        <?= $this->Html->link(
                            $this->Gravatar->image($task['Owner']['email'], array('d' => 'mm')),
                            array('controller' => 'users', 'action' => 'view', $task['Owner']['id']),
                            array('escape' => false, 'class' => 'thumbnail')
                        ) ?>
                    </div>
                    <div class="span11">
                        <div class="well col">
                            <div class="row-fluid">
                                <h5>
                                    <?= $this->Bootstrap->icon('pencil') ?>
                                    <small>
                                        <?= h($task['Owner']['name']) ?>
                                        <?= __("created this task") ?>
                                        <?= $this->Time->timeAgoInWords($task['Task']['created']) ?>
                                    </small>
                                    <span class="pull-right">
                                        <? if (!is_null($task['Assignee']['id'])) : ?>
                                            <?= __("Assigned to:") ?>
                                            <?= $this->Html->link(
                                                $task['Assignee']['name'],
                                                array('controller' => 'users', 'action' => 'view', $task['Assignee']['id'])
                                            ) ?>
                                            <?= $this->Html->link(
                                                $this->Gravatar->image($task['Assignee']['email'], array('d' => 'mm', 's' => 24)),
                                                array('controller' => 'users', 'action' => 'view', $task['Assignee']['id']),
                                                array('escape' => false, 'class' => '')
                                            ) ?>
                                        <? else : ?>
                                            <?= __("No-one currently assigned") ?>
                                        <? endif; ?>
                                    </span>
                                </h5>
                            </div>
                            <div class="row-fluid">
                                <div class="span9">
                                    <h3><?= h($task['Task']['subject']) ?></h3>
                                </div>

                                <div class="span3">
									  <h5>
                                        <? if (!is_null($task['Milestone']['id'])) : ?>
                                            <?= _("Milestone:") ?>
                                            <?= $this->Html->link(
                                                $task['Milestone']['subject'],
												array('controller' => 'milestones', 'action' => 'view', 'project' => $task['Project']['name'], $task['Milestone']['id'])
                                            ) ?>
                                        <? else : ?>
                                            <?= __("No milestone") ?>
                                        <? endif; ?>
									  </h5>
                            	</div>
                            </div>
                            <hr />

                            <?= $this->element('Task/View/section_details') ?>

                            <div class="row-fluid">
                                <?php
                                if ($task['Task']['description'] != '') {
                                    echo '<div class="span6">';
                                    echo $this->element('Task/View/section_description');
                                    echo '</div>';
                                }
                                ?>
                                <div class="span6">
                                    <?= $this->element('Task/View/section_time') ?>
                                </div>
                            </div>
                        </div>
                    </div>

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

                 <div class="row-fluid">

                    <div class="span1">
                        <?= $this->Html->link(
                            $this->Gravatar->image($current_user['email'], array('d' => 'mm')),
                            array('controller' => 'users', 'action' => 'view', $current_user['id']),
                            array('escape' => false, 'class' => 'thumbnail')
                        ) ?>
                    </div>
                    <div class="span11">
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
