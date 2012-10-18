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
<div class="well codewell">
    <div class="prettyprintheader row-fluid">
        <div class="span10">
            <p>
                <span class="label label-success">+ <?= $diff['more'] ?></span>
                <span class="label label-important">- <?= $diff['less'] ?></span>
                <?= $fileName ?>
            </p>
        </div>
        <div class="span2">
            <div class="btn-group pull-right">
                <?php
                    echo $this->Bootstrap->button_link('See File',
                        $this->Source->fetchTreeUrl($project['Project']['name'], $commit['hash'], $file),
                        array('size' => 'mini', "class" => "btn")
                    );
                ?>
            </div>
        </div>
    </div>
    <table class="diff_table prettyprint">
    <?php
        if (empty($diff['hunks'])) {
            ?>
            <tr class="diff_row">
                <td class="pre_col">
                    <pre class="diff_pre hunk_header"> Empty File</pre>
                </td>
            </tr>
            <?
        }
        foreach ($diff['hunks'] as $a => $hunk) {
            $d_m = $diff['hunks_def'][$a]['-'];
            $d_a = $diff['hunks_def'][$a]['+'];
            ?>
            <tr class="diff_row">
                <td class="diff_col old_col">...</td>
                <td class="diff_col new_col">...</td>
                <td class="pre_col">
                    <pre class="diff_pre hunk_header">   @@ -<?=$d_m[0]?>,<?=$d_m[1]?> +<?=$d_a[0]?>,<?=$d_a[1]?> @@ </pre>
                </td>
            </tr>
            <?
            foreach ($hunk as $line) {
                switch ($line[0]) {
                    case '+': $color = "pre_green green_back"; break;
                    case '-': $color = "pre_red red_back"; break;
                    case ' ': $color = "pre_normal"; break;
                }
                ?>
                <tr class="diff_row">
                    <td class="diff_col old_col"><?= $line[1] ?></td>
                    <td class="diff_col new_col"><?= $line[2] ?></td>
                    <td class="pre_col">
                        <pre class="diff_pre <?= $color ?>"> <?= $line[0] ?> <?= $line[3] ?></pre>
                    </td>
                </tr>
                <?
            }
        }
    ?>
    </table>
</div>
