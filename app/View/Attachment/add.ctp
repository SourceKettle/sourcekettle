<?php
/**
 *
 * View class for APP/attachments/add for the DevTrack system
 * View will allow user to create a new project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Attachment
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('pages/attachments', null, array ('inline' => false));
$this->Html->script('bootstrap-fileupload', array('block' => 'scriptBottom'));
$this->Html->scriptBlock("$(function () { $('#fileupload').fileupload() });");

$hover_conditions = $this->Popover->popover(
    'restrictions',
    "File upload restrictions",
    'Please note, a file has to be:
     <ul>
         <li>under 2Mb in size</li>
         <li>be a standard mime type</li>
     </ul>'
);

?>

<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <?= $this->element('Attachment/topbar_add') ?>
            <div class="span10">
                <div class="row-fluid">
                    <div class="well uploadBox">
            <?= $this->Form->create('Attachment', array('type' => 'file')) ?>
                <h3><?= $this->DT->t('instruction') ?></h3>
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="input-append">
                        <div class="uneditable-input span4">
                            <i class="icon-download fileupload-exists"></i>
                            <span class="fileupload-preview" style=""></span>
                        </div>
                        <span class="btn btn-file">
                            <span class="fileupload-new"><?= $this->DT->t('form.select') ?></span>
                            <span class="fileupload-exists"><?= $this->DT->t('form.change') ?></span>
                            <?= $this->Form->input('fileName', array('type' => 'file', 'label' => false, 'div' => false)) ?>
                        </span>
                        <?= $this->Bootstrap->button($this->DT->t('form.upload'), array("style" => "success", 'class' => 'fileupload-exists')) ?>
                    </div>
                </div>
                <p>(<?= $hover_conditions ?>)</p>
            <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
