<?php
/**
 *
 * View class for APP/Common/sidebar_layout_admin for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Common
 * @since         SourceKettle v 1.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->extend('/Common/sidebar_layout');

$this->assign('sidebar', $this->element('Sidebar/admin'));

echo $this->fetch('content');
