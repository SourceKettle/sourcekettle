<?php
/**
 *
 * View class for APP/tasks/sprint for the DevTrack system
 * Shows a list of tasks for a project
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

$this->Html->css('tasks.index', null, array ('inline' => false));
$this->Html->script ("milestones.index", array ('inline' => false));
$this->Html->script ("jquery-ui.min", array ('inline' => false));
$this->Html->script ("jquery.ui.touch-punch.min", array ('inline' => false));
$this->Html->script ("milestones.index", array ('inline' => false));
?>

<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>


    <?= $this->element('Milestone/topbar', array('name' => $milestone['Milestone']['subject'], 'id' => $milestone['Milestone']['id'])) ?>

    <!-- Milestone board -->
    <div class="span10">  <div class="row">

    <!-- Primary columns -->
	<div class="row-fluid span12">

        <div class="span4">
            <div class="well col sprintboard-column" data-taskstatus="open">
                <h2><?= $this->DT->t('column.backlog.title') ?></h2>
                <hr />
                <?= $this->element('Task/Board/column',
                    array('tasks' => $backlog)
                ) ?>
                </ul>
            </div>
        </div>

        <div class="span4">
            <div class="well col sprintboard-column" data-taskstatus="in_progress">
                <h2><?= $this->DT->t('column.inprogress.title') ?></h2>
                <hr />
                <?= $this->element('Task/Board/column',
                    array('tasks' => $inProgress)
                ) ?>
            </div>
        </div>

        <div class="span4">
            <div class="well col sprintboard-column" data-taskstatus="resolved">
                <h2><?= $this->DT->t('column.completed.title') ?></h2>
                <hr />
                <?= $this->element('Task/Board/column',
                  array('tasks' => $completed)
                ) ?>
            </div>
        </div>
	
	<!-- End primary columns -->
	</div>

    <!-- Icebox row -->
	<div class="row-fluid span12">

        <div class="span12">
            <div class="well col" data-taskstatus="on_ice">
                <h2><?= $this->DT->t('column.icebox.title') ?></h2>
                <hr />
                <?= $this->element('Task/Board/icebox',
                  array('tasks' => $iceBox)
                ) ?>
            </div>
        </div>

	<!-- End icebox -->
	</div>

    <!-- End milestone board -->
	</div> </div>





    </div>
</div>

