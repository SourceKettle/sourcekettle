<?php
/**
 *
 * Modal class for APP/times/add for the DevTrack system
 * Shows a modal box for adding time elements
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements.Time
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->Html->script('bootstrap-datepicker', array('block' => 'scriptBottom'));
$this->Html->scriptBlock("$('.dp1').datepicker()", array('inline' => false));
$this->Html->css('datepicker', null, array ('inline' => false));

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
    )."$('#addTimeModal').modal('hide');"
);
?>
<div class="modal hide" id="addTimeModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4>Log time for this Project</h4>
        <h6><small>to the nearest 30 mins, please</small></h6>
    </div>
    <?= $this->Form->create('Time', array('id' => 'UserMyaddForm', 'default' => false, 'class' => 'form-horizontal', 'style' => 'margin-bottom: 0px;')) ?>
    <div class="modal-body">
        <?php
        echo $this->Bootstrap->input("mins", array(
            "input" => $this->Form->text("mins", array("class" => "input-small", "placeholder" => "1h 30m")),
            "label" => "Time Taken",
            "help_block" => "The amount of time youve taken (in the format #h #m)"
        ));

        echo $this->Bootstrap->input("description", array(
            "input" => $this->Form->textarea("description", array("class" => "input-xlarge")),
            "label" => "Description",
            "help_inline" => "(Optional)"
        ));

        echo $this->Bootstrap->input("date", array(
            "input" => $this->Form->text("date", array("class" => "dp1", "value" => date('Y-m-d', time()), "data-date-format" => "yyyy-mm-dd")),
            "label" => "Date"
        ));
        ?>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Close</a>
        <?= $this->Bootstrap->button("Submit", array("style" => "primary")) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
