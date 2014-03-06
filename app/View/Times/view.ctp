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
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Times
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <?= $this->element('Time/topbar_view', array('id' => $time['Time']['id'])) ?>
        <div class="span10">
            <div class="well times form form-horizontal">
                <dl class="dl-horizontal">
                    <dt><?= $this->DT->t('info.time.logged') ?></dt>
                    <dd><?= h($time['Time']['minutes']['s']) ?></dd>

                    <dt><?= $this->DT->t('info.time.description') ?></dt>
                    <dd><?= ($time['Time']['description']) ? h($time['Time']['description']) : 'n/a' ?></dd>

                    <dt><?= $this->DT->t('info.time.date') ?></dt>
                    <dd><?= h($time['Time']['date']) ?></dd>

                    <? if (isset($task['Task']) && $task['Task']['id']) { ?>
                    <dt><?= $this->DT->t('info.time.task') ?></dt>
                    <dd><?= $this->Html->link('#'.$task['Task']['id'].' - '.$task['Task']['subject'], array('project'=>$project['Project']['name'],'controller'=>'tasks','action'=>'view',$task['Task']['id'])) ?></dd>
                    <? } ?>
                </dl>
                <dl class="dl-horizontal">
                    <dt><?= $this->DT->t('info.time.created') ?></dt>
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
