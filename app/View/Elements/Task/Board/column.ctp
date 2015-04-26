<?php
/**
 *
 * Element for APP/tasks/index for the SourceKettle system
 * Shows a task box for a task
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Task
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

if (!isset($classes)) {
	$classes = '';
} else {
	$classes = " $classes";
}
if (!isset($task_span)) {
	$task_span = 12;
}

$dtStatus = '';
if (isset($status)) {
	$dtStatus = 'data-taskstatus="'.h($status).'"';
}

$dtPriority = '';
if (isset($priority)) {
	$dtPriority = 'data-taskpriority="'.h($priority).'"';
}

$dtMilestone = '';
if (isset($milestoneID)) {
	$dtMilestone = 'data-milestone="'.h($milestoneID).'"';
}

if (isset($tooltip)) {
	$tooltip = " title=\"$tooltip\"";
} else {
	$tooltip = "";
}

if (isset($icon)) {
	$icon = '<i class="icon='.h($icon).'"></i> ';
} else {
	$icon = "";
}

$localStoryLink = isset($localStoryLink)?: false;

echo "<ul class='well span$span sprintboard-droplist $classes' data-taskspan='$task_span' $dtStatus $dtPriority $dtMilestone>\n";
echo "<h2$tooltip>$icon$title</h2>\n";
echo "<hr />\n";
foreach ($tasks as $task) {
    echo $this->element('Task/lozenge', array('task' => $task, 'draggable' => $draggable, 'span' => $task_span, 'localStoryLink' => $localStoryLink));
}
if (isset($addLink) && $addLink) {
	$params = array();
	if (isset($status)) {
		$params['status'] = $status;
	}
	if (isset($priority)) {
		$params['priority'] = $priority;
	}
	if (isset($milestoneID)) {
		$params['milestone'] = $milestoneID;
	}
	echo $this->Html->link('add task', array('controller' => 'tasks', 'action' => 'add', 'project' => $project['Project']['name'], '?' => $params));
}
echo "</ul>\n";
