<?php
/**
 *
 * View class for APP/settings/admin_index for the SourceKettle system
 * View will render system wide settings
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Settings
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('pages/settings', null, array ('inline' => false));
?>

<div class="row-fluid">
    <?= $this->element('Setting/admin_global') ?>
    <?= $this->element('Setting/admin_ldap') ?>
    <?= $this->element('Setting/admin_features') ?>
    <?= $this->element('Setting/admin_defaults') ?>
    <?= $this->element('Setting/admin_interface') ?>
    <?= $this->element('Setting/admin_repo') ?>
</div>
