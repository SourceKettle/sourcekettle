<?php
/**
 *
 * View class for APP/project_groups/admin_view for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2014
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Teams
 * @since         SourceKettle v 1.5
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header('Administration <small>organise your hackers</small>'); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/admin') ?>
    </div>
    <div class="span10">
        <div class="row-fluid">


<div class="projectGroups view">
<h2><?php  echo __('Project group: %s', h($projectGroup['ProjectGroup']['name'])); ?></h2>
<p>
	<small><?= h($projectGroup['ProjectGroup']['description']) ?></small>
</p>

<div>
  <h4><?=__('Member projects')?></h4>
  <ul>
    <? foreach($projectGroup['Project'] as $member) {?>
	  <li><?=$this->Html->link(h($member['name']), array('controller' => 'projects', 'action' => 'view', 'project' => $member['name'], 'admin' => false))?></li>
	<? } ?>
  </ul>
</div>

<div>
  <h4><?=__('Collaborating Teams')?></h4>
  <ul>
    <? foreach($projectGroup['GroupCollaboratingTeam'] as $collab) {?>
	  <li><?=$this->Html->link(h($collab['name']), array('controller' => 'teams', 'action' => 'view', 'id' => $collab['team_id']))?></li>
	<? } ?>
  </ul>
</div>

<div class="related">
	<h3><?php echo __('Collaborating Teams'); ?></h3>
	<?php if (!empty($projectGroup['GroupCollaboratingTeam'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Project Group Id'); ?></th>
		<th><?php echo __('Team Id'); ?></th>
		<th><?php echo __('Access Level'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($projectGroup['GroupCollaboratingTeam'] as $groupCollaboratingTeam): ?>
		<tr>
			<td><?php echo $groupCollaboratingTeam['id']; ?></td>
			<td><?php echo $groupCollaboratingTeam['project_group_id']; ?></td>
			<td><?php echo $groupCollaboratingTeam['team_id']; ?></td>
			<td><?php echo $groupCollaboratingTeam['access_level']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'group_collaborating_teams', 'action' => 'view', $groupCollaboratingTeam['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'group_collaborating_teams', 'action' => 'edit', $groupCollaboratingTeam['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'group_collaborating_teams', 'action' => 'delete', $groupCollaboratingTeam['id']), null, __('Are you sure you want to delete # %s?', $groupCollaboratingTeam['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Group Collaborating Team'), array('controller' => 'group_collaborating_teams', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>

		</div>
	</div>
</div>
