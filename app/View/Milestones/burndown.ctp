<?php
/**
 *
 * View class for APP/tasks/burndown for the SourceKettle system
 * Shows a burndown chart for a milestone
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2014
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Milestones
 * @since         SourceKettle v 1.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('tasks.index', null, array ('inline' => false));
$this->Html->css('milestones.index', null, array ('inline' => false));
$this->Html->script ("jquery-ui.min", array ('inline' => false));
$this->Html->script ("jquery.ui.touch-punch.min", array ('inline' => false));
$this->Html->script('jquery.flot.min', array('inline' => false));
$this->Html->script('jquery.flot.categories.min', array('inline' => false));
$this->Html->script('jquery.flot.stack.min', array('inline' => false));
$this->Html->script ("burndown", array ('inline' => false));
?>
<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
    <?= $this->element('Milestone/topbar', array('name' => $milestone['Milestone']['subject'], 'id' => $milestone['Milestone']['id'])) ?>

            <div class="span10">
                <div class="row-fluid">
				<div class="span12"><div class="burndown">
					<!-- Note that this table will be replaced with a graph, if JavaScript is working properly! -->
					<table>
						<thead>
						  <tr>
						  	<th>Timestamp</th>
						 	<th>Open tasks</th>
						 	<th>Closed tasks</th>
						 	<th>Open story points</th>
						 	<th>Closed story points</th>
						 	<th>Open time, estimated</th>
						 	<th>Closed time, estimated</th>
						  </tr>
						</thead>
						<? foreach($log as $event) {?>
							<tr>
								<td><?=$event['timestamp']?></td>
								<td><?=$event['open_task_count']?></td>
								<td><?=$event['closed_task_count']?></td>
								<td><?=$event['open_points_count']?></td>
								<td><?=$event['closed_points_count']?></td>
								<td><?=$event['open_minutes_count']?></td>
								<td><?=$event['closed_minutes_count']?></td>
							</tr>
						<? } ?>
					</table>
				</div>
				</div></div>

            </div>
        </div>
    </div>
</div>

