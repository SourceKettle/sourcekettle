<?php
/**
 *
 * Modal class for APP/tasks/view for the SourceKettle system
 * Shows a modal box for resolving a task
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
<div class="modal hide" id="resolveModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4><?= $this->DT->t('modal.resolve.header') ?></h4>
    </div>
    <?= $this->Form->create('TaskComment', array('url' => array('project' => $project['Project']['name'], 'controller' => 'tasks', 'action' => 'resolve', $task['Task']['id']))) ?>
    <div class="modal-body">
        <p>
            <?= $this->DT->t('modal.resolve.body') ?>
        </p>
        <?php
        echo $this->Bootstrap->input("comment", array(
            "input" => $this->Form->input("comment", array(
                "type" => "textarea",
                "class" => "span5",
                "label" => false,
                "placeholder" => $this->DT->t('modal.resolve.comment.placeholder')
            )),
            "label" => false,
        ));
        ?>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal"><?= $this->DT->t('modal.resolve.close') ?></a>
        <?= $this->Bootstrap->button($this->DT->t('modal.resolve.submit'), array("style" => "primary")) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
