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

$config['dtcore']['pages']['times']['history']['en']['tempo.table.task.text'] = "Task";
$config['dtcore']['pages']['times']['history']['en']['tempo.table.total.text'] = "Total";
$config['dtcore']['pages']['times']['history']['en']['tempo.table.week.text'] = "Week ";

$config['dtcore']['pages']['times']['history']['en']['tempo.modal.description.header'] = "Description";
$config['dtcore']['pages']['times']['history']['en']['tempo.modal.time.header'] = "Time";
$config['dtcore']['pages']['times']['history']['en']['tempo.modal.add'] = "Add Time";
$config['dtcore']['pages']['times']['history']['en']['tempo.modal.close'] = "Close";

// APP/Time/users
$config['dtcore']['pages']['times']['users']['en']['header.text'] = "Time breakdown for users on the project";

$config['dtcore']['pages']['times']['users']['en']['pie.header'] = "Time Contribution";
$config['dtcore']['pages']['times']['users']['en']['pie.total'] = "({hours} hours {mins} mins total)";

$config['dtcore']['pages']['times']['users']['en']['table.header.user'] = "User";
$config['dtcore']['pages']['times']['users']['en']['table.header.time'] = "Total Time";

// APP Time/edit
$config['dtcore']['pages']['times']['edit']['en']['header.text'] = "Edit some time for the Project";

$config['dtcore']['pages']['times']['edit']['en']['bar.time'] = "Time #";
$config['dtcore']['pages']['times']['edit']['en']['bar.view'] = "View";
$config['dtcore']['pages']['times']['edit']['en']['bar.delete'] = "Delete";


// APP Time/view
$config['dtcore']['pages']['times']['view']['en']['header.text'] = "Edit some time for the Project";

$config['dtcore']['pages']['times']['view']['en']['bar.time'] = "Time #";
$config['dtcore']['pages']['times']['view']['en']['bar.edit'] = "Edit";
$config['dtcore']['pages']['times']['view']['en']['bar.delete'] = "Delete";

$config['dtcore']['pages']['times']['view']['en']['info.time.logged'] = "Time Logged";
$config['dtcore']['pages']['times']['view']['en']['info.time.description'] = "Description";
$config['dtcore']['pages']['times']['view']['en']['info.time.date'] = "Date";
$config['dtcore']['pages']['times']['view']['en']['info.time.created'] = "Created By";

// APP Time/add
$config['dtcore']['pages']['times']['add']['en']['header.text'] = "to the nearest 30 mins, please";
