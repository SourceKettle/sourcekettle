<?php
/**
 *
 * Renders a message saying the user does not have any projects
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Project
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="alert alert-info<?if(isset($span)){ echo ' span3';}?>">
    <?= __('<strong>No projects :(</strong> Why don\'t you create one?', array('action' => 'all', 'controller' => 'projects')) ?>
</div>
