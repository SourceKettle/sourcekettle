<?php
/**
 *
 * ADVANCED USERS ONLY
 * Do NOT configure this file unless you know what you are doing.
 * Editing this file incorrectly could reduce the stability of the system.
 *
 * tl;dr Here be dragons.
 *
 * Core Configuration file for the DevTrack system
 * Provides the core config for the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.Config
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/*
 * Configuration for the settings section
 * APP/Settings
 */
// APP/Settings/admin_index
$config['dtcore']['pages']['settings']['admin_index']['en']['table.col1'] = "Description";
$config['dtcore']['pages']['settings']['admin_index']['en']['table.col2'] = "Options";

$config['dtcore']['pages']['settings']['admin_index']['en']['global.header.text'] = "System wide configuration options";
$config['dtcore']['pages']['settings']['admin_index']['en']['global.register.text'] = "Allow Registration";
$config['dtcore']['pages']['settings']['admin_index']['en']['global.register.description'] = "allow for new users to create accounts";
$config['dtcore']['pages']['settings']['admin_index']['en']['global.email.text'] = "Admin email address";
$config['dtcore']['pages']['settings']['admin_index']['en']['global.email.description'] = "where all emails come from";

$config['dtcore']['pages']['settings']['admin_index']['en']['global.ldap.text'] = "Enable LDAP authentication";
$config['dtcore']['pages']['settings']['admin_index']['en']['global.ldap.description'] = "authenticate users against LDAP";
$config['dtcore']['pages']['settings']['admin_index']['en']['global.ldap_url.text'] = "LDAP URL";
$config['dtcore']['pages']['settings']['admin_index']['en']['global.ldap_url.description'] = "URL of your LDAP server, e.g. ldaps://ldap.example.com";
$config['dtcore']['pages']['settings']['admin_index']['en']['global.ldap_base_dn.text'] = "LDAP base DN";
$config['dtcore']['pages']['settings']['admin_index']['en']['global.ldap_base_dn.description'] = "base DN for searching the LDAP tree";
$config['dtcore']['pages']['settings']['admin_index']['en']['global.ldap_filter.text'] = "LDAP search filter";
$config['dtcore']['pages']['settings']['admin_index']['en']['global.ldap_filter.description'] = "filter for locating user accounts: %USERNAME% will be replaced with the username e.g. 'mail=%USERNAME%'";
$config['dtcore']['pages']['settings']['admin_index']['en']['global.ldap_bind_dn.text'] = "LDAP bind DN";
$config['dtcore']['pages']['settings']['admin_index']['en']['global.ldap_bind_dn.description'] = "account to bind to LDAP with, or blank for anonymous binds";
$config['dtcore']['pages']['settings']['admin_index']['en']['global.ldap_bind_pw.text'] = "LDAP bind password";
$config['dtcore']['pages']['settings']['admin_index']['en']['global.ldap_bind_pw.description'] = "password to bind to LDAP with, or blank for anonymous binds";

$config['dtcore']['pages']['settings']['admin_index']['en']['projects.header.text'] = "Global project control";
$config['dtcore']['pages']['settings']['admin_index']['en']['projects.warning'] = "<strong>Warning!</strong> modifying these settings will restrict <strong>ALL</strong> projects, not just new ones.";

$config['dtcore']['pages']['settings']['admin_index']['en']['projects.time.text'] = "Time tracking";
$config['dtcore']['pages']['settings']['admin_index']['en']['projects.time.description'] = "allow users to track time spent on projects";
$config['dtcore']['pages']['settings']['admin_index']['en']['projects.source.text'] = "Source control";
$config['dtcore']['pages']['settings']['admin_index']['en']['projects.source.description'] = "allow users to use attached repositories";
$config['dtcore']['pages']['settings']['admin_index']['en']['projects.task.text'] = "Task management";
$config['dtcore']['pages']['settings']['admin_index']['en']['projects.task.description'] = "allow users to add tasks and milestones to track progress";
$config['dtcore']['pages']['settings']['admin_index']['en']['projects.attachment.text'] = "File uploads";
$config['dtcore']['pages']['settings']['admin_index']['en']['projects.attachment.description'] = "allow users to upload files to projects (database maintained)";

