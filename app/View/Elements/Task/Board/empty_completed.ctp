<?php
/**
 *
 * Element for APP/tasks/index for the DevTrack system
 * Shows a empty box for completed column
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements.Task
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="task empty invisiblewell">
    <h3><small>Nothing to see here!</small></h3>
</div>
<? while ($e-- > 1) : ?>
<div class="invisiblewell"></div>
<? endwhile; ?>
