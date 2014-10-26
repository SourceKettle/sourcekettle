<?php
/**
 *
 * Modal class for APP/tasks/add for the SourceKettle system
 * Shows a modal box for un-resolving a task
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Task
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="modal hide" id="unresolveModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4><?= $this->DT->t('modal.unresolve.header') ?></h4>
    </div>
    <?= $this->Form->create('TaskComment', array('url' => array('project' => $project['Project']['name'], 'controller' => 'tasks', 'action' => 'unresolve', $task['Task']['public_id']))) ?>
    <div class="modal-body">
        <p>
            <?= $this->DT->t('modal.unresolve.body') ?>
        </p>
        <?php
        echo $this->Bootstrap->input("comment", array(
            "input" => $this->Form->input("comment", array(
                "type" => "textarea",
                "class" => "span5",
                "label" => false,
                "placeholder" => $this->DT->t('modal.unresolve.comment.placeholder')
            )),
            "label" => false,
        ));
        ?>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal"><?= $this->DT->t('modal.unresolve.close') ?></a>
        <?= $this->Bootstrap->button($this->DT->t('modal.unresolve.submit'), array("style" => "primary")) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
