<?php
/**
 *
 * Element for displaying A Milestone in the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Element.Milestone
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$o = $milestone['Milestone']['oTasks'];
$i = $milestone['Milestone']['iTasks'];
$r = $milestone['Milestone']['rTasks'];
$c = $milestone['Milestone']['cTasks'];

$t = ($o + $i + $r + $c);

$percent_o = ($t == 0) ? 0 : $o / $t * 100;
$percent_i = ($t == 0) ? 0 : $i / $t * 100;
$percent_r = ($t == 0) ? 0 : $r / $t * 100;
$percent_c = ($t == 0) ? 0 : $c / $t * 100;

$link_title = $this->Html->link(
    $milestone['Milestone']['subject'],
    array(
        'project' => $project['Project']['name'],
        'controller' => 'milestones',
        'action' => 'view',
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
		'title' => __('Delete milestone'),
        'escape' => false
    )
);
$link_close = $this->Html->link(
    $this->Bootstrap->icon('off'),
    array(
        'project' => $project['Project']['name'],
        'action' => 'close',
        $milestone['Milestone']['id']
    ),
    array(
        'class' => 'close delete',
		'title' => __('Close milestone'),
        'escape' => false
    )
);
$link_reopen = $this->Html->link(
    $this->Bootstrap->icon('repeat'),
    array(
        'project' => $project['Project']['name'],
        'action' => 'reopen',
        $milestone['Milestone']['id']
    ),
    array(
        'class' => 'close delete',
		'title' => __('Re-open milestone'),
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
		'title' => __('Edit milestone'),
        'escape' => false
    )
);
$link_plan= $this->Html->link(
    $this->Bootstrap->icon('list-alt'),
    array(
        'project' => $project['Project']['name'],
        'action' => 'plan',
        $milestone['Milestone']['id']
    ),
    array(
        'class' => 'close edit',
        'escape' => false
    )
);
?>
<div class="row-fluid">
    <div class="span12">
        <div class="well">
        <div class="row-fluid overview">
            <div class="span5">
                <h3><?= $link_title ?></h3>
            </div>
            <div class="span7">
                <?= $link_remove ?>
                <?= $milestone['Milestone']['is_open']? $link_close : $link_reopen?>
                <?= $link_plan?>
                <?= $link_edit ?>
                <p>
                    <small>
                        <?= ($t == 0) ? __('no tasks in this milestone')  : '' ?>
                        <?= ($c > 0)  ? $this->Bootstrap->badge($c, 'info').' '.__('closed') : ''?>
                        <?= ($r > 0)  ? $this->Bootstrap->badge($r, 'success').' '.__('resolved') : ''?>
                        <?= ($i > 0)  ? $this->Bootstrap->badge($i, 'warning').' '.__('in progress') : ''?>
                        <?= ($o > 0)  ? $this->Bootstrap->badge($o, 'important').' '.__('open') : ''?>
                        <span class="pull-right muted"><?=$milestone['Milestone']['dPoints']?>/<?=$milestone['Milestone']['tPoints']?> points</span>
                    </small>
                </p>
                <div class="progress progress-striped">
                    <div class="bar bar-info"    style="width: <?= $percent_c ?>%;"></div>
                    <div class="bar bar-success" style="width: <?= $percent_r ?>%;"></div>
                    <div class="bar bar-warning" style="width: <?= $percent_i ?>%;"></div>
                    <div class="bar bar-danger"  style="width: <?= $percent_o ?>%;"></div>
                </div>
            </div>
        </div>

        <hr>
        <p><?= $this->DT->parse(h($milestone['Milestone']['description'])) ?></p>
        </div>
    </div>
</div>
