<?php
/**
 *
 * View class for APP/projects/edit for the DevTrack system
 * Edit allows editing of a specific project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Projects
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
 
$smallText = " <small>" . $project['Project']['description'] . " </small>";

echo $this->Bootstrap->page_header($project['Project']['name'] . $smallText); ?>
	
<div class="row">
    <div class="span2">
        <?= $this->element('project_sidebar', array('project' => $project['Project']['name'], 'action' => 'edit')) ?>
    </div>
    <div class="span10">
        <div class="row">
            <div class="span6">
                <div class="well row-split">
                    <h3>Edit Project</h3>
                    <?php echo $this->Form->create('Project', array('class' => 'form-vertical')); ?>
                    <?php 

                    echo $this->Bootstrap->input("description", array(
                        "input" => $this->Form->textarea("description", array('class' => 'span5')),
                    ));

                    echo $this->Bootstrap->input("public", array(
                        "input" => $this->Form->checkbox("public"),
                    ));

                    echo $this->Bootstrap->button("Update Project", array("style" => "primary", "size" => "large", 'class' => 'controls')); 

                    echo $this->Form->end(); ?>
                </div>
            </div>
            <div class="span4">
                <div class="well">
                    <h3>Delete this project</h3>
                    <p>Please note, this action is <strong>not</strong> reversible. This will also delete any material associate with this project (e.g. Wikis).</p>
                    <?= $this->Bootstrap->button_form("Delete this project", array("controller" => "projects", "action" => "delete", $this->Form->value('Project.id')), array("style" => "danger", "size" => "large"), "Yep, one final time. Are you sure you want to delete this project?") ?>
                </div>
            </div>
        </div>
    </div>
</div>
