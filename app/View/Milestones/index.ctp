<?php
/**
 *
 * View class for APP/milestones/index for the DevTrack system
 * Shows a list of milestones for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Milestones
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header("Milestones for the Project <small>I am serious and don't call me shirley!</small>");?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <div class="span10">
            <div class='hero-unit'>
                <h1>There be nothing here yet</h1>
                <p>
                   Oops :/
                </p>
            </div>
        </div>
    </div>
</div>
