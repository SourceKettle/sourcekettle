<?php
/**
 *
 * View class for APP/projects/fork for the SourceKettle system
 * View will allow user to create a new project by cloning an existing one
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2014
 * @link          https://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Projects
 * @since         SourceKettle v 1.5
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

// TODO remove duplication with add.ctp
$hover_unix_name = $this->Popover->popover(
    "UNIX",
    __("So what is a 'Unix' filename?"),
    __("A UNIX file name has to:").
    " <ul>".
    "     <li>".__("Have more than 4 characters")."</li>".
    "     <li>".__("Contain only letters, numbers, dashes and underscores")."</li>".
    "     <li>".__("Start with a letter")."</li>".
    " </ul>"
);

$hover_waffle = $this->Popover->popover(
    __("waffle"),
    __("Wait, what? Waffle?"),
    __("This 'long' waffle should, in short, tell anyone that sees your project:")."\n".
     "<ul>".
     "    <li>".__("What the project does")."</li>".
     "    <li>".__("Why/how it does it")."</li>".
     "</ul>".
    __("For example:<br><i>'A project management system using the CakePHP MVC'</i> (That's SourceKettle)")
);
?>

<div class="row-fluid">
    <div class="span6">
        <?php echo $this->Form->create('Project', array('class' => 'well form-horizontal')); ?>
		<?=__("Cloning from project: <strong>%s</strong>", $this->data['Project']['cloneFrom'])?>
		<?=$this->Bootstrap->input("cloneFrom", array(
			"input" => $this->Form->hidden("cloneFrom", array("label" => false)),
		))?>
        <?php

        echo $this->Bootstrap->input("name", array(
            "input" => $this->Form->text("name", array('autofocus' => '')),
            "help_block" => __("The 'short' name your project will be known by<br>(must be a valid %s name)", $hover_unix_name)
        ));

        echo $this->Bootstrap->input("description", array(
            "input" => $this->Form->textarea("description"),
            "help_block" => __("The 'long' %s explaining your projects intent", $hover_waffle),
        ));

        echo $this->Bootstrap->input("public", array(
            "input" => $this->Form->checkbox("public"),
        ));

        ?>
    </div>

    <div class="span6">
        <h3><?=__("Cloning? What's all this then?")?></h3>
		<p>
		  <?=__("Here at %s, we like collaboration. It's our favourite thing!", $sourcekettle_config['UserInterface']['alias']['value'])?>
		  <?=__("But sometimes, it's time to take things in a new direction.")?>
		</p>
		<p>
		  <?=__("By cloning a project, you can start making your own changes to the original code, and create something new - but built on existing work.")?>
		</p>
		<p>
		  <?=__("If your changes work well, you can even contribute them back to the original project. Share and share alike!")?>
		</p>
    </div>
</div>

<div class="row-fluid">
    <div class="span12" style="text-align:center">
        <?php echo $this->Bootstrap->button("Create Project", array("style" => "primary", "size" => "large", 'class' => 'controls')); ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>
