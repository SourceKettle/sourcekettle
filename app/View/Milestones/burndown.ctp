<?php
/**
 *
 * View class for APP/tasks/burndown for the SourceKettle system
 * Shows a burndown chart for a milestone
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2014
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Milestones
 * @since         SourceKettle v 1.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('tasks', null, array ('inline' => false));
$this->Html->css('milestones.index', null, array ('inline' => false));
?>
<div class="row-fluid">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row-fluid">
    	<?= $this->element('Milestone/topbar', array('name' => $milestone['Milestone']['subject'], 'id' => $milestone['Milestone']['id'])) ?>
		</div>

        <div class="row-fluid">
			<?=$this->element('burndown')?>
        </div>
        </div>
    </div>
</div>

