<?php
/**
 *
 * Tempo display for APP/times/history for the SourceKettle system
 * Shows a table of time vs. tasks
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Time
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */


$this->Html->css('time.tempo', null, array ('inline' => false));
$this->Html->css('/datatables/jquery.datatables-1.9.4.css', null, array ('inline' => false));
$this->Html->script('/datatables/jquery.datatables-1.9.4.min.js', array ('inline' => false));

echo $this->element('Time/modal_add');

?>
<div>
    <table id="timesheet" class="well table table-condensed table-striped tempo">
        <thead>
            <tr>
                <th><?= __('Task') ?></th>
                <th><?= __('User') ?></th>
				<? foreach ($weekTimes['dates'] as $daynum => $date){ ?>
				<th><?= __($date->format('D M d')) ?></th>
				<? } ?>
				<th>Total</th>
            </tr>
        </thead>
        <tbody>
        <?php
			// Sorry :-(

			$overallTotal = 0;
			$elements = '';
			foreach ($weekTimes['tasks'] as $taskId => $taskDetails) {
				foreach ($taskDetails['users'] as $userId => $userDetails) {
					echo "<tr>\n";
					if ($taskId == 0) {
						echo "<td>(".__("No associated task").")</td>\n";

					} else {
						echo "<td>";
						echo $this->Html->link($taskDetails['Task']['subject'], array(
                           'controller' => 'tasks',
	                       'action' => 'view',
    	                   'project' => $project['Project']['name'],
						   $taskDetails['Task']['public_id']
                        ));
						echo "</td>\n";
					}


					echo "<td>";
					echo $this->Html->link($userDetails['User']['name'], array(
   	                	'project' => $project['Project']['name'],
						'controller' => 'times',
						'action' => 'history',
						$thisYear,
						$thisWeek,
						'?' => array(
							'user' => $userId
						)
					));
					echo "</td>\n";

					// Loop over the days of the week
					// 1=Mon, 7=Sun...
					$rowTotal = 0;
					for ($i = 1; $i <= 7; $i++) {

						// The date for this day, and day name for display
						$date = $weekTimes['dates'][$i]->format('Y-m-d');
						$day = $weekTimes['dates'][$i]->format('D');

						// Popup for viewing times
		                $popover = "tempo_{$day}_{$taskId}_{$userId}";

						// times_by_day is a list of Time... cake-y object-like array things
						if (array_key_exists($i, $userDetails['times_by_day'])) {
							$timeSpent = 0;
							foreach ($userDetails['times_by_day'][$i] as $time){
								$rowTotal += $time['Time']['mins'];
								$timeSpent += $time['Time']['mins'];

							}
  	                    	$elements .= $this->element('Time/tempo_modal',
                        		array(
                            		'id' => $popover,
                             		'times' => $userDetails['times_by_day'][$i],
                        	    	'date'  => $weekTimes['dates'][$i]
                        		)
                        	);
							$timeSpent = TimeString::renderTime($timeSpent);
							echo "<td class=\"tempoBody\" data-toggle=\"$popover\" data-taskid=\"$taskId\" data-date=\"$date\">".h($timeSpent['s'])."</td>\n";
						} else {
							echo "<td class=\"tempoBody\" data-toggle=\"$popover\" data-taskid=\"$taskId\" data-date=\"$date\">---</td>\n";
						}
					}
					$overallTotal += $rowTotal;
					$timeSpent = TimeString::renderTime($rowTotal);
					echo "<th>".h($timeSpent['s'])."</th>\n";

					echo "</tr>\n";

				}
			}?>
        </tbody>
        <tfoot>

            <tr>
                <th><?= __('Total') ?></th>
				<th></th>
				<? for ($i = 1; $i <= 7; $i++) {
					if (array_key_exists($i, $weekTimes['totals'])) {
						$total = TimeString::renderTime($weekTimes['totals'][$i]);

						echo "<th>".$total['s']."</th>\n";
					} else {
                		echo "<th>---</th>\n";
					}

				} 
				$overallTotal = TimeString::renderTime($overallTotal);
				echo "<th>".h($overallTotal['s'])."</th>\n";
				?>
            </tr>
        </tfoot>
    </table>

	<?=$elements // Add in elements for each of our drill-down displays?>

    <div class="btn-toolbar tempo-toolbar span12">
        <div class="btn-group">
            <?php
			if (isset($user)) {
				$params = array('user' => $user);
			} else {
				$params = array();
			}
            echo $this->Bootstrap->button_link(
                $this->Bootstrap->icon('chevron-left'),
                array(
                    'project' => $project['Project']['name'],
                    'action' => $this->request['action'],
                    'year' => $prevYear,
                    'week' => $prevWeek,
					'?' => $params
                ),
                array('escape'=>false, 'size'=>'small')
            );

            echo "<button class='btn disabled btn-small'>";
			echo $startDate->format('Y-m-d')." - ".$endDate->format('Y-m-d');
            echo "</button>";

            echo $this->Bootstrap->button_link(
                $this->Bootstrap->icon('chevron-right'),
                array(
                    'project' => $project['Project']['name'],
                    'action' => $this->request['action'],
                    'year' => $nextYear,
                    'week' => $nextWeek,
					'?' => $params
                ),
                array('escape'=>false, 'size'=>'small')
            );
            ?>
        </div>
    </div>
</div>
