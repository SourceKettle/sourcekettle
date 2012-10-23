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
                    <?php
                        if ($diff['more']) {
                            echo '<span class="label label-success">+ folder</span>';
                        }
                        if ($diff['less']) {
                            echo '<span class="label label-important">- folder</span>';
                        }
                        echo " $fileName";
                    ?>
                </p>
            </div>
        </div>
    </div>
</span>
