<?php
/**
 *
 * View class for APP/times/edit for the DevTrack system
 * Allows users to edit time allocated to a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Times
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header("Correct Logged Time<small> to the nearest 30 mins, please</small>");?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <?= $this->element('Time/topbar') ?>
        <div class="span10">
            <?= $this->Form->create('Time', array('class' => 'form-horizontal')) ?>
            <div class="well times form">
                <?php
                echo $this->Bootstrap->input("mins", array(
                    "input" => $this->Form->text("mins", array("class" => "input-small", "placeholder" => "1h 30m")),
                    "label" => "Time Taken",
                    "help_block" => "The amount of time youve taken (in the format #h #m)"
                ));

                echo $this->Bootstrap->input("description", array(
                    "input" => $this->Form->textarea("description", array("class" => "input-xlarge")),
                    "label" => "Description",
                    "help_inline" => "(Optional)"
                ));

                echo $this->Bootstrap->button("Submit", array("style" => "primary", "size" => "normal", 'class' => 'controls'));
                echo ' ';
                echo $this->Bootstrap->button_form(
                    "Remove",
                    array('controller' => 'times', 'project' => $project['Project']['name'], 'action' => 'delete', $this->request->data['Time']['id']),
                    array('style' => 'danger', 'class' => 'controls', 'size' => 'normal'),
                    "Are you sure you want to delete?"
                );
                ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
