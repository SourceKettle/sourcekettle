<?php
$this->Html->script('jquery.flot.min', array('inline' => false));
$this->Html->script('jquery.flot.time.min', array('inline' => false));
$this->Html->script('jquery.flot.categories.min', array('inline' => false));
$this->Html->script('JUMFlot.min', array('inline' => false));
$this->Html->script ("gantt", array ('inline' => false));
?>

<div class="row-fluid gantt-outer">
<div class="span12"><div class="gantt-chart">
	<!-- Note that this table will be replaced with a graph, if JavaScript is working properly! -->
	<table>
		<thead>
		  <tr>
		  	<th><?=__("Milestone")?></th>
		  	<th><?=__("Is open?")?></th>
		 	<th><?=__("Starts")?></th>
		 	<th><?=__("Due")?></th>
		 	<th><?=__("Open tasks")?></th>
		 	<th><?=__("Closed tasks")?></th>
		 	<th><?=__("Open story points")?></th>
		 	<th><?=__("Closed story points")?></th>
		  </tr>
		</thead>
		<tbody>
		<?
		foreach($milestones as $milestone) {
			$open_tasks = (
				$milestone['Tasks']['open']['numTasks'] +
				$milestone['Tasks']['in progress']['numTasks']
			);

			$closed_tasks = (
				$milestone['Tasks']['resolved']['numTasks'] + 
				$milestone['Tasks']['closed']['numTasks']
			);

			$open_points = (
				$milestone['Tasks']['open']['points'] +
				$milestone['Tasks']['in progress']['points']
			);

			$closed_points = (
				$milestone['Tasks']['resolved']['points'] + 
				$milestone['Tasks']['closed']['points']
			);

			$milestone = $milestone['Milestone'];
		?>
			<tr>
				<td><?=h($milestone['subject'])?></td>
				<td><?=h($milestone['is_open']? 'true':'false')?></td>
				<td><?=h($milestone['starts'])?></td>
				<td><?=h($milestone['due'])?></td>
				<td><?=h($open_tasks)?></td>
				<td><?=h($closed_tasks)?></td>
				<td><?=h($open_points)?></td>
				<td><?=h($closed_points)?></td>
			</tr>
		<? } ?>
		</tbody>
	</table>
</div>
</div>

</div></div>
