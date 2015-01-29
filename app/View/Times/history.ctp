<?php
/**
 *
 * View class for APP/times/history for the SourceKettle system
 * Shows a graph of user contribution to a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Times
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->Html->script("times.history", array ('inline' => false));
?>

<?= $this->DT->pHeader(__("Timesheets")) ?>
<div class="row-fluid">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
		<div class="row-fluid">
        <?= $this->element('Time/topbar_history') ?>
		</div>
		<div class="row-fluid">
        <div class="span12">
			<? if (isset($user)) {
				echo __("Showing time log for $userName");
				echo " ";
				echo $this->Html->link(__("(show all users)"), array(
					'controller' => 'times',
					'action' => 'history',
					'year' => $thisYear,
					'week' => $thisWeek,
					'project' => $project['Project']['name']
				));
			} ?>
            <div class="row-fluid">
                <?= $this->element('Time/tempo') ?>
            </div>
        </div>
    </div>
</div>
