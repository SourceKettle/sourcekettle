<?php
/**
 *
 * View class for APP/Source/commits for the DevTrack system
 * Allows users to view commits
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

// Base url for the view
$url = array('project' => $project['Project']['name'], 'action' => 'commits', $location[1]);
$this->Bootstrap->add_crumb($project['Project']['name'], $url);

// Create the base url to be used for all links and add breadcrumbs
for ($i = 2; $i <= sizeof($location)-1; $i++) {
    $url[] = $location[$i];
    $this->Bootstrap->add_crumb($location[$i], $url);
}

foreach ($branches as $a => $branch) {
    $branches[$a] = $this->Html->link($branch, array('project' => $project['Project']['name'], 'action' => 'commits', $branch));
}

// Header for the page
echo $this->Bootstrap->page_header($pname . $smallText);

?>
<div class="row">
    <div class="span2">
        <?= $this->element('project_sidebar', array('project' => $pname, 'action' => 'collaborators')) ?>
    </div>
    <div class="row">
        <div class="span8">
            <?= $this->Bootstrap->breadcrumbs(array("divider" => "/")) ?>
        </div>
        <div class="span2">
            <?= $this->Bootstrap->button_dropdown($this->Bootstrap->icon('random')." <strong>Branch: </strong>".$location[1], array("class" => "span2", "links" => $branches)) ?>
        </div>
        <div class="span10">
            <table class="well table table-striped">
            <? foreach ($commits as $commit) : ?>
                <tr>
                    <td>
                        <h4><?= ucfirst((strlen($commit['subject']) > 100) ? substr($commit['subject'], 0, 100).'...' : $commit['subject']) ?></h4>
                        <h5><?= $commit['author']['name'].' &lt;'.$commit['author']['email'].'&gt;' ?> <small><?= $this->Time->timeAgoinWords($commit['date']) ?></small></h5>
                    </td>
                </tr>
            <? endforeach; ?>
            </table>
        </div>
    </div>
</div>
