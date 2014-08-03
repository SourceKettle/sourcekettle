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

echo "<ul class='sprintboard-droplist'>\n";
foreach ($tasks as $task) {
    echo "<li>".$this->element('Task/lozenge', array('task' => $task, 'draggable' => true))."</li>\n";
}
echo "</ul>\n";
