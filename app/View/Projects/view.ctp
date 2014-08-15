<?php
/**
 *
 * View class for APP/projects/view for the SourceKettle system
 * View will render a specific project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Projects
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<?=$this->Html->script('jquery.color-2.1.2.min', array('inline' => false))?>
<?=$this->Html->script('jquery.flot.min', array('inline' => false))?>
<?=$this->Html->script('jquery.flot.pie.min', array('inline' => false))?>
<?=$this->Html->css('projects.overview', null, array ('inline' => false));?>
<?=$this->Html->script('projects.overview', array('inline' => false));?>



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

						<?=$this->Element('Project/tasksummary', array('span' => 4));?>
						<?=$this->Element('Project/milestonesummary', array('span' => 4));?>
						<?=$this->Element('Project/quickstats', array('span' => 4));?>
	
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
						<?=$this->Bootstrap->icon('time')?>
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
