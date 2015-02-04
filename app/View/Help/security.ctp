<?php
/**
 *
 * View class for APP/help/create for the SourceKettle system
 * Display the help page for creating new projects
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
?>
  
<div class="well">
  <h3>Your account password</h3>

  <p>
    To change your account password, you must be logged in.  Click on the dropdown at the top right of the screen and select 'Account Settings', then click 'Change Password'.
  </p>

  <p>
    Note that this option is only available for "internal" accounts - for more information about "internal" and "external" accounts, see the <?= $this->Html->link('account details help page', array('controller' => 'help', 'action' => 'details'))?>.
  </p>

</div>
