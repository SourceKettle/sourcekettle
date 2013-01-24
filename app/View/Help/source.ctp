<?php
/**
 *
 * View class for APP/help/source for the DevTrack system
 * Display the help page for viewing source code
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Help
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('pages/help', null, array ('inline' => false));

echo $this->Bootstrap->page_header('Help! <small>Viewing attached source code</small>'); ?>

<div class="row">
	<div class="span2">
		<?= $this->element('Sidebar/help') ?>
	</div>
	<div class="span10">
		<div class="well">
			<h3>Viewing source code</h3>
			<p>To start viewing the source code that is attached to your project, select '<a href="#"><i class="icon-pencil"></i> Source</a>' from the projects sidebar. It's as simple as that! From here you can traverse the folder hierarchy of your repository.</p>
		</div>
	</div>
</div>
