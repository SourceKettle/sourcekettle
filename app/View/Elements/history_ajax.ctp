<?php
$rand = uniqid();
$url  = $this->Html->url(
    array(
        'api' => true,
        'project' => $project['Project']['name'],
        'controller' => 'projects',
        'action' => 'history',
        $historyCount
    ),
    true
);
if (isset($no_more) && $no_more) {
    $more = '';
} else {
    $more = "<ul class=\"pager\">";
    $more.= "<li>";
    $more.= $this->Html->link('See More', array('project' => $project['Project']['name'], 'action' => 'history'), array('escape' => false));
    $more.= "</li>";
    $more.= "</ul>";
}
$projectName = $project['Project']['name'];
$this->Html->scriptBlock("
    jQuery(function(){
        var details = {};
        details['project'] = '$projectName';
        console.log('$url');
        $.ajax({
            url: '$url',
            cache: false,
            data: details,
            type: 'GET',
            success: function(data){
                $('#histId$rand').fadeOut('slow', function() {
                    $('#histId$rand').html('<div class=\"well\">' + data + '$more</div>');
                    $('#histId$rand').fadeIn();
                });
            }
        });

    });
", array('inline' => false));
?>
<div id="histId<?=$rand?>">
    <div class="row-fluid">
        <div class=" span2 offset5">
            <div class="progress progress-striped active">
                <div class="bar" style="width: 100%;">Loading</div>
            </div>
        </div>
    </div>
</div>
