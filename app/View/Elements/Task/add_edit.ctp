<?php
/**
 *
 * View class for APP/tasks/add for the DevTrack system
 * Add a new task for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Tasks
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<?= $this->Form->create('Task', array('class' => 'well form-horizontal')) ?>
<div class="row-fluid">
    <div class="span10">
        <?php
        echo $this->Bootstrap->input("subject", array(
            "input" => $this->Form->text("subject", array("class" => "span9", "placeholder" => __('Quick, yet informative, description'), "maxlength" => 50)),
            "label" => __('Subject'),
            "help_inline" => __("50 characters max")
        ));

        echo $this->Bootstrap->input("task_priority_id", array(
            "input" => $this->Form->input("task_priority_id", array(
                "label"   => false,
                "default" => "2",
                "class"   => "span3"
            )),
            "label" => __('Priority'),
        ));

        echo $this->Bootstrap->input("assignee_id", array(
            "input" => $this->Form->input("assignee_id", array(
                "label"   => false,
                "default" => "2",
                "class"   => "span3",
            )),
            "label" => __('Assigned to')
        ));

        echo $this->Bootstrap->input("time_estimate", array(
            "input" => $this->Form->input("time_estimate", array(
				// Force text field, as we convert  time string to integer
				"type" => "text",
                "label" => false,
                "class" => "span3",
				"placeholder" => "e.g. 2d 4h 3m",
			)),
			"help_inline" => __("Roughly how much time will the task take to finish?"),
            "label" => __('Time Estimate'),
        ));

        echo $this->Bootstrap->input("story_points", array(
            "input" => $this->Form->input("story_points", array(
                "label" => false,
                "class" => "span3",
            )),
			"help_inline" => __("An abstract estimate of how complex the task is to implement"),
            "label" => __('Story Points'),
        ));
		

        echo $this->Bootstrap->input("DependsOn.DependsOn", array(
            "input" => $this->Form->input("DependsOn.DependsOn", array(
                "label"    => false,
                "class"    => "span6",
                "multiple" => "multiple",
                "options"  => $availableTasks, 
            )),
            "label" => __('Depends on').' '.$this->Bootstrap->icon('tasks'),
			"help_block" => "<a href='#' id='unselect-all'>".__("Unselect all")."</a>"
        ));

        echo $this->Bootstrap->input("milestone_id", array(
            "input" => $this->Form->input("milestone_id", array(
                "label" => false,
                "class" => "span6"
            )),
            "label" => __('Milestone').' '.$this->Bootstrap->icon('road'),
        ));

		echo $this->Bootstrap->input("description", array( 
			"input" => $this->Markitup->editor("description", array(
				"class" => "span7",
				"label" => false,
				"placeholder" => __('Longer and more descriptive explanation...')
			)),
			"label" => false,
		));

        echo $this->Bootstrap->button(__('Submit'), array("style" => "primary", 'class' => 'controls'));
        ?>
    </div>
    <div class="span2 sidebarRight">
        <h5><?= __('Issue type') ?></h5>
        <?php

        if (!isset($this->request->data['Task']['task_type_id'])) $this->request->data['Task']['task_type_id'] = 1;
        
        echo $this->Bootstrap->radio("task_type_id", array(
            "options" => array(
                1 => '<div class="tasktype label label-important">bug</div>',
                2 => '<div class="tasktype label label-warning">duplicate</div>',
                3 => '<div class="tasktype label label-success">enhancement</div>',
                4 => '<div class="tasktype label">invalid</div>',
                5 => '<div class="tasktype label label-info">question</div>',
                6 => '<div class="tasktype label label-inverse">wontfix</div>',
                7 => '<div class="tasktype label label-info">documentation</div>',
                8 => '<div class="tasktype label label-info">meeting</div>',
            ),
            "label" => false,
            "control" => false
        ));
        ?>
    </div>
</div>
<?= $this->Form->end() ?>
