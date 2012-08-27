<?php
/**
 *
 * Element for displaying comments on Tasks in the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Element.Task
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="row-fluid">

    <div class="span1">
        <?= $this->Html->link(
            $this->Gravatar->image($comment['User']['email'], array('d' => 'mm')),
            array('controller' => 'users', 'action' => 'view', $comment['User']['id']),
            array('escape' => false, 'class' => 'thumbnail')
        ) ?>
    </div>
    <div class="span10">
        <div class="well col">
            <button type="button" class="close"><?= $this->Bootstrap->icon('remove-circle') ?></button>
            <button type="button" class="close"><?= $this->Bootstrap->icon('pencil') ?></button>
            <h5><?= $this->Bootstrap->icon('comment') ?><small> <?= $comment['User']['name'] ?> <?= $this->DT->t('history.commented.action') ?> <?= $this->Time->timeAgoInWords($comment['TaskComment']['created']) ?></small></h5>
            <hr />
            <p><?= $comment['TaskComment']['comment'] ?></p>
        </div>
    </div>
    <div class="span1"></div>

</div>