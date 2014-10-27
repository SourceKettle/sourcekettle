<?php
/**
 *
 * Modal class for APP/tasks/add for the SourceKettle system
 * Shows a modal box for adding task elements
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
        <h4><?= __('Add a new task to this Project') ?></h4>
    </div>
    <?= $this->Form->create('Task', array('id' => 'UserMyaddForm', 'default' => false, 'style' => 'margin-bottom: 0px;')) ?>
    <div class="modal-body">
        <?php
        echo $this->Bootstrap->input("subject", array(
            "input" => $this->Form->text("subject", array("class" => "span5", "placeholder" => __('Quick, yet informative, description'))),
            "label" => __('Subject'),
        ));

        echo $this->Bootstrap->input("description", array(
            "input" => $this->Form->input("description", array(
                "type" => "textarea",
                "class" => "span5",
                "label" => false,
                "placeholder" => __('Longer and more descriptive explanation...')
            )),
            "label" => false,
        ));
        ?>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal"><?= __('Close') ?></a>
        <?= $this->Bootstrap->button(__('Submit and Edit'), array("style" => "primary")) ?>
        <?= $this->Bootstrap->button(__('Submit'), array("style" => "primary")) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
