<?php
/**
 *
 * View class for APP/projects/add for the DevTrack system
 * View will allow user to create a new project
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
 
echo $this->Bootstrap->page_header("New Project <small>where baby projects are made</small>"); ?>

<div class="row">
    <div class="span6">
        <?php echo $this->Form->create('Project', array('class' => 'well form-horizontal')); ?>
        <?php 

        echo $this->Bootstrap->input("name", array(
            "input" => $this->Form->text("name"),
            "help_block" => "The 'short' name your project will be known by"
        ));

        echo $this->Bootstrap->input("description", array(
            "input" => $this->Form->textarea("description"),
            "help_block" => "The 'long' waffle explaining your projects intent"
        ));

        echo $this->Bootstrap->input("public", array(
            "input" => $this->Form->checkbox("public"),
        ));

        echo $this->Bootstrap->input("repositoryType", array(
            "input" => $this->Form->select('repo_type', $repoTypes, array('empty'=>false)),
        ));
        ?>
    </div>
    
    <div class="span6">
        <div>
		    <h4>Fully-Fledged Project Wiki</h4>
            <p>From time to time it may be necessary to document your project with more than just a handful of README files. 
            Our Project Wikis allow you to create documentation to your hearts content.</p>
            <p><?=$this->Form->checkbox('wiki_enabled')?> Yes Please!</p>
        </div>
        <div>
            <h4>Dynamic Task Tracking</h4>
            <p>When it's more than just you working on a project, it can become troublesome keeping track of who's doing what. 
            DevTrack provides task/bug tracking to manage your collaborators.</p>
		    <p><?=$this->Form->checkbox('task_tracking_enabled')?> Yes Please!</p>
        </div>
		<div>
            <h4>Time Tracking</h4>
            <p>Sometimes, spreadsheets just aren't the best way of logging how much time you've spent on a task. 
            Heres where we introduce our simple to use time logging system.
            <p><?=$this->Form->checkbox('time_management_enabled')?> Yes Please!</p>
        </div>
        <p class="help-block"><strong>Note:</strong> Features can be enabled and disabled in the project page later.</p>
    </div>
</div>

<div class="row">
    <div class="span12" style="text-align:center"> 
        <?php echo $this->Bootstrap->button("Create Project", array("style" => "primary", "size" => "large", 'class' => 'controls')); ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>
