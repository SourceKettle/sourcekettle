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
$o = $milestone['Milestone']['o_tasks'];
$i = $milestone['Milestone']['i_tasks'];
$r = $milestone['Milestone']['r_tasks'];
$c = $milestone['Milestone']['c_tasks'];

$t = ($o + $i + $r + $c);

$percent_o = ($t == 0) ? 0 : $o / $t * 100;
$percent_i = ($t == 0) ? 0 : $i / $t * 100;
$percent_r = ($t == 0) ? 0 : $r / $t * 100;
$percent_c = ($t == 0) ? 0 : $c / $t * 100;

$link_title = $this->Html->link(
    $milestone['Milestone']['subject'],
    array(
        'project' => $project['Project']['name'],
        'controller' => 'tasks',
        'action' => 'sprint',
        $milestone['Milestone']['id']
    )
);
$link_remove = $this->Html->link(
    $this->Bootstrap->icon('remove-circle'),
    array(
        'project' => $project['Project']['name'],
        'action' => 'delete',
        $milestone['Milestone']['id']
    ),
    array(
        'class' => 'close delete',
        'escape' => false
    )
);
$link_edit = $this->Html->link(
    $this->Bootstrap->icon('pencil'),
    array(
        'project' => $project['Project']['name'],
        'action' => 'edit',
        $milestone['Milestone']['id']
    ),
    array(
        'class' => 'close edit',
        'escape' => false
    )
);
?>
<div class="row-fluid well">
    <div class="span12">

        <div class="row-fluid overview">
            <div class="span5">
                <h3><?= $link_title ?></h3>
            </div>
            <div class="span7">
                <?= $link_remove ?>
                <?= $link_edit ?>
                <p>
                    <small>
                        <?= ($t == 0) ? $this->DT->t('block.progress.notasks.text', array('action'=>'open')) : '' ?>
                        <?= ($o > 0) ? $this->Bootstrap->badge($o, 'warning').$this->DT->t('block.progress.open.text', array('action'=>'open')) : '' ?>
                        <?= ($i > 0) ? $this->Bootstrap->badge($i, 'info').$this->DT->t('block.progress.inprogress.text', array('action'=>'open')) : '' ?>
                        <?= ($r > 0) ? $this->Bootstrap->badge($r).$this->DT->t('block.progress.resolved.text', array('action'=>'open')) : '' ?>
                        <?= ($c > 0) ? $this->Bootstrap->badge($c, 'success').$this->DT->t('block.progress.closed.text', array('action'=>'open')) : '' ?>
                    </small>
                </p>
                <div class="progress progress-striped">
                    <div class="bar bar-warning" style="width: <?= $percent_o ?>%;"></div>
                    <div class="bar bar-info" style="width: <?= $percent_i ?>%;"></div>
                    <div class="bar" style="width: <?= $percent_r ?>%;"></div>
                    <div class="bar bar-success" style="width: <?= $percent_c ?>%;"></div>
                </div>
            </div>
        </div>

        <hr>
        <p><?= $this->DT->parse($milestone['Milestone']['description']) ?></p>

    </div>
</div>
