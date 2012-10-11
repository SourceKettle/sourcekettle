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


// The following JS will change a comment box into an input box
$this->Html->scriptBlock("
    $('.comment').find(':button.edit').bind('click', function() {
		$('.comment form').hide();
        var p = $(this).parent('.comment');
        p.find('p').hide();
        p.find('form').show();
    });

	$('.comment').find (':button.delete').click (function() {
		var result = confirm ('Are you sure you want to delete this comment?');
		if ( result ) {
			$(this).parent('.comment').find('form.comment-delete').submit();
		}
	});
", array('inline' => false));

echo $this->element('Task/modal_close');
echo $this->element('Task/modal_resolve');
echo $this->element('Task/modal_assign');

?>

<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
    <div class="span10">
        <div class="row">
            <?= $this->element('Task/topbar_view', array('id' => $task['Task']['id'], 'dependenciesComplete' => $task['Task']['dependenciesComplete'])) ?>
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
                            $this->Gravatar->image($user_email, array('d' => 'mm')),
                            array('controller' => 'users', 'action' => 'view', $user_id),
                            array('escape' => false, 'class' => 'thumbnail')
                        ) ?>
                    </div>
                    <div class="span11">
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

                </div>

            </div>
        </div>
    </div>
</div>
