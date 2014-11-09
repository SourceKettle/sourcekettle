<?php

$this->Html->script("elements/typeahead_input.js", array("inline" => false));

if (!isset($properties['class'])) {
    $properties['class'] = '';
}
$properties['class'] .= ' typeahead';

if (!isset($properties['data-provide'])) {
    $properties['data-provide'] = '';
}

$properties['autocomplete'] = 'off';
$properties['data-provide'] .= ' typeahead';
$properties['data-api-url'] = $this->Html->url($url, true);
$properties['data-api-name'] = $jsonListName;
?>
<?= $this->Form->text($name, $properties) ?>
