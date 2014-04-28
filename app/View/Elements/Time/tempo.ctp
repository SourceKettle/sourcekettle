<?php
/**
 *
 * Tempo display for APP/times/history for the DevTrack system
 * Shows a table of time vs. tasks
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Time
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */


$this->Html->css('time.tempo', null, array ('inline' => false));
$this->Html->css('jquery.dataTables', null, array ('inline' => false));
$this->Html->script('jquery.dataTables.min', array ('inline' => false));

/*$this->Html->scriptBlock("
    $('.tempo').tooltip({
        selector: 'th[rel=tooltip]'
    })
    $('.tempoBody').bind('click', function() {
        var a = $('#' + $(this).attr('data-toggle'));

        $('.dp1').val($(this).attr('data-date'));

        var taskId = $(this).attr('data-taskId');
        $('option[value='+taskId+']').attr('selected', 'selected');

        if (a.size() === 0) {
            $('#addTimeModal').modal('show');
        } else {
            a.modal('show');
        }
    });
", array('inline' => false));*/

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
			$overallTotal = 0;
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
        	                $taskId
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

					// 1=Mon, 7=Sun...
					$rowTotal = 0;
					for ($i = 1; $i <= 7; $i++) {
						if (array_key_exists($i, $userDetails['days'])) {
							$rowTotal += $userDetails['days'][$i];
							$timeSpent = TimeString::renderTime($userDetails['days'][$i]);
							echo "<td>".h($timeSpent['s'])."</td>\n";
						} else {
							echo "<td>---</td>\n";
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
