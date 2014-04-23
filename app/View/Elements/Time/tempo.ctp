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
						'controller' => 'users',
						'action' => 'view',
						$userId
					));
					echo "</td>\n";

					// 1=Mon, 7=Sun...
					for ($i = 1; $i <= 7; $i++) {
						if (array_key_exists($i, $userDetails['days'])) {
							echo "<td>".h($userDetails['days'][$i])."</td>\n";
						} else {
							echo "<td></td>\n";
						}
					}

					echo "</tr>\n";

				}
			}?>
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
</div>
