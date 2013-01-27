<?php
/**
 *
 * View class for APP/projects/history for the DevTrack system
 * Allows a user to view history for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Projects
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<?= $this->DT->pHeader() ?>
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
