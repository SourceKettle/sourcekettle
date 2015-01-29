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

echo $this->Bootstrap->page_header($title);
echo $this->Html->css('projects.index', null, array ('inline' => false));
?>

<div class="row-fluid">
    <?= $this->Element("Project/topbar") ?>
</div>

<div class="row-fluid">
    <? if (!empty($projects)){
		$count = 0;
		echo "<div class=\"row-fluid\">\n";
    	foreach ($projects as $project){
			// TODO spurious extra row but meh, don't think I care
			if ($count % 3 == 0) {
				echo "</div>";
				echo "<div class=\"row-fluid\">\n";
			}
			echo $this->Element('Project/block', array('project' => $project));
			$count++;
		}
		echo "</div>\n";
      } else {
        echo $this->element('Project/noprojectsalert', array('span' => true));
      }?>
</div>
