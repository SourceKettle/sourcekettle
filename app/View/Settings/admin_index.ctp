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
$this->extend('/Common/sidebar_layout_admin');

$this->assign('title', $this->Bootstrap->page_header('Administration <small>System-wide configuration</small>'));
$this->Html->css('pages/settings', null, array ('inline' => false));

?>
<div class="row-fluid">
	<?php
		echo $this->element('Setting/admin_global');
		echo $this->element('Setting/admin_features');
	?>
</div>
