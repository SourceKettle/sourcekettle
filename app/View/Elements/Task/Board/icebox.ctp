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

echo "<ul class='sprintboard-droplist'>\n";
foreach ($tasks as $task) {
    echo "<li>".$this->element('Task/element_1', array('task' => $task, 'draggable' => true))."</li>\n";
}
echo "</ul>\n";
