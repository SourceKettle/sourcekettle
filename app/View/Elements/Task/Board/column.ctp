<?php
/**
 *
 * Element for APP/tasks/index for the DevTrack system
 * Shows a task box for a task
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Task
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

if(!isset($classes)){
	$classes = '';
} else{
	$classes = " $classes";
}
if(!isset($task_span)){
	$task_span = False;
}

$dtStatus = '';
if(isset($status)){
	$dtStatus = 'data-taskstatus="'.$status.'"';
}

$dtPriority = '';
if(isset($priority)){
	$dtPriority = 'data-taskpriority="'.$priority.'"';
}

echo "<ul class='well col sprintboard-droplist span$span$classes' $dtStatus $dtPriority>\n";
echo "<h2>$title</h2>\n";
echo "<hr />\n";
foreach ($tasks as $task) {
	$draggable = (empty($task['Task']['assignee_id']) || $task['Task']['assignee_id'] == $user_id);
	if($draggable){
		echo "<li class='draggable' data-taskid='".h($task['Task']['id'])."'>";
	} else {
		echo "<li>";
	}
    echo $this->element('Task/element_1', array('task' => $task, 'draggable' => true, 'span' => $task_span))."</li>\n";
}
echo "</ul>\n";
