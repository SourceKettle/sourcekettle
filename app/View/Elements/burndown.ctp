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
		<? foreach($log['days'] as $day) { ?>
			<tr>
				<td><?=h($day)?></td>
				<td><?=h($log['tasks']['open'][$day])?></td>
				<td><?=h($log['tasks']['closed'][$day])?></td>
				<td><?=h($log['points']['open'][$day])?></td>
				<td><?=h($log['points']['closed'][$day])?></td>
				<td><?=h($log['minutes']['open'][$day])?></td>
				<td><?=h($log['minutes']['closed'][$day])?></td>
			</tr>
		<? } ?>
		</tbody>
	</table>

	<ul>
	  <li class="high-tasks"><?=__("Max open tasks:")?><strong><?=h($log['highs']['tasks'])?></strong></li>
	  <li class="high-points"><?=__("Max open story points:")?><strong><?=h($log['highs']['points'])?></strong></li>
	  <li class="high-time"><?=__("Max open time estimate:")?><strong><?=h($log['highs']['minutes'])?></strong></li>
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
	<label for="show_finished"><?=__('Show finished tasks')?></label>
</div>

</div></div>
