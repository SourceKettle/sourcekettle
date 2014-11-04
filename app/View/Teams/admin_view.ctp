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

echo $this->Bootstrap->page_header('Administration <small>organise your hackers</small>'); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/admin') ?>
    </div>
    <div class="span10">
        <div class="row-fluid">
<div class="teams view">
<h2><?php  echo __('Team: %s', h($team['Team']['name'])); ?></h2>
<p>
	<small><?= h($team['Team']['description']) ?></small>
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
	  <li><?=$this->Html->link(h($collab['name']), array('controller' => 'projects', 'action' => 'view', $collab['name']))?> <?__('(Level: %s)', 'admin')?></li>
	<? } ?>
  </ul>
</div>

<div>
  <h4><?=__('Collaborating on project groups')?></h4>
  <ul>
    <? foreach($team['GroupCollaboratingTeam'] as $collab) {?>
	  <li><?=$this->Html->link(h($collab['name']), array('controller' => 'projects', 'action' => 'view', $collab['name']))?> <?__('(Level: %s)', 'admin')?></li>
	<? } ?>
  </ul>
</div>

		</div>
	</div>
</div>
