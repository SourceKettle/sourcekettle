<?php
/**
 *
 * View class for APP/Source/gettingStarted for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link	  http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Source
 * @since	 SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

?>
<div class="row-fluid">

	<div class="hero-unit span10 offset1">
	  <h1>Nothing to see here</h1>
	  <p>Unfortunately this project was created repository-less and as such, there is no source to view.</p>
	  <? if ($isAdmin) {?>
	  <p>If you would like this to change, you can <?=$this->Html->link('add a repository here', array('controller' => 'projects', 'action' => 'add_repo', 'project' => $project['Project']['name']))?>.</p>
	  <? } else { ?>
	  <p>If you would like this to change, please contact an Administrator for this project.</p>
	  <? } ?>
	</div>
</div>
