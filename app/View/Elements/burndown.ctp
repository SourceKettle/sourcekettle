<?php
$this->Html->script('jquery.flot.min', array('inline' => false));
$this->Html->script('jquery.flot.categories.min', array('inline' => false));
$this->Html->script('jquery.flot.stack.min', array('inline' => false));
$this->Html->script ("burndown", array ('inline' => false));
?>

<div class="row-fluid burndown-outer">
<div class="span11"><div class="burndown-chart">
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
		<tbody>
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
		</tbody>
	</table>
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
	<label for="show_finished"><?=__('Show finished tasks')?></label>
</div>
</div></div>
