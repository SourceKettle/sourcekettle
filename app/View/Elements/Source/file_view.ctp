<?php
    // Loose ends to tie up
    $url['action'] = 'raw';
?>
<div class="span10">
    <div class="well">
        <div class="row-fluid">
            <div class="span10">
                <h4><?= $this->Html->link($file['message'], array('project' => $project['Project']['name'], 'action' => 'commit', $file['commit'])) ?></h4>
                <small> Last modified on <?= $this->Time->timeAgoinWords($file['updated']) ?></small>
            </div>
            <div class="span2">
                <?= $this->Bootstrap->button_link('Raw File', $url, array("style" => "info", "class" => "pull-right")) ?>
            </div>
        </div>
    </div>
    <?php
    echo '<pre class="prettyprint"><code>';

    $array = preg_split("/\015\012|\015|\012/", $file['content']);
    $array_size = strlen((string) sizeof($array));
    $i = 1;

    foreach ($array as $line) {
        $space = '';

        for ($x = 0; $x <= $array_size - strlen((string) $i); $x++) {
            $space .= ' ';
        }
        echo '<span class="nocode"> '.$i++.$space.'</span> '.htmlentities($line)."\n";
    }

    echo '</code></pre>';
    ?>
        <div class="row-fluid">
            <div class="span12">
                <?= $this->Bootstrap->button_link('View the History for this file', array_merge(array('project' => $project['Project']['name'], 'action' => 'history'), explode('/', $file['path'])), array("style" => "info", "class" => "span12")) ?>
            </div>
        </div>
</div>
