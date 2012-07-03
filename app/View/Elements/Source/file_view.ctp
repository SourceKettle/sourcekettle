<?php
    // Loose ends to tie up
    $url['action'] = 'raw';
?>
<div class="span7">
    <?= $this->Bootstrap->breadcrumbs(array("divider" => "/")) ?>
</div>
<div class="span1">
    <?= $this->Html->link("raw", $url, array("class" => "btn btn-default raw_button")) ?>
</div>
<div class="span2">
    <?= $this->Bootstrap->button_dropdown($this->Bootstrap->icon('random')." <strong>Branch: </strong>".substr($branch, 0, 10), array("class" => "branch_button", "links" => $branches)) ?>
</div>
<div class="span10">
    <?php
    echo '<pre class="prettyprint">';

    $array = preg_split("/\015\012|\015|\012/", $source);
    $array_size = strlen((string) sizeof($array));
    $i = 1;

    foreach ($array as $line) {
        $space = '';

        for ($x = 0; $x < $array_size - strlen((string) $i); $x++) {
            $space .= ' ';
        }
        echo '<span class="nocode">'.$i++.'</span>'.$space.htmlentities($line)."\n";
    }

    echo '</pre>';
    ?>
</div>
