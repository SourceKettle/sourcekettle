<?php
    $fileContent = preg_split("/\015\012|\015|\012/", $tree['content']);
    $size = sizeof($fileContent);
?>
<div class="span10">
    <div class="well codewell">
        <div class="prettyprintheader row-fluid">
            <div class="span6">
                <p><i class="icon-file"></i><?= $size ?> lines</p>
            </div>
            <div class="span6">
                <div class="btn-group pull-right">
                    <?php
                        echo $this->Bootstrap->button_link('Raw',
                            $this->Source->fetchRawUrl($project['Project']['name'], $branch, $path),
                            array('size' => 'mini', "class" => "btn")
                        );
                        echo $this->Bootstrap->button_link('History',
                            $this->Source->fetchHistoryUrl($project['Project']['name'], $branch, $path),
                            array('size' => 'mini', "class" => "btn")
                        );
                    ?>
                </div>
            </div>
        </div>
        <pre class="prettyprint"><code><?php
            $wordWidth = strlen((string) $size);
            $i = 1;

            foreach ($fileContent as $line) {
                $space = '';

                for ($x = 0; $x <= $wordWidth - strlen((string) $i); $x++) {
                    $space .= ' ';
                }
                echo '<span class="nocode"> '.$i++.$space.'</span> '.htmlentities($line)."\n";
            }
        ?></code></pre>
    </div>
</div>