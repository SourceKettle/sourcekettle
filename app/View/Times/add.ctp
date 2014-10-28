<?php
/**
 *
 * View class for APP/times/add for the SourceKettle system
 * Allows users to allocate time to a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Times
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<?= $this->DT->pHeader(__("Log time for the project")) ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <?= $this->element('Time/topbar_history') ?>
        <div class="span10">
            <?= $this->Form->create('Time', array('project' => $project['Project']['id'], 'class' => 'form-horizontal')) ?>
            <div class="well times form">
                <?= $this->element('Time/add', array('span' => 4)) ?>
                <?= $this->Bootstrap->button("Submit", array("style" => "primary", 'class' => 'controls')) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
