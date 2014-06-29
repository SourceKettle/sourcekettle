<?php
/**
 *
 * Modal class for APP/times/add for the SourceKettle system
 * Shows a modal box for adding time elements
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Time
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
// Submit the serialize data on submit click
$this->Js->get('#TimeMyaddForm')->event('submit',
    $this->Js->request(
        array('action' => 'add', 'project' => $project['Project']['name'], 'controller' => 'times'),
        array(
            'update' => '#flashes', // element to update
            // after form submission
            'data' => $this->Js->get('#TimeMyaddForm')->serializeForm(array('isForm' => true, 'inline' => true)),
            'async' => true,
            'dataExpression'=>true,
            'method' => 'POST'
        )
    )."$('#addTimeModal').modal('hide');"
);
?>
<div class="modal hide" id="addTimeModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4>Log time for this Project</h4>
    </div>
    <?= $this->Form->create('Time', array('id' => 'TimeMyaddForm', 'default' => false, 'class' => 'form-horizontal', 'style' => 'margin-bottom: 0px;')) ?>
    <div class="modal-body">
        <?= $this->element('Time/add') ?>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Close</a>
        <?= $this->Bootstrap->button("Submit", array("style" => "primary")) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
