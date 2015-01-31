<?php
/**
 *
 * View class for APP/help/settings for the SourceKettle system
 * Display the help page for logging time
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Help
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('time.tempo', null, array ('inline' => false));
$this->Html->css('pages/help', null, array ('inline' => false));

echo $this->Bootstrap->page_header('Help! <small>How do I manage project settings?</small>'); ?>

<div class="row-fluid">
	<div class="span2">
		<?= $this->element('Sidebar/help') ?>
	</div>
	<div class="span10">
		<div class="well">
          <h3>Project settings</h3>
          <p>
            From this page, project administrators can edit the project's settings - you can't change the name, but you can update the description and toggle whether or not the project is public.  There is also a scary-looking <a href="#" class="btn btn-mini btn-danger">Delete this project</a> button - use with care!
          </p>

          <p>
            For more information about project settings, see the page on <a href='create'>Creating Projects</a>.
          </p>
		</div>
	</div>
</div>
