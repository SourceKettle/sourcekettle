<?php
/**
 *
 * View class for APP/help/create for the DevTrack system
 * Display the help page for creating new projects
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

echo $this->Bootstrap->page_header('HELP!'); ?>
  
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/help') ?>
    </div>
    <div class="span10">
        <div class="well">
          <h3>Your account password</h3>

          <p>
            To change your account password, you must be logged in.  Click on the dropdown at the top right of the screen and select 'Account Settings', then click 'Change Password'.
          </p>

          <p>
            Note that this option is only available for "internal" accounts - for more information about "internal" and "external" accounts, see the <?= $this->Html->link('account details help page', array('controller' => 'help', 'action' => 'details'))?>.
          </p>

        </div>
    </div>
</div>
