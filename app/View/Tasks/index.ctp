<?php
/**
 *
 * View class for APP/tasks/index for the DevTrack system
 * Shows a list of tasks for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Tasks
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('tasks.index', null, array ('inline' => false));
$this->Html->scriptBlock("
        var repeat = function() {
            var details = {};

            // Set the project
            details['project'] = '".$project['Project']['id']."';

            // Get the status of the desired tasks
            var status = $('#taskStatus').find('.active').find('a').html();
            details['status'] = status;

            // Get the desired owner of tasks
            details['requester'] = '".$this->request['action']."';

            // Get the desired priorities
            details['types'] = '0';
            $('#priorityCheckboxes').find(':checked').each(
                function(a,b){
                    details['types'] = details['types'] + ',' + b.value;
                }
            );

            // Fetch the desired milestone
            var milestone = $('#milestone').attr('value');
            if (milestone != '') {
                details['milestone'] = milestone;
            }

            $.post('".$this->Html->url('/api/tasks/marshalled/')."', details, function(data) {
                $('#tasksCol').html(data);
                if (milestone != '') {
                    $.getJSON('".$this->Html->url('/api/milestones/view/')."' + milestone, function(data) {
                        $('#milestoneProgress').attr('style', 'width: ' + data['percent'] + '%;');
                    });
                } else {
                    $('#milestoneProgress').attr('style', 'width: 0%;');
                }
            });
        }

        jQuery(function(){
            $('#milestone').bind('change', function() {
                repeat();
            });
            $('#priorityCheckboxes').find('input[type!=hidden]').bind('change', function() {
                repeat();
            });

            $('#taskStatus').find('li').unbind('click'); //TODO FIX
            $('#taskStatus').find('li').bind('click', function(data) {
                $('#taskStatus').find('.active').attr('class', '');
                $(data.target).parent().attr('class', 'active');
                repeat();
            });

            repeat();
        });
    ", array('inline' => false));
?>

<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <?= $this->element('Task/topbar_index') ?>
            <div class="span10">
                <div class="row-fluid">


                    <div class="span7">
                        <div class="well col">
                            <h2><?= $this->DT->t('topbar.'.$this->request['action'].'.text', array('action' => 'topbar')) ?></h2>
                            <hr />
                            <span id="tasksCol">
                            </span>
                        </div>
                    </div>

                    <div class="span5">
                        <div class="well col">

                            <div class="row-fluid">
                                <div class="span6">
                                    <ul id="taskStatus" class="nav nav-pills nav-stacked">
                                        <li class="active">
                                            <a href="#"><?= $this->DT->t('column.options.statuses.all') ?></a>
                                        </li>
                                        <li class="">
                                            <a href="#"><?= $this->DT->t('column.options.statuses.open') ?></a>
                                        </li>
                                        <li class="">
                                            <a href="#"><?= $this->DT->t('column.options.statuses.progress') ?></a>
                                        </li>
                                        <li class="">
                                            <a href="#"><?= $this->DT->t('column.options.statuses.resolved') ?></a>
                                        </li>
                                        <li class="">
                                            <a href="#"><?= $this->DT->t('column.options.statuses.closed') ?></a>
                                        </li>
                                    </ul>
                                </div>
                                <div id="priorityCheckboxes" class="span6">
                                    <div class="tasktype label label-important"><?= $this->Form->checkbox('bug', array('value'=>1,'checked'=>true)) ?> bug</div>
                                    <div class="tasktype label label-warning"><?= $this->Form->checkbox('duplicate', array('value'=>2,'checked'=>true)) ?> duplicate</div>
                                    <div class="tasktype label label-success"><?= $this->Form->checkbox('enhancement', array('value'=>3,'checked'=>true)) ?> enhancement</div>
                                    <div class="tasktype label"><?= $this->Form->checkbox('invalid', array('value'=>4,'checked'=>true)) ?> invalid</div>
                                    <div class="tasktype label label-info"><?= $this->Form->checkbox('question', array('value'=>5,'checked'=>true)) ?> question</div>
                                    <div class="tasktype label label-inverse"><?= $this->Form->checkbox('wontfix', array('value'=>6,'checked'=>true)) ?> wontfix</div>
                                </div>
                            </div>
                            <hr>
                            <p class="lead"><strong><?= $this->DT->t('column.options.milestone.title') ?>:</strong> <?= $this->Form->select('milestone', $open_milestones) ?></p>
                            <div class="progress progress-striped">
                                <div  id="milestoneProgress" class="bar bar-success" style="width: 0%;"></div>
                            </div>
                        </div>

                        <div class="well col">
                            <h2><?= $this->DT->t('column.history.title') ?></h2>
                            <hr />
                            <?= $this->element('history', array('events' => $events, 'short' => 1)) ?>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
