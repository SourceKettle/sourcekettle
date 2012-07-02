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

$hover_unix_name = $this->Popover->popover(
    'UNIX',
    "So what is a 'Unix' filename?",
    'A UNIX file name has to:
     <ul>
         <li>Have more than 4 characters</li>
         <li>Contain only letters, numbers, dashes and underscores</li>
         <li>Start with a letter</li>
     </ul>'
);

$hover_waffle = $this->Popover->popover(
    'waffle',
    "Wait, what? Waffle?",
    "This 'long' waffle should, in short, tell anyone that sees your project:
     <ul>
         <li>What the project does</li>
         <li>Why/how it does it</li>
     </ul>
     For example:<br><i>'A project management system using the CakePHP MVC'</i> (Thats Devtrack)"
);

echo $this->Bootstrap->page_header("New Project <small>where baby projects are made</small>"); ?>

<div class="row">
    <div class="span6">
        <?php echo $this->Form->create('Project', array('class' => 'well form-horizontal')); ?>
        <?php

        echo $this->Bootstrap->input("name", array(
            "input" => $this->Form->text("name"),
            "help_block" => "The 'short' name your project will be known by<br>(must be a valid $hover_unix_name name)"
        ));

        echo $this->Bootstrap->input("description", array(
            "input" => $this->Form->textarea("description"),
            "help_block" => "The 'long' $hover_waffle explaining your projects intent"
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
        <h3>All DevTrack projects include...</h3>
        <div>
		    <h4>Fully-Fledged Project Wiki</h4>
            <p>From time to time it may be necessary to document your project with more than just a handful of README files.
            Our Project Wikis allow you to create documentation to your hearts content.</p>
            <br>
        </div>
        <div>
            <h4>Dynamic Task Tracking</h4>
            <p>When it's more than just you working on a project, it can become troublesome keeping track of who's doing what.
            DevTrack provides task/bug tracking to manage your collaborators.</p>
		    <br>
        </div>
		<div>
            <h4>Time Tracking</h4>
            <p>Sometimes, spreadsheets just aren't the best way of logging how much time you've spent on a task.
            Heres where we introduce our simple to use time logging system.
            <br>
        </div>
    </div>
</div>

<div class="row">
    <div class="span12" style="text-align:center">
        <?php echo $this->Bootstrap->button("Create Project", array("style" => "primary", "size" => "large", 'class' => 'controls')); ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>
