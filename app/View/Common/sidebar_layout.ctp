<?php
/**
 *
 * View class for APP/Common/sidebar_layout for the SourceKettle system
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
echo $this->fetch('title'); ?>
<div class="row">
	<div class="span2">
		<?php echo $this->fetch('sidebar'); ?>
	</div>
	<div class="row">
		<div class="span10">
			 <?php echo $this->fetch('content'); ?>
		</div>
	</div>
</div>
