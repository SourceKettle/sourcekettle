<?php
/**
 *
 * View class for APP/Source/gettingStarted for the DevTrack system
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

                <div class="hero-unit span9">
                  <h1>Nothing to see here</h1>
                  <p>Unfortunately this project was created repository-less and as such, there is no soure to view.</p>
                  <p>If you would like this to change, please contact an Administrator for this project.</p>
                </div>
        </div>
    </div>
</div>
