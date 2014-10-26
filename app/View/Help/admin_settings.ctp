<?php
/**
 *
 * View class for APP/help/admin_index for the SourceKettle system
 * View display help for the admin section of the site
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2014
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Help
 * @since         SourceKettle v1.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header('Help: System settings'); ?>
	
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/admin_help') ?>
    </div>
    <div class="span10">
        <div class="well">
            <div class="row">
			<div class="span10">
			  At SourceKettle, we try to keep things as simple as possible, so there aren't many settings
			  to change! The system wide configuration options are:
			  <ul>
			    <li><strong>Allow Registration: </strong>By default, any user who has an email address and can access SourceKettle can register themselves an account. If you would rather create accounts yourself (or you are using LDAP authentication only), turn this off.</li>
			    <li><strong>Admin email address: </strong>This should be the email address of a person or team who administers the SourceKettle system. Various pages have a link that uses this address; they generally say something like "if you are having a problem, contact your system administrator", so make sure somebody is checking the mailbox and can answer questions!</li>
			  </ul>

			  The following features can be enabled or disabled for all projects:
			  <ul>
			    <li><strong>Time tracking: </strong>This allows users to log the time they have spent on tasks or projects. Enabled by default.</li>
			    <li><strong>Source control: </strong>This allows projects to have associated source code management repositories (currently only git repositories are supported). You probably want this turned on. Enabled by default.</li>
			    <li><strong>Task management: </strong>This allows users to create tasks and milestones associated with a project, to track what needs to be done and what stage everything is at. Enabled by default.</li>
			    <li><strong>File uploads: </strong>This allows users to upload files to be associated with a project. Disabled by default.</li>
			  </ul>

			  Note that if you turn off all features, SourceKettle becomes basically useless! The defaults should be sensible for most installations.
			</div>
            </div>
        </div>
    </div>
</div>
