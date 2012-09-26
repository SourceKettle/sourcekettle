<?php
/**
 *
 * View class for APP/times/users for the DevTrack system
 * Shows a graph of user contribution to a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Times
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
        <?= $this->element('Time/topbar_history') ?>
        <div class="span10">
            <div class="row-fluid">
            <?php
                if (empty($users)) {
                    echo $this->element('Time/breakdown_empty');
                } else {
                    echo $this->element('Time/breakdown_full');
                }
            ?>
            </div>
        </div>
    </div>
</div>
