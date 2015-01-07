<?php
/**
 *
 * View class for APP/tasks/index for the SourceKettle system
 * Shows a list of tasks for a project
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

$this->Html->css('tasks', null, array ('inline' => false));
?>
<?= $this->Task->allDropdownMenus() ?>

<?= $this->DT->pHeader(__('Things to Do...')) ?>
<div class="row-fluid">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row-fluid">
            <?= $this->element('Task/topbar_filter') ?>
        </div>
		<div class="row-fluid well col">
            <h2><?=__("Task list")?></h2>
            <hr />
			<ul class="sprintboard-droplist" data-taskspan="4">
			  <? foreach ($tasks as $task) { ?>
			  <?= $this->element('Task/lozenge', array('task' => $task, 'span' => 4)) ?>
			  <? } ?>
			</ul>
		</div>
    </div>
</div>

