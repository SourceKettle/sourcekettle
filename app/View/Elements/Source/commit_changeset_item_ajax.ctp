<?php
if (strlen($file) > 80) {
    if (strpos($file, '/')) {
        $pathParts = explode('/', $file);
        $fileName = $pathParts[0].'/.../'.$pathParts[sizeof($pathParts)-1];
    } else {
        $fileName = "filename ommitted due to length (>200 chars)";
    }
} else {
    $fileName = $file;
}
?>
<span class="fileDiff">
    <div class="well codewell">
        <div class="prettyprintheader row-fluid">
            <div class="span10">
                <p>
                    <span class="label label-success">+ ?</span>
                    <span class="label label-important">- ?</span>
                    <?= $fileName ?>
                </p>
            </div>
            <div class="span2">
                <div class="btn-group pull-right">
                    <?php
                        echo $this->Bootstrap->button_link('Get Details',
                            '#',
                            array('size' => 'mini', "class" => "btn moreButton", "data-file" => $file)
                        );
                    ?>
                </div>
            </div>
        </div>
    </div>
</span>
