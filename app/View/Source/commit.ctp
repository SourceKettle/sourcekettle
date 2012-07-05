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
$smallText = " <small>" . $project['Project']['description'] . " </small>";
$pname = $project['Project']['name'];

// Header for the page
echo $this->Bootstrap->page_header($pname . $smallText);

?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span9">
        <div class="row">
            <?= $this->element('Source/topbar', array('branches' => $branches, 'branch' => $branch)) ?>
            <div class="well span9">
                <div class="row">
                    <div class="span7">
                        <h4>
                            <?= ucfirst($commit['Commit']['subject']) ?>
                        </h4>
                        <br>
                        <h5>
                            <small><?= $commit['Commit']['body'] ?></small>
                        </h5>
                    </div>
                    <div class="span2">
                        <?= $this->Bootstrap->button_link('See Code', array('project' => $project['Project']['name'], 'action' => 'tree', $commit['Commit']['hash']), array("style" => "info", "class" => "pull-right")) ?>
                    </div>
                    <div class="span9">
                        <h5>
                            <?= $commit['Commit']['author']['name'].' &lt;'.$commit['Commit']['author']['email'].'&gt;' ?> 
                            <small>authored <?= $this->Time->timeAgoinWords($commit['Commit']['date']) ?></small>
                        </h5>
                    </div>
                </div>
            </div>
            <? foreach ($commit['Commit']['diff'] as $file => $diff) : ?>
            <div class="well span9">
                <div class="row">
                    <div class="span7">
                        <h4><?= $file ?></h4>
                        <h6>
                            <span class="label label-success">Added</span> <span style="color:green"><?= $diff['more'] ?></span>
                            <span class="label label-important">Deleted</span> <span style="color:red"><?= $diff['less'] ?></span>
                        </h6>
                    </div>
                    <div class="span2">
                        <?= $this->Bootstrap->button_link('See File', array_merge(array('project' => $project['Project']['name'], 'action' => 'tree', $commit['Commit']['hash']), explode('/', $file)), array("style" => "info", "class" => "pull-right")) ?>
                    </div>
                </div>
                <br>
                <pre><?= $this->CommandLineColor->translateColors(htmlspecialchars($diff['diff'])) ?></pre>
            </div>
            <? endforeach; ?>
        </div>
    </div>
</div>
