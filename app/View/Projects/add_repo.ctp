<?php
/**
 *
 * View class for APP/projects/add_repo for the SourceKettle system
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

$smallText = " <small>Add a repository</small>";

echo $this->Bootstrap->page_header($project['Project']['name'] . $smallText); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row-fluid">
            <div class="span7">
                <div class="well">
                    <h3>Add a version control repository to your project</h3>
                    <?=$this->Form->create('Project', array('class' => 'form-inline')); ?>

			        <?=$this->Bootstrap->radio("repo_type", array(
			            "options" => $repoTypes,
			            "label" => false,
			            "control" => false
			        ));?>
                    <?=$this->Bootstrap->button("Add repo", array("style" => "primary", 'class' => 'controls'));?>
                    <?=$this->Form->end(); ?>
                </div>
            </div>
            <div class="span5">
                <div class="well">
                    <h3>Version control</h3>
					<p>
					  You're probably here because you created a SourceKettle project for keeping track of tasks and time, and you didn't need
					  a version control repository at the time - but now you do!
					</p>
					<p>
					  That's no problem, you can add one now. Note that you will not be able to remove the repo or change the repository type later
					  without bugging your system administrator to do it for you (and buying them cake).
					</p>
                </div>
            </div>
        </div>
    </div>
</div>
