<div class="projects view">
<h2><?php  echo __('Project');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($project['Project']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($project['Project']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($project['Project']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Public'); ?></dt>
		<dd>
			<?php echo h($project['Project']['public']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Repo Type'); ?></dt>
		<dd>
			<?php echo $this->Html->link($project['RepoType']['name'], array('controller' => 'repo_types', 'action' => 'view', $project['RepoType']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Wiki Enabled'); ?></dt>
		<dd>
			<?php echo h($project['Project']['wiki_enabled']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Task Tracking Enabled'); ?></dt>
		<dd>
			<?php echo h($project['Project']['task_tracking_enabled']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Time Management Enabled'); ?></dt>
		<dd>
			<?php echo h($project['Project']['time_management_enabled']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($project['Project']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($project['Project']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Project'), array('action' => 'edit', $project['Project']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Project'), array('action' => 'delete', $project['Project']['id']), null, __('Are you sure you want to delete # %s?', $project['Project']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Projects'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Project'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Repo Types'), array('controller' => 'repo_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Repo Type'), array('controller' => 'repo_types', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Collaborators'), array('controller' => 'collaborators', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Collaborator'), array('controller' => 'collaborators', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Collaborators');?></h3>
	<?php if (!empty($project['Collaborator'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Project Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Access Level'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($project['Collaborator'] as $collaborator): ?>
		<tr>
			<td><?php echo $collaborator['id'];?></td>
			<td><?php echo $collaborator['project_id'];?></td>
			<td><?php echo $collaborator['user_id'];?></td>
			<td><?php echo $collaborator['access_level'];?></td>
			<td><?php echo $collaborator['created'];?></td>
			<td><?php echo $collaborator['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'collaborators', 'action' => 'view', $collaborator['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'collaborators', 'action' => 'edit', $collaborator['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'collaborators', 'action' => 'delete', $collaborator['id']), null, __('Are you sure you want to delete # %s?', $collaborator['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Collaborator'), array('controller' => 'collaborators', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
