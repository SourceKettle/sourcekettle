<?php
/**
 *
 * View class for APP/tasks/chart for the SourceKettle system
 * Shows a gantt chart for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2014
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Projects
 * @since         SourceKettle v 1.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('tasks.index', null, array ('inline' => false));
$this->Html->css('projects.index', null, array ('inline' => false));
?>
<?= $this->DT->pHeader(__("Milestone schedule")) ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
		<div class="row">
        <?= $this->element('Project/topbar_charts') ?>
		</div>
        <div class="row">

            <div class="span10">
				<?=$this->element('gantt')?>

            </div>
        </div>
    </div>
</div>

