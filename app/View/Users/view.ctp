<?= $this->Bootstrap->page_header(h($user['User']['name'])); ?>
<?= $this->Html->css('projects.index', null, array ('inline' => false)); ?>

<div class="row">
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
			<?= $this->Time->timeAgoInWords($user['User']['created'], 'Y-m-d') ?>
		</dd>
		<dt>
			<?= __("Member of teams") ?>
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

<div class="row">';
	<h4 class='span12'><?=__("Projects shared with this user")?></h4>
	<?foreach ($shared_projects as $project) {
		echo $this->Element('Project/block', array('project' => $project));
	}?>
</div>
<? } ?>

<hr>
<div class="row">
	<?php
	// Loop through all the projects that a user has access to
	if (empty($projects)) {
		echo "<h4 class='span12'>This user has no public projects</h4>";
	} else {
		echo "<h4 class='span12'>Users public projects</h4>";
		foreach ($projects as $project){
			echo $this->Element('Project/block', array('project' => $project));
		}

	} ?>
</div>


