<?php
/**
 *
 * View class for APP/times/edit for the SourceKettle system
 * Allows users to edit time allocated to a project
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

<?= $this->DT->pHeader(__("Change your logged time")) ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <?= $this->element('Time/topbar_edit', array('id' => $time['Time']['id']))?>
        <div class="span10">
            <?= $this->Form->create('Time', array('class' => 'form-horizontal')) ?>
            <div class="well times form">
                <?php
                echo $this->element('Time/add', array('span' => 4));
                echo $this->Bootstrap->button("Submit", array("style" => "primary", "size" => "normal", 'class' => 'controls'));
                ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
