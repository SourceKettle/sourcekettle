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
$this->Html->css("milestones.index", null, array ('inline' => false));
$this->Html->script("jquery-ui.min", array ('inline' => false));
$this->Html->script("jquery.ui.touch-punch.min", array ('inline' => false));
$this->Html->script("milestones.plan", array ('inline' => false));
?>

<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>


    <?= $this->element('Milestone/topbar', array('name' => $milestone['Milestone']['subject'], 'id' => $milestone['Milestone']['id'])) ?>

    <!-- Planner board -->
    <div class="span10">  <div class="row">

    <!-- Unattached row -->
	<div class="row-fluid span12">
        <?= $this->element('Task/Board/column',
            array('tasks' => $wontHave, 'status' => 'detach', 'title' => __('Project backlog'), 'span' => '12', 'classes' => 'sprintboard-icebox')
        ) ?>

	<!-- End project backlog -->
	</div>


    <!-- Must-have, should-have -->
	<div class="row-fluid span12">

        <?= $this->element('Task/Board/column',
            array('tasks' => $mustHave, 'priority' => 'blocker', 'title' => __('Must have'), 'span' => '6', 'classes' => 'sprintboard-column')
        ) ?>	

        <?= $this->element('Task/Board/column',
            array('tasks' => $shouldHave, 'priority' => 'urgent', 'title' => __('Should have'), 'span' => '6', 'classes' => 'sprintboard-column')
        ) ?>

	<!-- End must-have/should-have -->
	</div>

    <!-- Could-have, won't-have -->
	<div class="row-fluid span12">

        <?= $this->element('Task/Board/column',
            array('tasks' => $couldHave, 'priority' => 'major', 'title' => __('Could have'), 'span' => '6', 'classes' => 'sprintboard-column')
        ) ?>	

        <?= $this->element('Task/Board/column',
            array('tasks' => $mightHave, 'priority' => 'minor', 'title' => __('Might have'), 'span' => '6', 'classes' => 'sprintboard-column')
        ) ?>

	<!-- End could-have/won't-have -->
	</div>


    <!-- End milestone board -->
	</div> </div>

</div>

