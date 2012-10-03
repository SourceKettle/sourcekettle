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
        $('.typeahead').typeahead({
            items: 5,
            minLength: 2,
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
$properties['class'] .= ' typeahead';

if (!isset($properties['data-provide'])) {
    $properties['data-provide'] = '';
}
$properties['data-provide'] .= ' typeahead';
?>
<?= $this->Form->text($name, $properties) ?>
