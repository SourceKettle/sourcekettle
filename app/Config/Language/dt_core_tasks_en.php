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
$config['dtcore']['pages']['tasks']['topbar']['en']['topbar.option1.text'] = "Classic Board";
$config['dtcore']['pages']['tasks']['topbar']['en']['topbar.option2.text'] = "Sprint Board";
$config['dtcore']['pages']['tasks']['topbar']['en']['topbar.option3.text'] = "Create Task";
$config['dtcore']['pages']['tasks']['topbar']['en']['topbar.option4.text'] = "Milestones";

// APP/Tasks/index
$config['dtcore']['pages']['tasks']['index']['en']['header.text'] = "Tasks for the Project";

$config['dtcore']['pages']['tasks']['index']['en']['column.tasks.title'] = "Tasks";
$config['dtcore']['pages']['tasks']['index']['en']['column.tasks.empty'] = "There are currently no tasks";

$config['dtcore']['pages']['tasks']['index']['en']['column.history.title'] = "Recent History";

// APP/Tasks/sprint
$config['dtcore']['pages']['tasks']['sprint']['en']['header.text'] = "Sprint board for the Project";

$config['dtcore']['pages']['tasks']['sprint']['en']['column.backlog.title'] = "Backlog";
$config['dtcore']['pages']['tasks']['sprint']['en']['column.backlog.empty'] = "Woo! Empty Backlog!";

$config['dtcore']['pages']['tasks']['sprint']['en']['column.inprogress.title'] = "In Progress";
$config['dtcore']['pages']['tasks']['sprint']['en']['column.inprogress.empty'] = "Wait, there must be something to do?";

$config['dtcore']['pages']['tasks']['sprint']['en']['column.completed.title'] = "Completed";
$config['dtcore']['pages']['tasks']['sprint']['en']['column.completed.empty'] = "Nothing to see here!";

$config['dtcore']['pages']['tasks']['sprint']['en']['column.icebox.title'] = "Ice Box";
$config['dtcore']['pages']['tasks']['sprint']['en']['column.icebox.empty.line1'] = "It's pretty warm in here!";
$config['dtcore']['pages']['tasks']['sprint']['en']['column.icebox.empty.line2'] = "Add some tasks to the current milestone!";

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
$config['dtcore']['pages']['tasks']['view']['en']['bar.selfassign'] = "Assign to me";
$config['dtcore']['pages']['tasks']['view']['en']['bar.close'] = "Close task";

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
$config['dtcore']['pages']['tasks']['view']['en']['modal.assign.close'] = "Close";
$config['dtcore']['pages']['tasks']['view']['en']['modal.assign.submit'] = "Assign";