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
                    <h3>Time Logged on the project</h3>
                    <h5><small>(<?= $total_time['h'] ?> hours <?= $total_time['m'] ?> mins total)</small></h5>
                    <br>
                </div>
                <table class="span10 table table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Logged Time</th>
                            <th style="width: 50%">Description</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    <? if ($page > 1) : ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">
                                <?= $this->Html->link('... See Newer ...', array('project' => $project['Project']['name'], 'action' => 'history', 'page' => ($page - 1)), array('escape' => false)) ?>
                            </td>
                        </tr>
                    <? endif; ?>
                    <? foreach ($times as $time) : ?>
                        <tr>
                            <td style="vertical-align:middle">
                                <?= $this->Gravatar->image($time['User']['email'], array('size' => 30), array('alt' => $time['User']['name'])) ?>
                                <?= $this->Html->link($time['User']['name'], array('controller' => 'users', 'action' => 'view', $time['User']['id'])) ?>
                            </td>
                            <td style="vertical-align:middle">
                                <?= $this->Bootstrap->label($time['Time']['mins']['s'], "warning") ?>
                            </td>
                            <td style="vertical-align:middle; overflow:hidden;">
                                <?= ($time['Time']['description']=='') ? 'n/a' : substr($time['Time']['description'], 0, 70) ?>
                            </td>
                            <td style="vertical-align:middle">
                                <?php
                                echo $this->Bootstrap->button_link(
                                        $this->Bootstrap->icon('search'),
                                        array(
                                            'project' => $time['Project']['name'],
                                            'controller' => 'times',
                                            'action' => 'view',
                                            $time['Time']['id']
                                        ),
                                        array('style' => 'success', 'escape' => false, 'size' => 'mini')
                                    ) .
                                ' ';
                                if ($user_id == $time['User']['id'])
                                echo $this->Bootstrap->button_link(
                                    $this->Bootstrap->icon('pencil', 'white'),
                                        array(
                                            'project' => $time['Project']['name'],
                                            'controller' => 'times',
                                            'action' => 'edit',
                                            $time['Time']['id']
                                        ),
                                        array('style' => 'primary', 'escape' => false, 'size' => 'mini')
                                    ) .
                                ' ';
                                if ($user_id == $time['User']['id'])
                                echo $this->Bootstrap->button_form(
                                    $this->Bootstrap->icon('remove', 'white'),
                                    array('controller' => 'times', 'project' => $time['Project']['name'], 'action' => 'delete', $time['Time']['id']),
                                    array('style' => 'danger', 'escape' => false, 'size' => 'mini'),
                                    "Are you sure you want to delete?"
                                ); ?>
                            </td>
                        </tr>
                    <? endforeach; ?>
                    <? if ($more_pages) : ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">
                                <?= $this->Html->link('... See Older ...', array('project' => $project['Project']['name'], 'action' => 'history', 'page' => ($page + 1)), array('escape' => false)) ?>
                            </td>
                        </tr>
                    <? endif; ?>
                    </tbody>
                </table>
<? endif; ?>
            </div>
        </div>
    </div>
</div>
