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
 * Configuration for the projects section
 * APP/Projects
 */

$config['dtcore']['pages']['projects']['all']['en']['noprojects.text'] = "<strong>No projects :(</strong> Why don't you create one?";

// APP/Projects/view
$config['dtcore']['pages']['projects']['view']['en']['header.text'] = "Project overview";

$config['dtcore']['pages']['projects']['view']['en']['summary.issues.title'] = "Tasks";
$config['dtcore']['pages']['projects']['view']['en']['summary.issues.open'] = "Open Tasks";
$config['dtcore']['pages']['projects']['view']['en']['summary.issues.closed'] = "Closed Tasks";
$config['dtcore']['pages']['projects']['view']['en']['summary.issues.total'] = "Total Tasks";
$config['dtcore']['pages']['projects']['view']['en']['summary.issues.percent'] = "complete";

// APP/Projects/history
$config['dtcore']['pages']['projects']['history']['en']['header.text'] = "What's been happening lately?";

// APP/Projects/index and APP/Projects/public_projects
$config['dtcore']['pages']['projects']['topbar']['en']['topbar.index.text'] = "My projects";
$config['dtcore']['pages']['projects']['topbar']['en']['topbar.public.text'] = "Public projects";
$config['dtcore']['pages']['projects']['topbar']['en']['topbar.create.text'] = "New Project";