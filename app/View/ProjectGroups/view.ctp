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
?>

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
	  <li><?=$this->Html->link(h($collab['team_name']), array('controller' => 'teams', 'action' => 'view', 'team' => $collab['team_name']))?> <?=__('Level: %s', $collab['access_level'])?></li>
	<? } ?>
  </ul>
</div>

