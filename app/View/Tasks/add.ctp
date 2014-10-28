<?php
/**
 *
 * View class for APP/tasks/add for the SourceKettle system
 * Add a new task for a project
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
				$('#DependsOnDependsOn option:selected').removeAttr ('selected');
			});
		});
	", array ("inline" => false));

?>
<?= $this->DT->pHeader(__("Create a task")) ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <div class="span10">
				<?= $this->element('Task/add_edit') ?>
            </div>
        </div>
    </div>
</div>
