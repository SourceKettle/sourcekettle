<?= $this->Html->css('projects.index', null, array ('inline' => false)); ?>

<div class="row-fluid">
	<div class="span3">
		<?= $this->Gravatar->image(
			$user['User']['email'],
			array('size' => 200),
			array('alt' => $user['User']['name'])
		) ?>
	</div>

	<dl class="dl-horizontal span9">
		<dt>
			<?=__('Email address')?>
		</dt>
		<dd>
			<?= h($user['User']['email']) ?>
		</dd>
		<dt>
			<?=__('User registered')?>
		</dt>
		<dd>
			<?= $this->Time->timeAgoInWords($user['User']['created'])?>
		</dd>
		<? if ($user['User']['id'] == $current_user['id']) { ?>
			<dd><?= $this->Html->link(__('View kanban chart'), array('controller' => 'tasks', 'action' => 'personal_kanban'))?></dd>
		<? } ?>
		<dt>
			<?= __("Teams") ?>
		</dt>
		<dd>
			<ul>
			<? foreach ($user['Team'] as $team) {?>
				<li><?= $this->Html->link($team['name'], array('controller' => 'teams', 'action' => 'view', 'team' => $team['name'])) ?></li>
			<? } ?>
			</ul>
		</dd>
	</dl>

</div>


<? if (!empty($shared_projects)) { ?>
<hr>

<div class="row-fluid">
	<h4 class='span12'>
		<?=__("%s collaborates on these projects:", h($user['User']['name']))?>
	</h4>
</div>
<? $projects = $shared_projects; ?>
<?= $this->Element("Project/projectgrid") ?>
<? } ?>


<hr>
<? $projects = $shared_projects; ?>
<?= $this->Element("Project/projectgrid") ?>
<div class="row-fluid">
	<?php
	// Loop through all the projects that a user has access to
	if (empty($projects)) {
		echo "<h4 class='span12'>";
		echo __("%s has no public projects", h($user['User']['name']));
		echo "</h4>";
	} else {
		echo "<h4 class='span12'>";
		echo __("%s's public projects", h($user['User']['name']));
		echo "</h4>";
		foreach ($projects as $project){
			echo $this->Element('Project/block', array('project' => $project));
		}

	} ?>
</div>


