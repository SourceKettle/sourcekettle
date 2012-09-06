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
$config['dtcore']['pages']['milestones']['topbar']['en']['topbar.create.text'] = "Create Milestone";
$config['dtcore']['pages']['milestones']['topbar']['en']['topbar.edit.text'] = "Edit";
$config['dtcore']['pages']['milestones']['topbar']['en']['topbar.view.text'] = "View";
$config['dtcore']['pages']['milestones']['topbar']['en']['topbar.delete.text'] = "Delete";

// APP/Milestones/open
$config['dtcore']['pages']['milestones']['open']['en']['header.text'] = "Open Milestones";
$config['dtcore']['pages']['milestones']['open']['en']['block.progress.notasks.text'] = "no tasks in this milestone";
$config['dtcore']['pages']['milestones']['open']['en']['block.progress.open.text'] = " open";
$config['dtcore']['pages']['milestones']['open']['en']['block.progress.inprogress.text'] = " in progress";
$config['dtcore']['pages']['milestones']['open']['en']['block.progress.resolved.text'] = " resolved";
$config['dtcore']['pages']['milestones']['open']['en']['block.progress.closed.text'] = " closed";

// APP/Milestones/closed
$config['dtcore']['pages']['milestones']['closed']['en']['header.text'] = "Closed Milestones";


//APP/Milestones/view
$config['dtcore']['pages']['milestones']['view']['en']['header.text'] = "Milestone board";

$config['dtcore']['pages']['milestones']['view']['en']['column.backlog.title'] = "Backlog";
$config['dtcore']['pages']['milestones']['view']['en']['column.backlog.empty'] = "Woo! Empty Backlog!";

$config['dtcore']['pages']['milestones']['view']['en']['column.inprogress.title'] = "In Progress";
$config['dtcore']['pages']['milestones']['view']['en']['column.inprogress.empty'] = "Wait, there must be something to do?";

$config['dtcore']['pages']['milestones']['view']['en']['column.completed.title'] = "Completed";
$config['dtcore']['pages']['milestones']['view']['en']['column.completed.empty'] = "Nothing to see here!";

$config['dtcore']['pages']['milestones']['view']['en']['column.icebox.title'] = "Ice Box";
$config['dtcore']['pages']['milestones']['view']['en']['column.icebox.empty.line1'] = "It's pretty warm in here!";
$config['dtcore']['pages']['milestones']['view']['en']['column.icebox.empty.line2'] = "Add some tasks to the current milestone!";

//APP/Milestones/add
$config['dtcore']['pages']['milestones']['add']['en']['header.text'] = "New Milestone";

$config['dtcore']['pages']['milestones']['add']['en']['form.subject.label'] = "Short name";
$config['dtcore']['pages']['milestones']['add']['en']['form.subject.placeholder'] = "e.g. Sprint 1";
$config['dtcore']['pages']['milestones']['add']['en']['form.description.label'] = "Description";
$config['dtcore']['pages']['milestones']['add']['en']['form.description.placeholder'] = "e.g. Overall goals of the milestone";
$config['dtcore']['pages']['milestones']['add']['en']['form.due.label'] = "Completion target";
$config['dtcore']['pages']['milestones']['add']['en']['form.due.help'] = "When the milstone should be complete.";
$config['dtcore']['pages']['milestones']['add']['en']['form.submit'] = "Submit";

//APP/Milestones/edit
$config['dtcore']['pages']['milestones']['edit']['en']['header.text'] = "Edit a Milestone";

$config['dtcore']['pages']['milestones']['edit']['en']['form.subject.label'] = "Short name";
$config['dtcore']['pages']['milestones']['edit']['en']['form.subject.placeholder'] = "e.g. Sprint 1";
$config['dtcore']['pages']['milestones']['edit']['en']['form.description.label'] = "Description";
$config['dtcore']['pages']['milestones']['edit']['en']['form.description.placeholder'] = "e.g. Overall goals of the milestone";
$config['dtcore']['pages']['milestones']['edit']['en']['form.due.label'] = "Completion target";
$config['dtcore']['pages']['milestones']['edit']['en']['form.due.help'] = "When the milstone should be complete.";
$config['dtcore']['pages']['milestones']['edit']['en']['form.submit'] = "Submit";
