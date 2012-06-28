<?php
/**
 *
 * View class for APP/Source/tree for the DevTrack system
 * Allows users to view source for a blob
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
$url = array('project' => $project['Project']['name'], 'action' => 'tree', $location[1]);
$this->Bootstrap->add_crumb($project['Project']['name'], $url);

// Create the base url to be used for all links and add breadcrumbs
for ($i = 2; $i <= sizeof($location)-1; $i++) {
    $url[] = $location[$i];
    $this->Bootstrap->add_crumb($location[$i], $url);
}
$url['action'] = 'raw';

foreach ($branches as $a => $branch) {
    $branches[$a] = $this->Html->link($branch, array('project' => $project['Project']['name'], 'action' => 'tree', $branch));
}

// Header for the page
echo $this->Bootstrap->page_header($pname . $smallText);

?>
<div class="row">
    <div class="span2">
        <?= $this->element('project_sidebar', array('project' => $pname, 'action' => 'collaborators')) ?>
    </div>
    <div class="row">
        <div class="span7">
            <?= $this->Bootstrap->breadcrumbs(array("divider" => "/")) ?>
        </div>
        <div class="span1">
            <?= $this->Html->link("raw", $url, array("class" => "btn btn-default raw-button", "size" => "small")) ?>
        </div>
        <div class="span2">
            <?= $this->Bootstrap->button_dropdown($this->Bootstrap->icon('random')." <strong>Branch: </strong>".$location[1], array("class" => "span2", "links" => $branches)) ?>
        </div>
        <div class="span10">
            <?= $this->Geshi->highlight('<pre lang="php">'.htmlentities($source_files).'</pre>') ?>
        </div>
    </div>
</div>
