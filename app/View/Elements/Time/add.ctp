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
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Time
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->Html->script('bootstrap-datepicker', array('block' => 'scriptBottom'));
$this->Html->scriptBlock("$('.dp1').datepicker()", array('inline' => false));
$this->Html->css('datepicker', null, array ('inline' => false));

if (!isset($span)) {
    $span = 9;
}

echo $this->Bootstrap->input("mins", array(
    "input" => $this->Form->text("mins", array("class" => "span{$span}", "placeholder" => "1h 30m")),
    "label" => "Time Taken",
    "help_block" => "The amount of time youve taken (in the format #h #m)"
));

echo $this->Bootstrap->input("description", array(
    "input" => $this->Form->textarea("description", array("class" => "span{$span}")),
    "label" => "Description",
    "help_inline" => "(Optional)"
));

echo $this->Bootstrap->input("date", array(
    "input" => $this->Form->text("date", array("class" => "dp1 span{$span}", "value" => date('Y-m-d', time()), "data-date-format" => "yyyy-mm-dd")),
    "label" => "Date"
));

echo $this->Bootstrap->input("task_id", array(
    "input" => $this->Form->input("task_id", array(
        "label" => false,
        "class" => "span{$span}"
    )),
    "label" => 'Attached Task',
));
