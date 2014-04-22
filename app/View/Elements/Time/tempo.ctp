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

echo "<pre>"; print_r($weekTimes); echo "</pre>\n";


$this->Html->css('time.tempo', null, array ('inline' => false));

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
    <table class="well table table-condensed table-striped tempo">
        <thead>
            <tr>
                <th><?= __('Task') ?></th>
                <th><?= __('User') ?></th>
				<th><?= __('Mon') ?></th>
				<th><?= __('Tue') ?></th>
				<th><?= __('Wed') ?></th>
				<th><?= __('Thu') ?></th>
				<th><?= __('Fri') ?></th>
				<th><?= __('Sat') ?></th>
				<th><?= __('Sun') ?></th>
                <?php
                    /*foreach ($weekdays as $day => $times) {
                        $today = ($times['today']) ? 'today' : '';
                        echo "<th width='5%' class='tempoHeader $today' rel='tooltip' data-original-title='{$times['date']}'>$day</th>";
                    }*/
					/*foreach ($weekTimes as $taskId => $taskDetails){
					  echo "<th>Task: $taskId</th>";
					}*/
                ?>
            </tr>
        </thead>
        <tbody>
        <?php
			foreach ($weekTimes as $taskId => $taskDetails) {
				foreach ($taskDetails['users'] as $userId => $userDetails) {
					echo "<tr>\n";
					if ($taskId == 0) {
						echo "<td>(".__("No associated task").")</td>\n";
					} else {
						echo "<td><a href='".$this->Html->url(array(
								'controller' => 'tasks',
								'action' => 'view',
								'project' => $project['Project']['name'],
								$taskId
							))."'>".
							h($taskDetails['Task']['subject']).
						"</a></td>\n";
					}


					echo "<td><a href='".$this->Html->url(array(
							'controller' => 'users',
							'action' => 'view',
							$userId
						))."'>".
						h($userDetails['User']['name']).
					"</a></td>\n";


					for ($i = 1; $i <= 7; $i++) {
						if (array_key_exists($i, $userDetails['days'])) {
							echo "<td>".h($userDetails['days'][$i])."</td>\n";
						} else {
							echo "<td></td>\n";
						}
					}

					echo "</tr>\n";

				}
			}
            /*$elements = '';
            foreach ($weekTasks as $task) {
                $eventTitle = $task['Task']['subject'];

                if ($task['Task']['id'] > 0) {
                    $eventTitle = $this->Html->link(
                        $eventTitle,
                        array(
                            'action' => 'view',
                            'controller' => 'tasks',
                            'project' => $project['Project']['name'],
                            $task['Task']['id']
                        )
                    );
                }

                $columns = '';
                foreach ($week as $day => $times) {
                    $taskId = $task['Task']['id'];

                    $popover = "tempo_{$day}_{$taskId}";
                    $total = '';

                    if (isset($times['times'][$taskId])) {
                        $elements .= $this->element('Time/tempo_modal',
                            array(
                                'id' => $popover,
                                'times' => $times['times'][$taskId],
                                'date' => $times['date']
                            )
                        );
                        $total = $times['totalTimes'][$taskId];
                    }

                    $today = ($times['today']) ? 'today' : '';

                    $columns .= "<td data-date='{$times['date']}' data-taskId='{$taskId}' class='tempoBody $today' data-toggle='$popover'>$total</td>";
                }

                echo "<tr><td>$user</td><td>$eventTitle</td>$columns</tr>";
            }*/
        ?>
            <tr>
                <td><strong><?= $this->DT->t('tempo.table.total.text') ?></strong></td>
            <?php
                foreach ($week as $day => $times) {
                    if ($times['totalTime'] == 0) $times['totalTime'] = '';
                    $today = ($times['today']) ? 'today' : '';
                    echo "<td class='tempoFooter $today'>{$times['totalTime']}</td>";
                }
            ?>
            </tr>
        </tbody>
    </table>
    <div class="btn-toolbar tempo-toolbar span12">
        <div class="btn-group">
            <?php
            echo $this->Bootstrap->button_link(
                $this->Bootstrap->icon('chevron-left'),
                array(
                    'project' => $project['Project']['name'],
                    'action' => $this->request['action'],
                    'year' => $prevYear,
                    'week' => $prevWeek
                ),
                array('escape'=>false, 'size'=>'small')
            );

            echo "<button class='btn disabled btn-small'>";
            echo $this->DT->t('tempo.table.week.text');
            echo "$thisWeek - $thisYear";
            echo "</button>";

            echo $this->Bootstrap->button_link(
                $this->Bootstrap->icon('chevron-right'),
                array(
                    'project' => $project['Project']['name'],
                    'action' => $this->request['action'],
                    'year' => $nextYear,
                    'week' => $nextWeek
                ),
                array('escape'=>false, 'size'=>'small')
            );
            ?>
        </div>
    </div>
    <?= $elements ?>
</div>
