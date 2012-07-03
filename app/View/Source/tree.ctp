<?php
/**
 *
 * View class for APP/Source/tree for the DevTrack system
 * Allows users to view tree objects
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
$this->set('css_for_layout', array('pages/source'));

$smallText = " <small>" . $project['Project']['description'] . " </small>";
$pname = $project['Project']['name'];

// Base url for the view
$url = array('project' => $project['Project']['name'], 'action' => 'tree', $branch);
$this->Bootstrap->add_crumb($project['Project']['name'], $url);

// Create the base url to be used for all links and add breadcrumbs
foreach (explode('/',$path) as $crumb) {
    $url[] = $crumb;
    $this->Bootstrap->add_crumb($crumb, $url);
}

foreach ($branches as $a => $b) {
    $branches[$a] = $this->Html->link($b, array('project' => $project['Project']['name'], 'action' => 'tree', $b));
}

// Header for the page
echo $this->Bootstrap->page_header($pname . $smallText);

?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <?= $this->element('project_topbar') ?>
        <? if (isset($files)) : ?> 
            <?= $this->element('source/folder_view', array('url' => $url, 'branches' => $branches, 'branch' => $branch, 'files' => $files)) ?>
        <? else : ?>
            <?= $this->element('source/file_view', array('url' => $url, 'branches' => $branches, 'branch' => $branch, 'source' => $source)) ?>
        <? endif; ?>
    </div>
</div>
