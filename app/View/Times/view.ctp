<?php
/**
 *
 * View class for APP/times/view for the DevTrack system
 * Allows users to view time allocated to a project
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

echo $this->Bootstrap->page_header("View Logged Time<small> we have been busy, haven't we?</small>");?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <?= $this->element('Topbar/time') ?>
        <div class="span10">
            <div class="well times form form-horizontal">
                <div class="control-group">
                    <p class="control-label">Time Logged</p>
                    <div class="controls">
                        <span class="span6" style="padding-top:5px"><?= $time['Time']['mins']['s'] ?></span>
                    </div>
                </div>
                <div class="control-group">
                    <p class="control-label">Description</p>
                    <div class="controls">
                        <span class="span6" style="padding-top:5px"><?= $time['Time']['description'] ?></span>
                    </div>
                </div>
                <div class="control-group">
                    <p class="control-label">Date</p>
                    <div class="controls">
                        <span class="span6" style="padding-top:5px"><?= $time['Time']['date'] ?></span>
                    </div>
                </div>
                <div class="control-group">
                    <p class="control-label">Created By</p>
                    <div class="controls">
                        <span class="span6" style="padding-top:5px">
                            <?= $this->Gravatar->image($time['User']['email'], array('size' => 30), array('alt' => $time['User']['name'])) ?>
                            <?= $this->Html->link($time['User']['name'], array('controller'=>'users','action'=>'view',$time['User']['id'])) ?>
                        </span>
                    </div>
                </div>
                <div class="control-group">
                    <p class="control-label">Created<br>Last Modified</p>
                    <div class="controls">
                        <span class="span6" style="padding-top:5px"><?= $time['Time']['created'] ?><br><?= $time['Time']['modified'] ?></span>
                    </div>
                </div>
                <?php
                echo $this->Bootstrap->button_link("Edit", array('project'=>$project['Project']['name'],'action'=>'edit',$time['Time']['id']), array("style" => "success"));
                echo ' ';
                echo $this->Bootstrap->button_form(
                    "Remove",
                    array('controller' => 'times', 'project' => $project['Project']['name'], 'action' => 'delete', $time['Time']['id']),
                    array('style' => 'danger', 'class' => 'controls', 'size' => 'normal'),
                    "Are you sure you want to delete?"
                );
                ?>
            </div>
        </div>
    </div>
</div>
