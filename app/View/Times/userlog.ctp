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
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="row-fluid">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
	<div class="span10">
		<div class="well">
		  <ul>
		    <? foreach ($times as $time) { ?>
				<li>
					<?
					echo $this->Html->link(
						$time['User']['name'], array(
						'controller' => 'users', 'action' => 'view', $time['User']['id']
						));
					echo " ";
					echo __("has logged");
					echo " ";
					echo TimeString::renderTime($time[0]['total_mins'], 's');
					echo " ";
		
					if ($time['Task']['id'] != 0) {
						echo __("on task #%d", $time['Task']['public_id']);
						echo " ";
						echo $this->Html->link(
							$time['Task']['subject'], array(
							'controller' => 'tasks', 'action' => 'view', 'project' => $project['Project']['name'], $time['Task']['public_id']
							));
					} else {
						echo __("to no specific task");
					}?>
				</li>
			<? } ?>
		  </ul>
		</div>
	</div>
</div>
