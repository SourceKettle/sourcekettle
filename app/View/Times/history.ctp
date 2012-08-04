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
                <div class="span6 offset2">
                    <div class="row">
                    <? foreach ($times as $time) : ?>
                        <?=$this->element('Time/time_row_block', array('time' => $time))?>
                    <? endforeach; ?>
                    </div>
                </div>
<? endif; ?>
            </div>
        </div>
    </div>
</div>
