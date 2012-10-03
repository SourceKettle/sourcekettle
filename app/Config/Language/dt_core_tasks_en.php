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
// APP/*/topbar
$config['dtcore']['pages']['tasks']['topbar']['en']['topbar.index.text'] = "Assigned to you";
$config['dtcore']['pages']['tasks']['topbar']['en']['topbar.others.text'] = "Assigned to others";
$config['dtcore']['pages']['tasks']['topbar']['en']['topbar.nobody.text'] = "Assigned to nobody";
$config['dtcore']['pages']['tasks']['topbar']['en']['topbar.all.text'] = "All tasks";
$config['dtcore']['pages']['tasks']['topbar']['en']['topbar.option3.text'] = "Watching";
$config['dtcore']['pages']['tasks']['topbar']['en']['topbar.create.text'] = "Create Task";

// APP/Tasks/index
$config['dtcore']['pages']['tasks']['index']['en']['header.text'] = "My Tasks for the Project";

$config['dtcore']['pages']['tasks']['index']['en']['column.tasks.empty'] = "There are currently no tasks";

$config['dtcore']['pages']['tasks']['index']['en']['column.options.statuses.open'] = "Open";
$config['dtcore']['pages']['tasks']['index']['en']['column.options.statuses.progress'] = "In Progress";
$config['dtcore']['pages']['tasks']['index']['en']['column.options.statuses.resolved'] = "Resolved";
$config['dtcore']['pages']['tasks']['index']['en']['column.options.statuses.closed'] = "Closed";
$config['dtcore']['pages']['tasks']['index']['en']['column.options.milestone.title'] = "Milestone";
$config['dtcore']['pages']['tasks']['index']['en']['column.history.title'] = "Recent History";

// APP/Tasks/others
$config['dtcore']['pages']['tasks']['others']['en']['header.text'] = "Others' Tasks for the Project";

$config['dtcore']['pages']['tasks']['others']['en']['column.tasks.empty'] = "There are currently no tasks";

$config['dtcore']['pages']['tasks']['others']['en']['column.options.statuses.open'] = "Open";
$config['dtcore']['pages']['tasks']['others']['en']['column.options.statuses.progress'] = "In Progress";
$config['dtcore']['pages']['tasks']['others']['en']['column.options.statuses.resolved'] = "Resolved";
$config['dtcore']['pages']['tasks']['others']['en']['column.options.statuses.closed'] = "Closed";
$config['dtcore']['pages']['tasks']['others']['en']['column.options.milestone.title'] = "Milestone";
$config['dtcore']['pages']['tasks']['others']['en']['column.history.title'] = "Recent History";

// APP/Tasks/nobody
$config['dtcore']['pages']['tasks']['nobody']['en']['header.text'] = "Unassigned Tasks for the Project";

$config['dtcore']['pages']['tasks']['nobody']['en']['column.tasks.empty'] = "There are currently no tasks";

$config['dtcore']['pages']['tasks']['nobody']['en']['column.options.statuses.open'] = "Open";
$config['dtcore']['pages']['tasks']['nobody']['en']['column.options.statuses.progress'] = "In Progress";
$config['dtcore']['pages']['tasks']['nobody']['en']['column.options.statuses.resolved'] = "Resolved";
$config['dtcore']['pages']['tasks']['nobody']['en']['column.options.statuses.closed'] = "Closed";
$config['dtcore']['pages']['tasks']['nobody']['en']['column.options.milestone.title'] = "Milestone";
$config['dtcore']['pages']['tasks']['nobody']['en']['column.history.title'] = "Recent History";

// APP/Tasks/all
$config['dtcore']['pages']['tasks']['all']['en']['header.text'] = "All Tasks for the Project";

$config['dtcore']['pages']['tasks']['all']['en']['column.tasks.empty'] = "There are currently no tasks";

$config['dtcore']['pages']['tasks']['all']['en']['column.options.statuses.open'] = "Open";
$config['dtcore']['pages']['tasks']['all']['en']['column.options.statuses.progress'] = "In Progress";
$config['dtcore']['pages']['tasks']['all']['en']['column.options.statuses.resolved'] = "Resolved";
$config['dtcore']['pages']['tasks']['all']['en']['column.options.statuses.closed'] = "Closed";
$config['dtcore']['pages']['tasks']['all']['en']['column.options.milestone.title'] = "Milestone";
$config['dtcore']['pages']['tasks']['all']['en']['column.history.title'] = "Recent History";

// APP/Tasks/add
$config['dtcore']['pages']['tasks']['add']['en']['header.text'] = "Add a new Task for the Project";

$config['dtcore']['pages']['tasks']['add']['en']['form.subject.label'] = "Subject";
$config['dtcore']['pages']['tasks']['add']['en']['form.subject.placeholder'] = "Quick, yet informative, description";
$config['dtcore']['pages']['tasks']['add']['en']['form.description.label'] = "Description";
$config['dtcore']['pages']['tasks']['add']['en']['form.description.placeholder'] = "Longer and more descriptive explanation...";
$config['dtcore']['pages']['tasks']['add']['en']['form.type.label'] = "Issue Type";
$config['dtcore']['pages']['tasks']['add']['en']['form.priority.label'] = "Priority";
$config['dtcore']['pages']['tasks']['add']['en']['form.milestone.label'] = "Milestone";
$config['dtcore']['pages']['tasks']['add']['en']['form.submit'] = "Submit";
$config['dtcore']['pages']['tasks']['add']['en']['form.submit.continue'] = "Submit and Edit";

$config['dtcore']['pages']['tasks']['add']['en']['modal.header.text'] = "Add a new task to this Project";
$config['dtcore']['pages']['tasks']['add']['en']['modal.header.subtext'] = "Task will appear in the Ice Box if no milestone is set";
$config['dtcore']['pages']['tasks']['add']['en']['modal.form.close'] = "Close";

// APP/Tasks/edit
$config['dtcore']['pages']['tasks']['edit']['en']['header.text'] = "Edit a task for the Project";

$config['dtcore']['pages']['tasks']['edit']['en']['bar.task'] = "Task #";
$config['dtcore']['pages']['tasks']['edit']['en']['bar.view'] = "View";
$config['dtcore']['pages']['tasks']['edit']['en']['bar.assign'] = "Assign";
$config['dtcore']['pages']['tasks']['edit']['en']['bar.resolve'] = "Resolve";
$config['dtcore']['pages']['tasks']['edit']['en']['bar.close'] = "Close task";
$config['dtcore']['pages']['tasks']['edit']['en']['bar.open'] = "Re-open task";

$config['dtcore']['pages']['tasks']['edit']['en']['form.subject.label'] = "Subject";
$config['dtcore']['pages']['tasks']['edit']['en']['form.subject.placeholder'] = "Quick, yet informative, description";
$config['dtcore']['pages']['tasks']['edit']['en']['form.description.label'] = "Description";
$config['dtcore']['pages']['tasks']['edit']['en']['form.description.placeholder'] = "Longer and more descriptive explanation...";
$config['dtcore']['pages']['tasks']['edit']['en']['form.type.label'] = "Issue Type";
$config['dtcore']['pages']['tasks']['edit']['en']['form.priority.label'] = "Priority";
$config['dtcore']['pages']['tasks']['edit']['en']['form.milestone.label'] = "Milestone";
$config['dtcore']['pages']['tasks']['edit']['en']['form.submit'] = "Submit";
$config['dtcore']['pages']['tasks']['edit']['en']['form.submit.continue'] = "Submit and Edit";

// APP/Tasks/view
$config['dtcore']['pages']['tasks']['view']['en']['header.text'] = "A task for the Project";

$config['dtcore']['pages']['tasks']['view']['en']['bar.task'] = "Task #";
$config['dtcore']['pages']['tasks']['view']['en']['bar.edit'] = "Edit";
$config['dtcore']['pages']['tasks']['view']['en']['bar.assign'] = "Assign";
$config['dtcore']['pages']['tasks']['view']['en']['bar.resolve'] = "Resolve";
$config['dtcore']['pages']['tasks']['view']['en']['bar.close'] = "Close task";
$config['dtcore']['pages']['tasks']['view']['en']['bar.open'] = "Re-open task";

$config['dtcore']['pages']['tasks']['view']['en']['details.title'] = "Task details";
$config['dtcore']['pages']['tasks']['view']['en']['details.creator'] = "Created by";
$config['dtcore']['pages']['tasks']['view']['en']['details.type'] = "Task type";
$config['dtcore']['pages']['tasks']['view']['en']['details.priority'] = "Task priority";
$config['dtcore']['pages']['tasks']['view']['en']['details.status'] = "Task status";
$config['dtcore']['pages']['tasks']['view']['en']['details.assignee'] = "Assigned to";
$config['dtcore']['pages']['tasks']['view']['en']['details.milestone'] = "Fix Milestone";
$config['dtcore']['pages']['tasks']['view']['en']['details.created'] = "Created";
$config['dtcore']['pages']['tasks']['view']['en']['details.updated'] = "Last updated";

$config['dtcore']['pages']['tasks']['view']['en']['description.title'] = "Description";

$config['dtcore']['pages']['tasks']['view']['en']['history.create.action'] = "created this task";
$config['dtcore']['pages']['tasks']['view']['en']['history.change.action'] = "updated the tasks";
$config['dtcore']['pages']['tasks']['view']['en']['history.commented.action'] = "commented";
$config['dtcore']['pages']['tasks']['view']['en']['history.newcomment.placeholder'] = "Add a new comment to this task...";
$config['dtcore']['pages']['tasks']['view']['en']['history.newcomment.submit'] = "Comment";
$config['dtcore']['pages']['tasks']['view']['en']['history.editcomment.submit'] = "Update comment";
$config['dtcore']['pages']['tasks']['view']['en']['history.assignee.assigned'] = "Assigned to:";
$config['dtcore']['pages']['tasks']['view']['en']['history.assignee.none'] = "No-one currently assigned";

$config['dtcore']['pages']['tasks']['view']['en']['modal.assign.header'] = "No-one currently assigned";
$config['dtcore']['pages']['tasks']['view']['en']['modal.assign.assignee.label'] = "New Assignee";
$config['dtcore']['pages']['tasks']['view']['en']['modal.assign.assignee.placeholder'] = "Start typing a name...";
$config['dtcore']['pages']['tasks']['view']['en']['modal.assign.close'] = "Close";
$config['dtcore']['pages']['tasks']['view']['en']['modal.assign.submit'] = "Assign";
$config['dtcore']['pages']['tasks']['view']['en']['modal.assign.body'] = "To assign this task to a user on this project, enter their name in the box below and select";

$config['dtcore']['pages']['tasks']['view']['en']['modal.close.header'] = "Close the task";
$config['dtcore']['pages']['tasks']['view']['en']['modal.close.comment.placeholder'] = "Enter a closing comment for this task...";
$config['dtcore']['pages']['tasks']['view']['en']['modal.close.close'] = "Cancel";
$config['dtcore']['pages']['tasks']['view']['en']['modal.close.submit'] = "Close Task";
$config['dtcore']['pages']['tasks']['view']['en']['modal.close.body'] = "Before the task can be closed, please leave an explanation.";

// APP/Tasks/api_marshalled

$config['dtcore']['pages']['tasks']['api_marshalled']['en']['column.tasks.empty'] = "No Tasks found...";
