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
 * @link          http://github.com/chrisbulmer/devtrack
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
          <h3>Your account details</h3>

          <p>
            To edit your account details, you must be logged in.  Click on the dropdown at the top right of the screen and select 'Account Settings'.
          </p>

          <h4>Internal vs. External accounts</h4>
          <p>
            DevTrack has its own user account system ("internal accounts"), but can also be integrated with your organisation's existing account system if you have one ("external accounts").
          </p>

          <p>
            If you are using an "external" account, some settings cannot be changed - the email address and password are not managed within DevTrack, so you do not have the option to change them.
          </p>

          <h4>Your name</h4>
          <p>
            You can customise this how you like, it does not affect the login process.  When you log in with an external account for the first time, the name will be set to something sensible based on your organisational account.
          </p>

          <h4>Email address</h4>
          <p>If you have an "internal" account, you may change your email address with a few caveats:
            <ul>
              <li>You can't set it to an address somebody else is using</li>
              <li>The next time you log in, you will have to use the <strong>new</strong> address - don't forget!</li>
            </ul>
          </p>

        </div>
    </div>
</div>
