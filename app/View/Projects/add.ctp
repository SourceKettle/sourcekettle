<?php
/**
 *
 * View class for APP/projects/add for the SourceKettle system
 * View will allow user to create a new project
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
     For example:<br><i>'A project management system using the CakePHP MVC'</i> (That's SourceKettle)"
);
?>

<div class="row-fluid">
    <div class="span8">
        <?= $this->Form->create('Project', array('class' => 'well form-horizontal')); ?>
        <?= $this->Bootstrap->input("name", array(
            "input" => $this->Form->text("name", array('class' => 'span12', 'autofocus' => '')),
            "help_block" => "The 'short' name your project will be known by<br>(must be a valid $hover_unix_name name)"
        ))?>

        <?= $this->Bootstrap->input("description", array(
            "input" => $this->Form->textarea("description", array("class" => "span12")),
            "help_block" => "The 'long' $hover_waffle explaining your projects intent"
        ))?>

        <?= $this->Bootstrap->input("public", array(
            "input" => $this->Form->checkbox("public"),
        ))?>

        <?= $this->Bootstrap->input("repositoryType", array(
            "input" => $this->Form->select('repo_type', $repoTypes, array("class" => "span12", 'empty'=>false, 'value' => $defaultRepo)),
        ))?>

        <?php echo $this->Bootstrap->button("Create Project", array("style" => "primary", "size" => "large", 'class' => 'controls')); ?>
    </div>

    <div class="span4">
        <h3>All SourceKettle projects include...</h3>
        <div>
            <h4>Dynamic Task Tracking</h4>
            <p>When it's more than just you working on a project, it can become troublesome keeping track of who's doing what.
            SourceKettle provides task/bug tracking to manage your collaborators.</p>
		    <br>
        </div>
		<div>
            <h4>Time Tracking</h4>
            <p>Sometimes, spreadsheets just aren't the best way of logging how much time you've spent on a task.
            Here's where we introduce our simple to use time logging system.
            <br>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>
