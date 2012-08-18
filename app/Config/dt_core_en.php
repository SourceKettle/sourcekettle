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
 * Configuration for the tasks section
 * APP/Tasks
 */
// APP/Tasks/index
$config['dtcore']['pages']['tasks']['index']['en']['header.text'] = "Tasks for the Project";

$config['dtcore']['pages']['tasks']['index']['en']['topbar.option1.text'] = "Sprint Board";
$config['dtcore']['pages']['tasks']['index']['en']['topbar.option2.text'] = "Classic Board";
$config['dtcore']['pages']['tasks']['index']['en']['topbar.option3.text'] = "Create Task";
$config['dtcore']['pages']['tasks']['index']['en']['topbar.option4.text'] = "Milestones";

$config['dtcore']['pages']['tasks']['index']['en']['column.backlog.title'] = "Backlog";
$config['dtcore']['pages']['tasks']['index']['en']['column.backlog.empty'] = "Woo! Empty Backlog!";

$config['dtcore']['pages']['tasks']['index']['en']['column.inprogress.title'] = "In Progress";
$config['dtcore']['pages']['tasks']['index']['en']['column.inprogress.empty'] = "Wait, there must be something to do?";

$config['dtcore']['pages']['tasks']['index']['en']['column.completed.title'] = "Completed";
$config['dtcore']['pages']['tasks']['index']['en']['column.completed.empty'] = "Nothing to see here!";

$config['dtcore']['pages']['tasks']['index']['en']['column.icebox.title'] = "Completed";
$config['dtcore']['pages']['tasks']['index']['en']['column.icebox.empty.line1'] = "It's pretty warm in here!";
$config['dtcore']['pages']['tasks']['index']['en']['column.icebox.empty.line2'] = "Add some tasks to the current milestone!";

// APP/Tasks/add
$config['dtcore']['pages']['tasks']['add']['en']['header.text'] = "Add a new Task for the Project";

$config['dtcore']['pages']['tasks']['add']['en']['modal.header.text'] = "Add a new task to this Project";
$config['dtcore']['pages']['tasks']['add']['en']['modal.header.subtext'] = "Task will appear in the Ice Box if no milestone is set";

$config['dtcore']['pages']['tasks']['add']['en']['modal.form.subject.label'] = "Title";
$config['dtcore']['pages']['tasks']['add']['en']['modal.form.subject.placeholder'] = "Quick, yet informative, description";
$config['dtcore']['pages']['tasks']['add']['en']['modal.form.description.placeholder'] = "Longer and more descriptive explanation...";

$config['dtcore']['pages']['tasks']['add']['en']['modal.form.close'] = "Close";
$config['dtcore']['pages']['tasks']['add']['en']['modal.form.submit'] = "Submit";

// APP/Tasks/edit
$config['dtcore']['pages']['tasks']['edit']['en']['header.text'] = "Edit a task for the Project";

// APP/Tasks/view
$config['dtcore']['pages']['tasks']['view']['en']['header.text'] = "A task for the Project";
