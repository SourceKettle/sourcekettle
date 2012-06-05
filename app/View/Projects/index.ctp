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

echo $this->Bootstrap->page_header("Projects <small>all the projects you care about</small>" .
	$this->Bootstrap->button_link("New Project", array('action' => 'add'), array("style" => "primary", "size" => "medium", "class" => "pull-right"))); ?>

<div class="row">
	<?php // Loop through all the projects that a user has access to
	foreach ($projects as $project): ?>
		<div class="span4">
			<div class="well project-well">
				<h3 class="project-title"><?=$this->Html->link($project['Project']['name'], array('action' => '.', 'project' => $project['Project']['name']), array('class' => 'project-link'))?>
				<span style="float: right;"><?= $this->Bootstrap->icon((($project['Project']['public']) ? 'globe' : 'lock'), 'black') ?></span></h3>
				<p class="project-desc"><?=$project['Project']['description']?></p>
				<p class="project-time">Last Modified: <?=$this->Time->timeAgoInWords($project['Project']['modified'])?></p>
			</div>
		</div>
	<?php endforeach; ?>
</div>
