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

echo $this->Bootstrap->page_header("Projects <small>all the projects you care about</small>"); ?>

<div class="row">
	<div class="span8">
		<?php // Loop through all the projects that a user has access to
		foreach ($projects as $project): ?>
		<div class="well">
			<h3><?=$this->Html->link($project['Project']['name'], array('action' => 'view', $project['Project']['id']))?></h3>
			<p><i class="icon-<?=($project['Project']['public']) ? 'globe' : 'lock' ?>"></i>
			<?=$project['Project']['description']?></p>
			<p>Last Modified: <?=$this->Time->timeAgoInWords($project['Project']['modified'])?></p>
			<p><strong><?=$project['RepoType']['name']?></strong></p>
		</div>
		<?php endforeach; ?>
		<?= $this->element('pagination') ?>
	</div>
	
	<div class="span4">	
		<div class="actions">
			<h3><?php echo __('Actions'); ?></h3>
			<ul>
				<li><?php echo $this->Html->link(__('New Project'), array('action' => 'add')); ?></li>
				<li><?php echo $this->Html->link(__('List Repo Types'), array('controller' => 'repo_types', 'action' => 'index')); ?> </li>
				<li><?php echo $this->Html->link(__('New Repo Type'), array('controller' => 'repo_types', 'action' => 'add')); ?> </li>
				<li><?php echo $this->Html->link(__('List Collaborators'), array('controller' => 'collaborators', 'action' => 'index')); ?> </li>
				<li><?php echo $this->Html->link(__('New Collaborator'), array('controller' => 'collaborators', 'action' => 'add')); ?> </li>
			</ul>
		</div>
	</div>
</div>

