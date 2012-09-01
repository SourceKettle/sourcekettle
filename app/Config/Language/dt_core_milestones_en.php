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
 * Configuration for the milestones section
 * APP/Milestones
 */
// APP/*/topbar
$config['dtcore']['pages']['milestones']['topbar']['en']['topbar.option1.text'] = "Open Milestones";
$config['dtcore']['pages']['milestones']['topbar']['en']['topbar.option2.text'] = "Closed Milestones";

// APP/Milestones/open
$config['dtcore']['pages']['milestones']['open']['en']['header.text'] = "Open Milestones";
$config['dtcore']['pages']['milestones']['open']['en']['block.progress.notasks.text'] = "no tasks in this milestone";
$config['dtcore']['pages']['milestones']['open']['en']['block.progress.open.text'] = " open";
$config['dtcore']['pages']['milestones']['open']['en']['block.progress.inprogress.text'] = " in progress";
$config['dtcore']['pages']['milestones']['open']['en']['block.progress.resolved.text'] = " resolved";
$config['dtcore']['pages']['milestones']['open']['en']['block.progress.closed.text'] = " closed";

// APP/Milestones/closed
$config['dtcore']['pages']['milestones']['closed']['en']['header.text'] = "Closed Milestones";
