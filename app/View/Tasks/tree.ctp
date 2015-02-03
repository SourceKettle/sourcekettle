<?php
/**
 *
 * View class for APP/tasks/tree for the SourceKettle system
 * Allows a user to view a task dependency tree for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2014
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Tasks
 * @since         SourceKettle v 1.5
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('tasks', null, array ('inline' => false));
$this->Html->script("tasks", array ('inline' => false));
?>

<?= $this->DT->pHeader(__("Task dependency tree")) ?>
<div class="row-fluid">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
		<?=$this->Task->treeRender($project['Project']['name'], $tree)?>
    </div>
</div>
