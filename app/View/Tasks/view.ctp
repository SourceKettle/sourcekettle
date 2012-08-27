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

$edit_comment  = $this->Form->create('TaskCommentEdit', array('class' => 'form'));
$edit_comment .= $this->Form->hidden('id');
$edit_comment .= $this->Bootstrap->input("comment", array(
    "input" => $this->Form->textarea("comment", array("class" => "span12", "rows" => 5)),"label" => false)
);
$edit_comment .= $this->Bootstrap->button($this->DT->t('history.editcomment.submit'), array("style" => "primary", 'class' => 'controls'));
$edit_comment .= $this->Form->end();

// The following JS will change a comment box into an input box
$this->set('js_blocks_for_layout', array(
    "$('.comment').find(':button.edit').bind('click', function() {
        var open = $('[name=\"data[TaskCommentEdit][id]\"]').parent('form').parent('.comment');
        if (open) {
            open.find('form').remove();
            open.find('p').show();
        }
        var p = $(this).parent('.comment');
        p.find('p').hide();
        p.append('$edit_comment');
        p.find('textarea').html(p.find('p').text());
        $('[name=\"data[TaskCommentEdit][id]\"]').attr('value', p.attr('id'));
    });"
));
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

                    <div class="span1"></div>
                    <div class="span10">
                        <div class="btn-toolbar">
                            <div class="btn-group">
                                <?= $this->Bootstrap->button($this->DT->t('bar.task').$task['Task']['id'], array("class" => "disabled")) ?>
                                <?= $this->Bootstrap->button_link($this->DT->t('bar.edit'), array('project' => $project['Project']['name'], 'action' => 'edit', $task['Task']['id']), array("style" => "primary")) ?>
                            </div>
                            <div class="btn-group pull-right">
                                <?= $this->Bootstrap->button($this->DT->t('bar.assign')) ?>
                                <?= $this->Bootstrap->button($this->DT->t('bar.selfassign')) ?>
                                <?= $this->Bootstrap->button($this->DT->t('bar.close'), array("style" => "success")) ?>
                            </div>
                        </div>
                    </div>
                    <div class="span1"></div>

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
                            <h5>
                                <?= $this->Bootstrap->icon('pencil') ?>
                                <small>
                                    <?= $task['Owner']['name'] ?>
                                    <?= $this->DT->t('history.create.action') ?>
                                    <?= $this->Time->timeAgoInWords($task['Task']['created']) ?>
                                </small>
                                <span class="pull-right">
                                    <? if (!is_null($task['Assignee']['id'])) : ?>
                                        <?= $this->DT->t('history.assignee.assigned') ?>
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
                                        <?= $this->DT->t('history.assignee.none') ?>
                                    <? endif; ?>
                                </span>
                            </h5>
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

                 <div class="row-fluid">

                    <div class="span1">
                        <?= $this->Html->link(
                            $this->Gravatar->image($user_email, array('d' => 'mm')),
                            array('controller' => 'users', 'action' => 'view', $user_id),
                            array('escape' => false, 'class' => 'thumbnail')
                        ) ?>
                    </div>
                    <div class="span10">
                        <div class="well col">
                            <?php
                            echo $this->Form->create('TaskComment', array('class' => 'form'));

                            echo $this->Bootstrap->input("comment", array(
                                "input" => $this->Form->textarea("comment", array(
                                    "class" => "span12",
                                    "rows" => 5,
                                    "placeholder" => $this->DT->t('history.newcomment.placeholder')
                                )),
                                "label" => false,
                            ));

                            echo $this->Bootstrap->button($this->DT->t('history.newcomment.submit'), array("style" => "primary", 'class' => 'controls'));
                            echo $this->Form->end();
                            ?>
                        </div>
                    </div>
                    <div class="span1"></div>

                </div>

            </div>
        </div>
    </div>
</div>
