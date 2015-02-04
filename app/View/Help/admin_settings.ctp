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
?>
	
<div class="well">
		  At SourceKettle, we try to keep things as simple as possible, so there aren't many settings
		  to change!
		  <h3>System-wide configuration</h3>
		  <ul>
		    <li><strong>Allow Registration: </strong>By default, any user who has an email address and can access SourceKettle can register themselves an account. If you would rather create accounts yourself (or you are using LDAP authentication only), turn this off.</li>
		    <li><strong>Support email address: </strong>This should be the email address of a person or team who administers the SourceKettle system. Various pages have a link that uses this address; they generally say something like "if you are having a problem, contact your system administrator", so make sure somebody is checking the mailbox and can answer questions!</li>
		    <li><strong>Sending email address: </strong>This email address will be used as the sender when emailing users - it does not have to be the same as the support email address.</li>
		    <li><strong>System alias: </strong>Your installation of SourceKettle doesn't have to be called SourceKettle - this name will be used instead throughout the system.</li>
		  </ul>

		  <h3>LDAP settings</h3>
		  If you have an LDAP-based authentication system, such as Active Directory or OpenLdap, you can use it to authenticate users. SourceKettle identifies users by email address, so there must be at least one email address field available to search for.
		  <ul>
		  	<li><strong>Enable LDAP authentication: </strong>Should SourceKettle use LDAP authentication? (NB if the other settings are invalid LDAP will be effectively disabled anyway)</li>
		  	<li><strong>LDAP server URL: </strong>The URL of the LDAP server - you should definitely use an ldaps:// URL here so the connection is encrypted</li>
		  	<li><strong>Base DN: </strong>The base DN for searching under - usually something like OU=User,dc=example,dc=com</li>
		  	<li><strong>LDAP filter: </strong>Filter for finding user accounts. %USERNAME% will be replaced with the user's email address (as we use email addresses as the username). Note that if users have multiple email addresses for the same account, you can specify a filter which will match any of them.</li>
		  	<li><strong>Bind DN: </strong>If your LDAP system does not allow anonymous lookups to find user DNs, supply a DN that has access to search the directory</li>
		  	<li><strong>Bind password: </strong>...and a password for the bind DN</li>
		  </ul>

		  <h3>Features</h3>
		  SourceKettle allows various features for projects, which can be independently enabled/disabled on a per-project basis or system-wide. If a feature is locked, this will prevent individual projects from overriding the setting (so for instance if you do not wish to allow uploads, disable uploads and lock the setting; however if you do not wish to enable uploads by default, just disable it, and if a project administrator wants to use the feature they can enable it themselves).
		  <ul>
		    <li><strong>Time tracking: </strong>This allows users to log the time they have spent on tasks or projects. Enabled by default.</li>
		    <li><strong>Source control: </strong>This allows projects to have associated source code management repositories (currently only git repositories are supported). You probably want this turned on. Enabled by default.</li>
		    <li><strong>Task management: </strong>This allows users to create tasks and milestones associated with a project, to track what needs to be done and what stage everything is at. Enabled by default.</li>
		    <li><strong>File uploads: </strong>This allows users to upload files to be associated with a project. Disabled by default.</li>
		  </ul>

		  Note that if you turn off all features, SourceKettle becomes basically useless! The defaults should be sensible for most installations.

		  <h3>User interface settings</h3>
		  These settings change the look and feel of the system.
		  <ul>
		    <li><strong>Theme: </strong>The general look and feel, pick a theme provided by BootSwatch. You can also lock the theme to override user preferences.</li>
		    <!--<li><strong>Terminology: </strong> (currently not implemented) Change the agile terminology used on project pages</li>-->
		  </ul>

		  <h3>Source repository settings</h3>
		  <strong>Caution:</strong> These settings will cause a lot of trouble if you mess with them after installation, so please make sure you know what you're doing!
		  <ul>
		    <li><strong>Repository location: </strong>The location on disk where your source repositories are stored</li>
		    <li><strong>SSH user: </strong>The username for remote SSH access to source repositories</li>
		  </ul>
</div>
