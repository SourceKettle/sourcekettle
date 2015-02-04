<?php
/**
 *
 * View class for APP/projects/index for the SourceKettle system
 * View will render a list of all the projects a user has access to
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Projects
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Html->css('projects.index', null, array ('inline' => false));
?>

<div class="row-fluid">
    <?= $this->Element("Project/topbar") ?>
</div>

<?= $this->Element("Project/projectgrid") ?>
