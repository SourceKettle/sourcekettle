<?php
/**
 *
 * Element for displaying comments on Tasks in the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Element.Task
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="row-fluid">
        <div class="well span8 offset2 comment" id="<?= $comment['TaskComment']['id'] ?>">
            <?php if ($current_user['is_admin'] == 1 || $current_user['id'] == $comment['User']['id']): ?>
            	<button type="button" class="close delete"><?= $this->Bootstrap->icon('remove-circle'); ?></button>
                <button type="button" class="close edit"><?= $this->Bootstrap->icon('pencil'); ?></button>
				<?= $this->Form->create ('TaskCommentDelete', array ('class' => 'hide comment-delete', 'url' => array('controller' => 'tasks', 'action' => 'deleteComment', 'project' => $project['Project']['name'], $comment['TaskComment']['id']))); ?>
				<?= $this->Form->hidden ('id', array ('value' => $comment['TaskComment']['id'])); ?>
				<?= $this->Form->end(); ?>
            <?php endif; ?>

	<h5>
        <?= $this->Bootstrap->icon('comment') ?>
        <?= $this->Html->link(
            $this->Gravatar->image($comment['User']['email'], array('d' => 'mm', 'size' => 20)),
            array('controller' => 'users', 'action' => 'view', $comment['User']['id']),
            array('escape' => false,) 
        ) ?>
	<small> <?= h($comment['User']['name']) ?> <?= __('commented') ?> <?= $this->Time->timeAgoInWords($comment['TaskComment']['created']) ?></small></h5>
            <hr />
            <p><?= $this->Markitup->parse($comment['TaskComment']['comment']) ?></p>
			<?= $this->Form->create('TaskCommentEdit', array ('class' => 'hide', 'url' => array('controller' => 'tasks', 'action' => 'updateComment', 'project' => $project['Project']['name'], $comment['TaskComment']['id']))); ?>
			<?= $this->Bootstrap->input("comment", array(
    			"input" => $this->Form->textarea("comment", array("value" => $comment['TaskComment']['comment'], "class" => "span12", "rows" => 5)),"label" => false)); ?>
			<?= $this->Bootstrap->button(__("Update comment"), array("style" => "primary", 'class' => 'controls')); ?>
			<?= $this->Form->end(); ?>
       </div>

</div>
