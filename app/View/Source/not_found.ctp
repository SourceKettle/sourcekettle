<?php
/**
 *
 * View class for APP/Source/tree for the DevTrack system
 * Shows an error when an invalid node is viewed
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

$smallText = " <small>source code</small>";
$pname = $project['Project']['name'];

// Base url for the view
$url = array('project' => $project['Project']['name'], 'action' => 'tree');
$this->Bootstrap->add_crumb($project['Project']['name'], $url);
$this->Bootstrap->add_crumb("Aw fiddlesticks...", $url);

// Header for the page
echo $this->Bootstrap->page_header($pname . $smallText);

?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <?= $this->element('Source/topbar', array('branches' => $branches, 'branch' => $branch)) ?>
        <div class="span10">
            <?= $this->Bootstrap->breadcrumbs(array("divider" => "/")) ?>
        </div>
        <div class="span10">
            <div class="well">
                <h2>Darn!</h2>
                <h3>This is not the location you are looking for...</h3>
                <h4>Whatever you've requested has gone and caused a pesky error in the system.</h4>
                <p>Dont worry! We're making sure it wasnt us by realigning our flux capacitors and what-not.</p>
            </div>
        </div>
    </div>
</div>
