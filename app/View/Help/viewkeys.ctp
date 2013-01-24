<?php
/**
 *
 * View class for APP/help/viewkeys for the DevTrack system
 * Display the help page for logging time
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

$this->Html->css('time.tempo', null, array ('inline' => false));
$this->Html->css('pages/help', null, array ('inline' => false));

echo $this->Bootstrap->page_header('Help! <small>How do I manage my SSH keys?</small>'); ?>

<div class="row">
	<div class="span2">
		<?= $this->element('Sidebar/help') ?>
	</div>
	<div class="span10">
		<div class="well">
          <h3> Changing SSH keys</h3>
          <p>
            Given that SSH keys are just a line of text, there's not much point in being able to edit them individually - so, we only allow delete functionality.
          </p>

          <p>
            On the "Edit Keys" page, you will see a list of your SSH keys, with an option to delete each one.  If something Bad has happened, such as somebody stealing your private key, or you forgot the passphrase, or you deleted the private key and didn't have a backup, this page lets you erase all trace of the key.
          </p>

          <p>
            As when adding keys, this will take a little time to take effect as the keys are synced periodically (usually every minute or two).
          </p>
		</div>
	</div>
</div>
