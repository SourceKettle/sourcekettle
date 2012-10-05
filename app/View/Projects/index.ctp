<?php
/**
 *
 * View class for APP/projects/index for the DevTrack system
 * View will render a list of all the projects a user has access to
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Projects
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header("My Projects <small>all the projects you care about</small>"); ?>

<div class="row">
    <?= $this->Element("Project/topbar") ?>
    <?= $this->Element("Project/list", array('projects' => $projects)) ?>
</div>
