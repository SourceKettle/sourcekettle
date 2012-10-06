<?php
/**
 *
 * View class for APP/Source/commit for the DevTrack system
 * Allows users to view a commit
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Source
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('pages/diff', null, array ('inline' => false));

$smallText = " <small>source code</small>";
$pname = $project['Project']['name'];

// Header for the page
echo $this->Bootstrap->page_header($pname . $smallText);

?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <?= $this->element('Source/topbar', array('branches' => $branches, 'branch' => $branch)) ?>
            <div class="span10">
                <div class="well">
                    <div class="row-fluid">
                        <div class="span10">
                            <h4>
                                <?= ucfirst($commit['Commit']['subject']) ?>
                            </h4>
                            <br>
                            <h5>
                                <small><?= $commit['Commit']['body'] ?></small>
                            </h5>
                             <h5>
                                <?= $commit['Commit']['author']['name'].' &lt;'.$commit['Commit']['author']['email'].'&gt;' ?>
                                <small>authored <?= $this->Time->timeAgoinWords($commit['Commit']['date']) ?></small>
                            </h5>
                        </div>
                        <div class="span2">
                            <?= $this->Bootstrap->button_link('See Code', array('project' => $project['Project']['name'], 'action' => 'tree', $commit['Commit']['hash']), array("style" => "info", "class" => "pull-right")) ?>
                        </div>
                    </div>
                </div>
            </div>
            <? foreach ($commit['Commit']['diff'] as $file => $diff) : ?>
            <div class="span10">
                <div class="well">
                    <div class="row-fluid">
                        <div class="span10">
                            <h4><?= $file ?></h4>
                            <h6>
                                <span class="label label-success">Added</span> <span class="green_front"><?= $diff['more'] ?></span>
                                <span class="label label-important">Deleted</span> <span class="red_front"><?= $diff['less'] ?></span>
                            </h6>
                        </div>
                        <div class="span2">
                            <?= $this->Bootstrap->button_link('See File', array_merge(array('project' => $project['Project']['name'], 'action' => 'tree', $commit['Commit']['hash']), explode('/', $file)), array("style" => "info", "class" => "pull-right")) ?>
                        </div>
                    </div>
                    <br>
                    <table class="diff_table">
                    <?php
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
            </div>
            <? endforeach; ?>
        </div>
    </div>
</div>
