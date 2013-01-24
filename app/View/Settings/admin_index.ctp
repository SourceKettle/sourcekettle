<?php
/**
 *
 * View class for APP/settings/admin_index for the DevTrack system
 * View will render system wide settings
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Settings
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('pages/settings', null, array ('inline' => false));
?>

<?= $this->Bootstrap->page_header('System-wide configuration') ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/admin') ?>
    </div>
    <div class="span10">
        <div class="row">
            <!-- topbar -->
            <div class="span10">
                <div class="row-fluid">
                    <?= $this->element('Setting/admin_global') ?>
                    <?= $this->element('Setting/admin_features') ?>
                </div>
            </div>
        </div>
    </div>
</div>
