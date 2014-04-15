<?php
/**
 *
 * View class for APP/projects/view for the DevTrack system
 * View will render a specific project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Projects
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('projects.overview', null, array ('inline' => false));
$this->Html->script('projects.overview.js', array('inline' => false));
?>


<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <div class="span10">
                <div class="well">
                    <div class="row-fluid overview">



                        <div class="span4">
                            <h3><?= __("Tasks") ?></h3>
                            <hr />
                            <div class="row-fluid">
                                <div class="span6">
                                    <ul class="unstyled">
                                        <li class="open-tasks">
                                          <?= $this->Html->link(
                                           "$numberOfOpenTasks - ".__("open tasks"),
                                           array(
                                             'project'    => $project['Project']['name'],
                                             'controller' => 'tasks',
                                             'action'     => 'all',
                                             '1' // Task status IDs
                                           ))?>
                                        </li>

                                        <li class="in-progress-tasks">
                                          <?= $this->Html->link(
                                           "$numberOfInProgressTasks - ".__("tasks in progress"),
                                           array(
                                             'project'    => $project['Project']['name'],
                                             'controller' => 'tasks',
                                             'action'     => 'all',
                                             '2' // Task status IDs
                                           ))?>
                                        </li>

                                        <li class="closed-tasks">
                                          <?= $this->Html->link(
                                            "$numberOfClosedTasks - ".__("finished tasks"),
                                           array(
                                             'project'    => $project['Project']['name'],
                                             'controller' => 'tasks',
                                             'action'     => 'all',
                                             '3,4' // Task status IDs
                                          ))?>
                                        </li>

                                        <li class="dropped-tasks">
                                          <?= $this->Html->link(
                                            "$numberOfDroppedTasks - ".__("dropped tasks"),
                                           array(
                                             'project'    => $project['Project']['name'],
                                             'controller' => 'tasks',
                                             'action'     => 'all',
                                             '5' // Task status IDs
                                          ))?>
                                        </li>

                                        <li class="total-tasks">
                                          <?=$this->Html->link(
                                            "$numberOfTasks - ".__("total tasks"),
                                           array(
                                             'project'    => $project['Project']['name'],
                                             'controller' => 'tasks',
                                             'action'     => 'all'
                                            ))?>
                                        </li>

                                        <li><?= h($percentOfTasks) ?>% <?= __("complete") ?></li>
                                    </ul>
                                </div>
                                <div class="span6">
                                    <?=$this->GoogleChart->create()->setType('pie')->setSize(100, 100)->addData(
										array(
											$numberOfOpenTasks, $numberOfInProgressTasks,
											$numberOfClosedTasks, $numberOfDroppedTasks
										)
									); ?>
                                </div>
                            </div>
                        </div>


                        <div class="span4">
							<h3><?=__("Next Milestone")?></h3>
                            <hr />
                            <ul class="unstyled">
                                <? if ($milestone) : ?>
                                <li><strong><?= $this->Html->link(
                                    $milestone['Milestone']['subject'],
                                    array(
                                        'project'=>$project['Project']['name'],
                                        'controller'=>'milestones',
                                        'action'=>'view',
                                        $milestone['Milestone']['id']
                                    )) ?></strong></li>
                                <br>
                                <li>Due: <?= h($milestone['Milestone']['due']) ?></li>
                                <?= $this->Bootstrap->progress(array("width" => (int) $milestone['Milestone']['percent'], "striped" => true)) ?>
                                <? endif; ?>
                            </ul>
                        </div>


                        <div class="span4">
                            <h3><?= __("Quick Stats")?></h3>
                            <hr />
                            <ul class="unstyled">
                                <li><strong><?= $this->Html->link($numCollab . " " . Inflector::pluralize(__('user'), $numCollab), array('controller' => 'collaborators', 'action' => 'all', 'project' => $project['Project']['name']))?></strong> <?=__("are working on this project")?>.</li>
                                <li><?= __("Last activity was")?> <strong><?= $this->Time->timeAgoInWords($project['Project']['modified']) ?></strong>.</li>
                            </ul>
                        </div>
	
					</div>


					<div class="row-fluid">
						<div class="span4">
						<?=$this->Bootstrap->icon('file')?>
                        <?= $this->Html->link(
                          __('Create a task'),
                          array(
                            'project'    => $project['Project']['name'],
                            'controller' => 'tasks',
                            'action'     => 'add'
                        ))?>
						</div>
						<div class="span4">
						<?=$this->Bootstrap->icon('road')?>
                        <?= $this->Html->link(
                          __('Create a milestone'),
                          array(
                            'project'    => $project['Project']['name'],
                            'controller' => 'milestones',
                            'action'     => 'add'
                        ))?>
						</div>
						<div class="span4">
						<?=$this->Bootstrap->icon('book')?>
                        <?= $this->Html->link(
                          __('Log time'),
                          array(
                            'project'    => $project['Project']['name'],
                            'controller' => 'times',
                            'action'     => 'add'
                        ))?>
						</div>
                    </div>
                </div>
            </div>
			<div class="span10">
                <? if (!empty($project['Project']['description'])){?>
                    <div class='well' id='project_description'>
						<h4><?=__("Project description")?></h4>

                        <? $more_link = '... <span id="view_more_button">' .$this->Html->link('Read More', '#') . '</span>'; ?>

                        <?= $this->Text->truncate($this->Markitup->parse($project['Project']['description']), 100, array('ending' => $more_link, 'exact' => false, 'html' => true)) ?>
                        <div id='full_description'>
							<h4><?=__("Project description")?></h4>
                            <?= $this->Markitup->parse($project['Project']['description']) ?>
                        </div>
                    </div>
                <?}?>
			</div>

            <div class="span10" style="text-align:center">
                <h3><?=__("Recent events for the project")?></h3>
            </div>
            <div class="span10">
                <?= $this->element('history_ajax') ?>
            </div>
        </div>
    </div>
</div>
