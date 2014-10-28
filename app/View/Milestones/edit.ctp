<?php
/**
 *
 * View class for APP/milestones/edit for the SourceKettle system
 * Allows a user to edit a task for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Milestones
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->Html->script('bootstrap-datepicker', array('block' => 'scriptBottom'));
$this->Html->scriptBlock("$('.dp1').datepicker()", array('inline' => false));
$this->Html->css('datepicker', null, array ('inline' => false));
$this->Html->css('milestones.index', null, array ('inline' => false));
?>

<?= $this->DT->pHeader(__("Edit a Milestone")) ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <?= $this->element('Milestone/topbar', array('name' => $milestone['Milestone']['subject'], 'id' => $this->request->data['Milestone']['id'])) ?>
            <div class="span10">

                <?= $this->element('Milestone/add_edit') ?>

            </div>
        </div>
    </div>
</div>

