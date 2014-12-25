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

if (!isset($span)) {
    $span = 9;
}

echo $this->Bootstrap->input("task_id", array(
    "input" => $this->Form->input("task_id", array(
        "label" => false,
        "class" => "span{$span}"
    )),
    "label" => __("Attached Task"),
));

echo $this->Bootstrap->input("mins", array(
    "input" => $this->Form->text("mins", array("class" => "span{$span}", "placeholder" => "e.g. 1h 30m", "autofocus" => "true")),
    "label" => __("Time Taken"),
    "help_block" => __("The amount of time youve taken (in the format #h #m)")
));

echo $this->Bootstrap->input("description", array(
    "input" => $this->Form->textarea("description", array("class" => "span{$span}")),
    "label" => __("Description"),
    "help_inline" => __("(Optional)")
));

if (isset($this->request->data['Time'])) {
    $date = $this->request->data['Time']['date'];
} else {
    $date = date('Y-m-d', time());
}

echo $this->element('datepicker', array(
	'name' => 'date',
	'value' => $date,
	'label' => __("Date"),
	'classes' => array("span$span"),
));

