<?php
/**
 *
 * Modal class for APP/tasks/add for the DevTrack system
 * Shows a modal box for assigning users
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements.Task
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="modal hide" id="assignModal">
    <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <p>
            <?= $this->DT->t('modal.assign.body') ?>
            '<?= $this->DT->t('modal.assign.submit') ?>'
        </p>
        <?php
            echo $this->Form->create('TaskAssignee', array('class' => 'form-inline'));

            echo $this->Form->input('assignee', array(
                'options' => $collaborators,
                'empty' => false,
                'selected' => "$user_name [$user_email]",
                'label' => false,
                'id' => 'appendedInputButton',
                'class' => 'span5',
            ));
        ?>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal"><?= $this->DT->t('modal.assign.close') ?></a>
        <?= $this->Bootstrap->button($this->DT->t('modal.assign.submit'), array("style" => "success")) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
