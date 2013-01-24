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
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Projects
 * @since         DevTrack v 0.1
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
                    <?php echo $this->Form->create('Project', array('class' => 'form-inline')); ?>
                    <?php

                    echo $this->Bootstrap->input("description", array(
                        "input" => $this->Form->textarea("description", array('class' => 'span12', 'rows' => 8)),
                        "label" => false
                    ));
                    ?>
                    <h3>Is the project public?</h3>

                    <p><?= $this->Form->checkbox("public") ?> Yes, I would like to allow other DevTrack users to browse my project</p>

                    <?php
                    echo $this->Bootstrap->button("Submit", array("style" => "primary", 'class' => 'controls'));

                    echo $this->Form->end(); ?>
                </div>
            </div>
            <div class="span5">
                <div class="well">
                    <h3>Delete this project</h3>
                    <p>Please note, this action is <strong>not</strong> reversible. This will also delete any material associated with this project (e.g. Wikis).</p>
                    <?= $this->Bootstrap->button_link("Delete this project", array("controller" => "projects", "action" => "delete", "project" => $project['Project']['name']), array("style" => "danger")) ?>
                </div>
            </div>
        </div>
    </div>
</div>
