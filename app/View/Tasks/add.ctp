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

$this->Html->css('tasks.add', null, array ('inline' => false));
$this->Html->scriptBlock ("
		jQuery(function() {
			$('#unselect-all').click (function() {
				$('#DependsOnDependsOn option:selected').removeAttr ('selected');
			});
		});
	", array ("inline" => false));

?>
<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <?= $this->element('Task/topbar_add') ?>
            <div class="span10">
				<?= $this->element('Task/add_edit') ?>
            </div>
        </div>
    </div>
</div>
