<?php
/**
 *
 * Element for displaying A Milestone in the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Element.Milestone
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

if ($milestone['Milestone']['closed_tasks'] == 0) {
    $percent = 0;
} else {
    $percent = $milestone['Milestone']['closed_tasks'] / ($milestone['Milestone']['closed_tasks'] + $milestone['Milestone']['open_tasks']) * 100;
}
?>
<div class="row-fluid well">
    <div class="span12">

        <div class="row-fluid overview">
            <div class="span6">
                <h3><?= $milestone['Milestone']['subject'] ?></h3>
            </div>
            <div class="span6">
                <?= $this->Html->link($this->Bootstrap->icon('remove-circle'), array('project' => $project['Project']['name'], 'action' => 'delete', $milestone['Milestone']['id']), array('class' => 'close delete', 'escape' => false)) ?>
                <?= $this->Html->link($this->Bootstrap->icon('pencil'), array('project' => $project['Project']['name'], 'action' => 'edit', $milestone['Milestone']['id']), array('class' => 'close edit', 'escape' => false)) ?>
                <p><small><?= $milestone['Milestone']['open_tasks'] ?> open - <?= $milestone['Milestone']['closed_tasks'] ?> closed</small></p>
                <div class="progress progress-striped<?= ($percent == 0) ? ' zero' : '' ?>">
                    <div class="bar" style="width: <?= $percent ?>%;"><?= $percent ?>%</div>
                </div>
            </div>
        </div>

        <hr>
        <p><?= $this->DT->parse($milestone['Milestone']['description']) ?></p>

    </div>
</div>
