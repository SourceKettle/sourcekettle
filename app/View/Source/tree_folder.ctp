<?php
/**
 *
 * View class for APP/Source/tree for the DevTrack system
 * Allows users to view tree for a commit
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
$url = array('project' => $project['Project']['name'], 'action' => 'tree');
$this->Bootstrap->add_crumb($project['Project']['name'], $url);

// Create the base url to be used for all links and add breadcrumbs
for ($i = 1; $i <= sizeof($location)-1; $i++) {
    $url[] = $location[$i];
    $this->Bootstrap->add_crumb($location[$i], $url);
}
// Cheat to get file pointer working later on
$url[] = 'file.php';

// Header for the page
echo $this->Bootstrap->page_header($pname . $smallText);

?>
<div class="row">
    <div class="span2">
        <?= $this->element('project_sidebar', array('project' => $pname, 'action' => 'collaborators')) ?>
    </div>
    <div class="row">
        <div class="span10">
            <?= $this->Bootstrap->breadcrumbs(array("divider" => "/")) ?>
            <table class="well table table-striped">
            <? foreach ($source_files as $source_file) : ?>
                <tr>
                    <td>
                    <?php
                    $icon = 'warning-sign';
                    if ($source_file['type'] == 'blob') $icon = 'file';
                    if ($source_file['type'] == 'tree') $icon = 'folder-open';
                    echo $this->Bootstrap->icon($icon).' ';
                    $url[sizeof($location)-1] = $source_file['name'];
                    echo $this->Html->link(
                        $source_file['name'], 
                        $url, 
                        array('escape' => false)
                    ); 
                    ?></td>
                </tr>
            <? endforeach; ?>
            </table>
        </div>
    </div>
</div>
