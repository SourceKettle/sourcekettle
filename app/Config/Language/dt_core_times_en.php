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
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.Config
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/*
 * Configuration for the time section
 * APP/Times
 */
// APP/*/topbar
$config['dtcore']['pages']['times']['topbar']['en']['topbar.create.text'] = "Log Time";
$config['dtcore']['pages']['times']['topbar']['en']['topbar.history.text'] = "History";
$config['dtcore']['pages']['times']['topbar']['en']['topbar.users.text'] = "User Statistics";

// APP/Time/history
$config['dtcore']['pages']['times']['history']['en']['header.text'] = "Recent time logged to the project";