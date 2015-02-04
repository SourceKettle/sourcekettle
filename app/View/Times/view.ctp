<?php
/**
 *
 * View class for APP/times/view for the SourceKettle system
 * Allows users to view time allocated to a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Times
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<div class="row-fluid">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
		<div class="row-fluid">
        <?= $this->element('Time/topbar_view', array('id' => $time['Time']['id'])) ?>
		</div>
		<div class="row-fluid">
            <div class="well times form form-horizontal">
                <dl class="dl-horizontal">
                    <dt><?= __('Time Logged') ?></dt>
                    <dd><?= h($time['Time']['minutes']['s']) ?></dd>

                    <dt><?= __('Description') ?></dt>
                    <dd><?= ($time['Time']['description']) ? h($time['Time']['description']) : 'n/a' ?></dd>

                    <dt><?= __('Date') ?></dt>
                    <dd><?= h($time['Time']['date']) ?></dd>

                    <? if (isset($task['Task']) && $task['Task']['public_id']) { ?>
                    <dt><?= __('Attached Task') ?></dt>
                    <dd><?= $this->Html->link('#'.$task['Task']['public_id'].' - '.$task['Task']['subject'], array('project'=>$project['Project']['name'],'controller'=>'tasks','action'=>'view',$task['Task']['public_id'])) ?></dd>
                    <? } ?>
                </dl>
                <dl class="dl-horizontal">
                    <dt><?= __('Created By') ?></dt>
                    <dd>
                        <?= $this->Gravatar->image($time['User']['email'], array('size' => 24), array('alt' => $time['User']['name'])) ?>
                        <?= $this->Html->link($time['User']['name'], array('controller'=>'users','action'=>'view',$time['User']['id'])) ?>
                        - <small><?= $this->Time->timeAgoInWords($time['Time']['created']) ?></small>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
