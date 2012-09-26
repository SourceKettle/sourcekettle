<?php
/**
 *
 * View class for APP/times/add for the DevTrack system
 * Allows users to allocate time to a project
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

echo $this->Bootstrap->page_header("Log Time<small> to the nearest 30 mins, please</small>");?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <?= $this->element('Time/topbar_history') ?>
        <div class="span10">
            <?= $this->Form->create('Time', array('project' => $project['Project']['id'], 'class' => 'form-horizontal')) ?>
            <div class="well times form">
                <?= $this->element('Time/add') ?>
                <?= $this->Bootstrap->button("Submit", array("style" => "primary", 'class' => 'controls')) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
