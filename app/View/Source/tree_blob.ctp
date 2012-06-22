<?php
/**
 *
 * View class for APP/collaborators/index for the DevTrack system
 * Allows modification of collaborators
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Collaborators
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$smallText = " <small>" . $project['Project']['description'] . " </small>";
$pname = $project['Project']['name'];

// Base url for the view
$url = array('project' => $project['Project']['name'], 'action' => 'index');
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
        </div>
        <div class="span10">
            <div class="">
                <?= $this->Geshi->highlight('<pre lang="php">'.htmlentities($source_files).'</pre>') ?>
            </div>
        </div>
    </div>
</div>
