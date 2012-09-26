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
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements.Time
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$tDay = date('Y-m-d');

$this->Html->css('time.tempo', null, array ('inline' => false));

$this->Html->scriptBlock("
    $('.tempoBody').bind('click', function() {
        var a = $('#' + $(this).attr('data-toggle'));

        if (a.size() === 0) {
            $('#addTimeModal').modal('show');
        } else {
            a.modal('show');
        }
    });
", array('inline' => false));

echo $this->element('Time/modal_add');
?>
<div>
    <table class="well table table-condensed table-striped tempo">
        <thead>
            <tr>
                <th><?= $this->DT->t('tempo.table.task.text') ?></th>
            <?php
                for ($x = 1; $x <= 7; $x++) {
                    $date = date('Y-m-d', strtotime("$weekStart +".($x-1)." days"));

                    $classes = "tempoHeader ";

                    if ($date == $tDay) {
                        $classes .= "today ";
                    }

                    echo "<th width=\"5%\" class=\"$classes\">";
                    echo date('D', strtotime('-'.($dayOfWeek - $x).' day'));
                    echo "</th>";
                }
            ?>
            </tr>
        </thead>
        <tbody>
        <?php
            $elements = '';
            foreach ($tasks as $task) {
                echo "<tr>";

                echo "<td>";
                echo ($task['Task']['id'] > 0) ? $this->Html->link(
                    $task['Task']['subject'],
                    array(
                        'action' => 'view',
                        'controller' => 'tasks',
                        'project' => $project['Project']['id'],
                        $task['Task']['id']
                    )
                ) : $task['Task']['subject'];
                echo "</td>";

                for ($x = 1; $x <= 7; $x++) {
                    $date = date('Y-m-d', strtotime("$weekStart +".($x-1)." days"));

                    $classes = "tempoBody ";

                    if ($date == $tDay) {
                        $classes .= "today ";
                    }

                    $popover = 'tempo_'.$x.'_'.$task['Task']['id'];
                    $elements .= $this->element('Time/tempo_modal',
                        array(
                            'id' => $popover,
                            'times' => $week[$date][$task['Task']['id']],
                            'date' => $date
                        )
                    );

                    echo "<td id=\"$date\" class=\"$classes\" data-toggle=\"$popover\">";
                    echo ($week[$date][$task['Task']['id']]['total'] != 0) ? $week[$date][$task['Task']['id']]['total'] : '';
                    echo "</td>";
                }
                echo "</tr>";
            }
        ?>
            <tr>
                <td><strong><?= $this->DT->t('tempo.table.total.text') ?></strong></td>
            <?php
                for ($x = 1; $x <= 7; $x++) {
                    $date = date('Y-m-d', strtotime("$weekStart +".($x-1)." days"));

                    $classes = "tempoFooter ";

                    if ($date == $tDay) {
                        $classes .= "today ";
                    }

                    echo "<td class=\"$classes\">";
                    echo $week[$date]['total'];
                    echo "</td>";
                }
            ?>
            </tr>
        </tbody>
    </table>
    <div class="btn-toolbar tempo-toolbar span12">
        <div class="btn-group">
            <?=$this->Bootstrap->button_link(
                $this->Bootstrap->icon('chevron-left'),
                array(
                    'project' => $project['Project']['name'],
                    'action' => $this->request['action'],
                    $weekNo-1
                ),
                array('escape'=>false, 'size'=>'small')
            )?>
            <button class="btn disabled btn-small"><?= $this->DT->t('tempo.table.week.text').$weekNo ?></button>
            <?=$this->Bootstrap->button_link(
                $this->Bootstrap->icon('chevron-right'),
                array(
                    'project' => $project['Project']['name'],
                    'action' => $this->request['action'],
                    $weekNo+1
                ),
                array('escape'=>false, 'size'=>'small')
            )?>
        </div>
    </div>
    <?= $elements ?>
</div>
