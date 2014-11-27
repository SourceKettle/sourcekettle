<?php
/**
 *
 * View class for APP/projects/index for the SourceKettle system
 * View will render a list of all the projects a user has access to
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Projects
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header(__("Team Projects <small>we do what we must because we can</small>")); 
echo $this->Html->css('projects.index', null, array ('inline' => false));
?>

<div class="row">
    <?= $this->Element("Project/topbar") ?>

    <? if (!empty($projects)){
    	foreach ($projects as $project){
			echo $this->Element('Project/block', array('project' => $project));
		}
      } else {
        echo $this->element('Project/noprojectsalert', array('span' => true));
      }?>
</div>
