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

$this->Html->css('pages/source', null, array ('inline' => false));
$this->Html->css('prettify/prettify', null, array ('inline' => false));
$this->Html->script('prettify/prettify', array('block' => 'scriptBottom'));
$this->Html->scriptBlock("prettyPrint()", array('inline' => false));

// Base url for the view
$url = array('project' => $project['Project']['name'], 'action' => 'tree', $branch);
$this->Bootstrap->add_crumb($project['Project']['name'], $url);

// Create the base url to be used for all links and add breadcrumbs
foreach (explode('/',$path) as $crumb) {
    $url[] = $crumb;
    $this->Bootstrap->add_crumb($crumb, $url);
}
?>
<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <?= $this->element('Source/topbar', array('branches' => $branches, 'branch' => $branch)) ?>
        <div class="span10">
            <?= $this->Bootstrap->breadcrumbs(array("divider" => "/")) ?>
        </div>
        <?php
            if ($tree['type'] == 'tree') {
                if ($tree['path'] == '.') {
                    echo $this->element('Source/tree_commit_header', array('commit' => $tree['commit']));
                }
                echo $this->element('Source/tree_folder_content', array('files' => $tree['content']));
            } else if ($tree['type'] == 'blob') {
                echo $this->element('Source/tree_commit_header', array('commit' => $tree['updated']));
                echo $this->element('Source/tree_blob_content');
            }
        ?>
    </div>
</div>
