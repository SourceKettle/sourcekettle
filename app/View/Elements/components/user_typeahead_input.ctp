<?php
if (isset($url)) {
    $url = $this->Html->url($url, true);
} else {
    $url  = $this->Html->url(
        array(
            'api' => true,
            'controller' => 'users',
            'action' => 'autocomplete',
        ),
        true
    );
}
$this->Html->scriptBlock("
    jQuery(function(){
	var cache = {};
        $('.user_typeahead').typeahead({
            items: 5,
            minLength: 1,
            source: function (query, process) {
                $.get('$url', { query: query }, function (data) {
                    process($.parseJSON(data).users);
                });
            }
        });
    });
", array('inline' => false));

if (!isset($properties['class'])) {
    $properties['class'] = '';
}
$properties['class'] .= 'user_typeahead typeahead';

if (!isset($properties['data-provide'])) {
    $properties['data-provide'] = '';
}
$properties['data-provide'] .= ' typeahead';
?>
<?= $this->Form->text($name, $properties) ?>
