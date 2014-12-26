<?php
$this->Html->script('/flot/jquery.flot.min', array('inline' => false));
$this->Html->script('/flot/jquery.flot.categories.min', array('inline' => false));
$this->Html->script('/flot/jquery.flot.stack.min', array('inline' => false));
$this->Html->script ("burndown", array ('inline' => false));
?>

<div class="row-fluid burndown-outer">
<div class="span11"><div class="burndown-chart">
	<!-- Note that this table will be replaced with a graph, if JavaScript is working properly! -->
	<table>
		<thead>
		  <tr>
		  	<th><?=__("Date")?></th>
		 	<th><?=__("Open tasks")?></th>
		 	<th><?=__("Closed tasks")?></th>
		 	<th><?=__("Open story points")?></th>
		 	<th><?=__("Closed story points")?></th>
		 	<th><?=__("Open time, estimated")?></th>
		 	<th><?=__("Closed time, estimated")?></th>
		  </tr>
		</thead>
		<tbody>
		<?
		$start_tasks = $start_points = $start_minutes = 0;
		foreach($log as $day => $entry) {
			$start_tasks = $start_tasks ?: $entry['open']['tasks'];
			$start_points = $start_points ?: $entry['open']['points'];
			$start_minutes = $start_minutes ?: $entry['open']['minutes'];
		?>
			<tr>
				<td><?=h($day)?></td>
				<td><?=h($entry['open']['tasks'])?></td>
				<td><?=h($entry['closed']['tasks'])?></td>
				<td><?=h($entry['open']['points'])?></td>
				<td><?=h($entry['closed']['points'])?></td>
				<td><?=h($entry['open']['minutes'])?></td>
				<td><?=h($entry['closed']['minutes'])?></td>
			</tr>
		<? } ?>
		</tbody>
	</table>
	<ul>
	  <li class="start-date"><?=__("Start date:")?> <strong><?=h($milestone['Milestone']['starts'])?></strong></li>
	  <li class="due-date"><?=__("Due date:")?> <strong><?=h($milestone['Milestone']['due'])?></strong></li>
	  <li class="start-tasks"><?=__("Starting open tasks:")?> <strong><?=h($start_tasks)?></strong></li>
	  <li class="start-points"><?=__("Starting open story points:")?> <strong><?=h($start_points)?></strong></li>
	  <li class="start-time"><?=__("Starting open time estimate:")?> <strong><?=h($start_minutes)?></strong></li>
	</ul>
</div>
</div>
<div class="span1">
<div class="burndown-controls">
	<input type="radio" name="series" value="points" checked="checked" id="points"></input>
	<label for="points"><?=__('Show story points')?></label>
	<input type="radio" name="series" value="tasks" id="tasks"></input>
	<label for="tasks"><?=__('Show number of tasks')?></label>
	<input type="radio" name="series" value="hours" id="hours"></input>
	<label for="hours"><?=__('Show estimated time (hours)')?></label>
	<input type="checkbox" name="show_finished" id="show_finished"></input>
	<label for="show_finished"><?=__('Include finished tasks')?></label>
</div>

</div></div>
