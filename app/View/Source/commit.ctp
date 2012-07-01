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
        <?= $this->element('project_sidebar', array('project' => $pname, 'action' => 'collaborators')) ?>
    </div>
    <div class="row">
        <div class="span10">
            <table class="well table table-striped">
                <tr>
                    <td>
                        <h4><?= ucfirst($commit['Commit']['subject']) ?></h4>
                        <br>
                        <h5><small><?= $commit['Commit']['body'] ?></small></h5>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5><?= $commit['Commit']['author']['name'].' &lt;'.$commit['Commit']['author']['email'].'&gt;' ?> <small>authored <?= $this->Time->timeAgoinWords($commit['Commit']['date']) ?></small></h5>
                    </td>
                </tr>
            </table>
        </div>
        <div class="span10">
            <? foreach ($commit['Commit']['diff'] as $file => $diff) : ?>
            <div class="well">
                <h5>A: <?= $diff['more'] ?> D: <?= $diff['less'] ?> <?= $file ?></h5>
                <pre><?= $this->CommandLineColor->translateColors(htmlspecialchars($diff['diff'])) ?></pre>
            </div>
            <? endforeach; ?>
        </div>
    </div>
</div>
