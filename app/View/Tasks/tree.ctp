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

$this->Html->css('tasks.view', null, array ('inline' => false));
$this->Html->script("tasks.view", array ('inline' => false));
?>

<?= $this->DT->pHeader(__("Task dependency tree")) ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
    <div class="span10">
        <div class="row">
            <?//= $this->element('Task/topbar_view', array('id' => $tree['public_id'], 'dependenciesComplete' => $tree['dependenciesComplete'])) ?>
            <div class="span10">

                <div class="row-fluid">
				<?=$this->Task->treeRender($project['Project']['name'], $tree)?>
                </div>

            </div>
        </div>
    </div>
</div>
