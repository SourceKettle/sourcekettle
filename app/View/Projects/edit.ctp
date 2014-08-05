<?php
/**
 *
 * View class for APP/projects/edit for the SourceKettle system
 * Edit allows editing of a specific project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          https://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Projects
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$smallText = " <small>Edit Project</small>";

echo $this->Bootstrap->page_header($project['Project']['name'] . $smallText); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row-fluid">
            <div class="span7">
                <div class="well">
                    <h3>Project description</h3>
                    <?=$this->Form->create('Project', array('class' => 'form-inline')); ?>
					<?=$this->Bootstrap->input("description", array( 
						"input" => $this->Markitup->editor("description", array(
							"class" => "span7",
							"label" => false,
						)),
						"label" => false,
					));?>

                    <h3>Is the project public?</h3>

                    <p><?= $this->Form->checkbox("public") ?> Yes, I would like to allow other SourceKettle users to browse my project</p>

                    <?=$this->Bootstrap->button("Submit", array("style" => "primary", 'class' => 'controls'));?>

                    <?=$this->Form->end();?>
                </div>
            </div>
            <div class="span5">
                <div class="well">
                    <h3>Delete this project</h3>
                    <p>Please note, this action is <strong>not</strong> reversible. This will also delete any material associated with this project (e.g. Wikis).</p>
                    <?= $this->Bootstrap->button_link("Delete this project", array("controller" => "projects", "action" => "delete", "project" => $project['Project']['name']), array("style" => "danger")) ?>
                </div>
				<? if ($noRepo) {?>
				<div class="well">
					<h3>Project is repository-less!</h3>
					Need to add a repository? <?=$this->Html->link('Go here!', array('controller' => 'projects', 'action' => 'add_repo', 'project' => $project['Project']['name']))?>
				</div>
				<? } ?>
            </div>
        </div>
    </div>
</div>
