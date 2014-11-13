<?php
/**
 *
 * View class for APP/teams/admin_add for the SourceKettle system
 * View allow admin to create a new team
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

echo $this->Bootstrap->page_header(__('Team: <small>%s</small>', h($team['Team']['name']))); ?>

<div class="row-fluid">
<div class="teams view">
<p>
	<?= h($team['Team']['description']) ?>
</p>

<p>
	<?= $this->Html->link(
		__('View kanban chart'),
		array(
			'controller' => 'tasks',
			'action' => 'team_kanban',
			'team' => $team['Team']['name']
		)
	)?>
</p>

<div>
  <h4><?=__('Members')?></h4>
  <ul>
    <? foreach($team['User'] as $member) {?>
	  <li><?=$this->Html->link(h($member['name']), array('controller' => 'users', 'action' => 'view', $member['id']))?></li>
	<? } ?>
  </ul>
</div>

<div>
  <h4><?=__('Collaborating on projects')?></h4>
  <ul>
    <? foreach($team['CollaboratingTeam'] as $collab) {?>
	  <li><?=$this->Html->link(h($collab['project_name']), array('controller' => 'projects', 'action' => 'view', 'project' => $collab['project_name']))?> <?=__('(Level: %s)', $collab['access_level'])?></li>
	<? } ?>
  </ul>
</div>

<div>
  <h4><?=__('Collaborating on project groups')?></h4>
  <ul>
    <? foreach($team['GroupCollaboratingTeam'] as $collab) {?>
	  <li><?=$this->Html->link(h($collab['project_group_name']), array('controller' => 'project_groups', 'action' => 'view', $collab['project_group_name']))?> <?=__('(Level: %s)', $collab['access_level'])?></li>
	<? } ?>
  </ul>
</div>

