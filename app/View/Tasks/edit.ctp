<?php
/**
 *
 * View class for APP/tasks/edit for the SourceKettle system
 * Allows a user to edit a task for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Tasks
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('tasks.add', null, array ('inline' => false));
$this->Html->scriptBlock ("
    jQuery(function() {
        $('#unselect-all').click (function() {
            // Fix chrome rendering issue
            var css = $('#DependsOnDependsOn option:selected').css('display');
            $('#DependsOnDependsOn option:selected').removeAttr ('selected').css('display', css);
        });
    });
", array ("inline" => false));

?>

<?= $this->DT->pHeader(__("Edit a task")) ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <?= $this->element('Task/topbar_edit', array('id' => $this->request->data['Task']['public_id'])) ?>
            <div class="span10">
				<?= $this->element('Task/add_edit') ?>
            </div>
        </div>
    </div>
</div>
