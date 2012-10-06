<?php
/**
 *
 * View class for APP/projects/public_projects for the DevTrack system
 * View will render a list of all the public projects
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

echo $this->Bootstrap->page_header("Public Projects <small>projects people have shared</small>"); ?>

<div class="row">
  <?= $this->Element("Project/topbar") ?>
</div>
<div class='row'>  
  <? if (!empty($projects)){
        echo $this->Element("Project/list", array('projects' => $projects));
      } else {
        $config = Configure::read('dtcore'); 
        echo "<div class='well span4'>" . $config['pages']['projects']['all']['en']['noprojects.text'] . "</div>";
      }?>
</div>
<div class='row'>
  <?=$this->element('pagination') ?>
</div>
