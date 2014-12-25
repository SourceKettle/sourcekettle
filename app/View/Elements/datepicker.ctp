<?php

// Include CSS and JavaScript for the bootstrap datepicker
$this->Html->script('/bootstrap-datepicker/js/bootstrap-datepicker.js', array('inline' => false, 'once' => true));
$this->Html->script('activate-datepickers.js', array('inline' => false, 'once' => true));
$this->Html->css('/bootstrap-datepicker/css/datepicker.css', null, array('inline' => false, 'once' => true));

// Set defaults
if (!isset($classes)) {
	$classes = array();
}

if (!isset($dateFormat)) {
	$dateFormat = "yyyy-mm-dd";
}

if (!isset($label)) {
	$label = null;
}

if (!isset($helpBlock)) {
	$helpBlock = null;
}

// The input is just a form element with the class 'datepicker', activate-datepicker.js does the magic
echo $this->Bootstrap->input($name, array(
	"input" => $this->Form->text($name, array(
		"class" => "datepicker ".implode(' ', $classes),
		"value" => $value,
		"data-date-format" => $dateFormat)
	),
	"label" => $label,
	"help_block" => $helpBlock,
));

