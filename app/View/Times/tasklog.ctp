<?php
/**
 * Displays a log of which tasks a user has spent time on.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Times
 * @since         SourceKettle v 1.5
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<? $taskLink = $this->Html->link(__("#%s: %s", $task['Task']['public_id'], $task['Task']['subject']), array(
	'controller' => 'tasks',
	'action' => 'view',
	'project' => $task['Project']['name'],
	$task['Task']['public_id']
))?>
<div class="row-fluid">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
	<div class="span10">
		<div class="well">
		<h3><?=__("Time logged for task %s", $taskLink)?></h3>
		  <ul>
		    <? foreach ($times as $time) {
			$timeLink = $this->Html->link($time['Time']['minutes']['s'], array(
				'controller' => 'times',
				'action' => 'view',
				'project' => $time['Project']['name'],
				$time['Time']['id']
			));
			$userLink = $this->Html->link($time['User']['name'], array(
				'controller' => 'users',
				'action' => 'view',
				$time['User']['id']
			));
			?>
				<li>
				<?=__("%s logged %s on %s (\"%s\")", $userLink, $timeLink, $time['Time']['date'], $time['Time']['description'])?>
				</li>
			<? } ?>
		  </ul>
		</div>
	</div>
</div>
