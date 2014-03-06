<?php
/**
 *
 * View class for APP/help/admin_index for the DevTrack system
 * View display help for the admin section of the site
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
        <?= $this->element('Sidebar/admin_help') ?>
    </div>
    <div class="span10">
        <div class="well">
            <div class="row">
			<div class="span10">
			<p>
			The "add user" page will allow you to manually add users to the system. If registration is disabled, then this is the only way that new users can be added.
			</p>
			<p>
			You will need to provide a name and email address, plus the following two settings:
			</p>
			<ul>
			  <li><strong>System Admin:</strong> Tick this box if the new user should be be a system administrator, i.e. they can use the system-wide admin pages</li>
			  <li><strong>Account Active:</strong> Untick this box if the account should be inactive, i.e. the account <em>cannot be used</em>. Usually you should leave this box ticked!</li>
			</ul>
			</div>
            </div>
        </div>
    </div>
</div>
