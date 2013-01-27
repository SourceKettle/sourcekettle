<?php
/**
 *
 * Modal class for APP/tasks/add for the DevTrack system
 * Shows a modal box for adding task elements
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Task
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$_dt = array('action' => 'add');

// Submit the serialize data on submit click
$this->Js->get('#UserMyaddForm')->event('submit',
    $this->Js->request(
        array('action' => 'add', 'project' => $project['Project']['name']),
        array(
            'update' => '#flashes', // element to update
            // after form submission
            'data' => $this->Js->get('#UserMyaddForm')->serializeForm(array('isForm' => true, 'inline' => true)),
            'async' => true,
            'dataExpression'=>true,
            'method' => 'POST'
        )
    )."$('#addTaskModal').modal('hide');"
);
?>
<div class="modal hide" id="addTaskModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4><?= $this->DT->t('modal.header.text', $_dt) ?></h4>
        <h6><small><?= $this->DT->t('modal.header.subtext', $_dt) ?></small></h6>
    </div>
    <?= $this->Form->create('Task', array('id' => 'UserMyaddForm', 'default' => false, 'style' => 'margin-bottom: 0px;')) ?>
    <div class="modal-body">
        <?php
        echo $this->Bootstrap->input("subject", array(
            "input" => $this->Form->text("subject", array("class" => "span5", "placeholder" => $this->DT->t('form.subject.placeholder', $_dt))),
            "label" => $this->DT->t('form.subject.label', $_dt),
        ));

        echo $this->Bootstrap->input("description", array(
            "input" => $this->Form->input("description", array(
                "type" => "textarea",
                "class" => "span5",
                "label" => false,
                "placeholder" => $this->DT->t('form.description.placeholder', $_dt)
            )),
            "label" => false,
        ));
        ?>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal"><?= $this->DT->t('modal.form.close', $_dt) ?></a>
        <?= $this->Bootstrap->button($this->DT->t('form.submit.continue', $_dt), array("style" => "primary")) ?>
        <?= $this->Bootstrap->button($this->DT->t('form.submit', $_dt), array("style" => "primary")) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
