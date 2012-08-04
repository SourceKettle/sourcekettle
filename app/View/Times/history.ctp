<?php
/**
 *
 * View class for APP/times/history for the DevTrack system
 * Shows a graph of user contribution to a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Times
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$total = array('hours' => 0, 'mins' => 0);

foreach ($times as $time) {
    $total['hours'] += (int) $time['Time']['mins']['hours'];
    $total['mins'] += (int) $time['Time']['mins']['mins'];
}

// How full will the mini graph be?
function size($time) {
    if ($time <= 30) {
        return array(10, 90);
    } elseif ($time <= 60) {
        return array(20, 80);
    } elseif ($time <= 120) {
        return array(30, 70);
    } elseif ($time <= 240) {
        return array(40, 60);
    } elseif ($time <= 480) {
        return array(50, 50);
    } elseif ($time <= 960) {
        return array(60, 40);
    } elseif ($time <= 1920) {
        return array(70, 30);
    } elseif ($time <= 3684) {
        return array(80, 20);
    } else {
        return array(90, 10);
    }
}

echo $this->Bootstrap->page_header("Time Logged For The Project <small>" . $project['Project']['name'] . " </small>");?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <?= $this->element('Time/topbar') ?>
        <div class="span10">
            <div class="row">
                <div class="span10" style="text-align:center">
<? if (empty($times)) : ?>
                    <h1>No Time Logged Yet</h1>
                    <h3><small>Go on, click the 'Log Time' button</small></h3>
                </div>
<? else : ?>
                    <h3>Time Contribution</h3>
                    <h5><small>(<?= $total['hours'] ?> hours <?= $total['mins'] ?> mins total)</small></h5>
                    <br>
                </div>
                <table class="span6 offset2 table table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Logged Time</th>
                            <th>Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <? foreach ($times as $time) : ?>
                        <tr>
                            <td style="vertical-align:middle">
                                <?= $this->Gravatar->image($time['User']['email'], array('size' => 30), array('alt' => $time['User']['name'])) ?>
                                <?= $this->Html->link($time['User']['name'], array('controller' => 'users', 'action' => 'view', $time['User']['id'])) ?>
                            </td>
                            <td style="vertical-align:middle">
                                <?= $this->Bootstrap->label($time['Time']['mins']['hours'].'h '.$time['Time']['mins']['mins'].'m', "warning") ?>
                            </td>
                            <td style="vertical-align:middle">
                                <?= $this->Time->timeAgoInWords($time['Time']['created']) ?>
                            </td>
                            <td style="vertical-align:middle">
                                <?= $this->GoogleChart->create()->setType('pie')->setSize(30, 30)->addData(size($time['Time']['mins']['mins'] + (60 * $time['Time']['mins']['hours'])))->setMargins(0, 0, 0, 0)//->setContainerSize(40, 40) ?>
                            </td>
                        </tr>
                    <? endforeach; ?>
                    </tbody>
                </table>
<? endif; ?>
            </div>
        </div>
    </div>
</div>
