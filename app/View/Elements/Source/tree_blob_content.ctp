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

		<? // TODO this is a quick and dirty hack to make sure we only display
		   // text-y things and image-y things. Long term something like
		   // http://viewerjs.org/ would be a lot nicer!
		if (preg_match('/^text\//', $tree['mimeType'])) {?>

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

		<?} elseif (preg_match('/^image\//', $tree['mimeType'])) {?>

		<img alt='An image' src='<?=$this->Source->fetchRawUrl($project['Project']['name'], $branch, $path)?>'/>

		<?} else {?>

		(can't render file, take a look at the <a href='<?=$this->Source->fetchRawUrl($project['Project']['name'], $branch, $path)?>'>raw view</a> instead!)

		<? } ?>
    </div>
</div>
