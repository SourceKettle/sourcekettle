<?php
/**
 *
 * View class for APP/projects/history for the SourceKettle system
 * Allows a user to view history for a project
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
?>

<?= $this->DT->pHeader(__("Project history")) ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
    <div class="span10">
        <div class="row">
            <div class="span10">

                <div class="row-fluid">

                    <div class="span12">
                        <?= $this->element('history_ajax', array('no_more'=>true)) ?>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
