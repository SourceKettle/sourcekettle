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
			SourceKettle system administrators have more options available to them
			in the admin section of the site. As an admin, you are able to:

			<ul>
			  <li>Configure global system options</li>
			  <li>Enable and disable system features</li>
			  <li>Edit the details of ANY project</li>
			  <li>Delete ANY project</li>
			  <li>Add and remove users to ANY project, or grant/revoke permissions</li>
			  <li>Create new users</li>
			  <li>Change any user's details, enable/disable accounts and grant/revoke admin status</li>
			</ul>
			</div>
            </div>
        </div>
    </div>
</div>
