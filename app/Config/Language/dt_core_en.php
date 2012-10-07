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
 * Configuration for the history Plugin
 * APP/*
 */
$config['dtcore']['pages']['all']['history']['en']['log.collaborator.updated.context'] = "{actioner} updated {subject}'s role in {project}";
$config['dtcore']['pages']['all']['history']['en']['log.collaborator.updated'] = "{actioner} updated {subject}'s role &rarr; '{field}' changed from '{old}' to '{new}'";

$config['dtcore']['pages']['all']['history']['en']['log.collaborator.created.context'] = "{subject} was added by {actioner} to {project}";
$config['dtcore']['pages']['all']['history']['en']['log.collaborator.created'] = "{subject} was added to the project by {actioner}";

$config['dtcore']['pages']['all']['history']['en']['log.collaborator.deleted.context'] = "{actioner} removed {subject} from {project}";
$config['dtcore']['pages']['all']['history']['en']['log.collaborator.deleted'] = "{actioner} removed {subject} from the project";

$config['dtcore']['pages']['all']['history']['en']['log.time.updated.context'] = "{actioner} updated some {subject} in {project}";
$config['dtcore']['pages']['all']['history']['en']['log.time.updated'] = "{actioner} updated some {subject}";

$config['dtcore']['pages']['all']['history']['en']['log.time.created.context'] = "{actioner} {subject} to {project}";
$config['dtcore']['pages']['all']['history']['en']['log.time.created'] = "{actioner} {subject} to the project";

$config['dtcore']['pages']['all']['history']['en']['log.time.deleted.context'] = "{actioner} removed some {subject} from {project}";
$config['dtcore']['pages']['all']['history']['en']['log.time.deleted'] = "{actioner} removed {subject} from the project";

$config['dtcore']['pages']['all']['history']['en']['log.source.created.context'] = "{actioner} commited code to {project}";
$config['dtcore']['pages']['all']['history']['en']['log.source.created'] = "{actioner} commited code to the project &rarr; {subject}";

$config['dtcore']['pages']['all']['history']['en']['log.task.updated.context'] = "{actioner} updated task {subject} in {project}";
$config['dtcore']['pages']['all']['history']['en']['log.task.updated.short'] = "{actioner} updated task {subject}";
$config['dtcore']['pages']['all']['history']['en']['log.task.updated'] = "{actioner} updated task {subject} &rarr; '{field}' was modified";

$config['dtcore']['pages']['all']['history']['en']['log.task.created.context'] = "{actioner} added a task ({subject}) to {project}";
$config['dtcore']['pages']['all']['history']['en']['log.task.created.short'] = "{actioner} added task {subject}";
$config['dtcore']['pages']['all']['history']['en']['log.task.created'] = "{actioner} added a task ({subject}) to the project";

$config['dtcore']['pages']['all']['history']['en']['log.task.deleted.context'] = "{actioner} deleted task {subject} from {project}";
$config['dtcore']['pages']['all']['history']['en']['log.task.deleted.short'] = "{actioner} deleted task {subject}";
$config['dtcore']['pages']['all']['history']['en']['log.task.deleted'] = "{actioner} deleted task {subject} from the project";


$config['dtcore']['pages']['all']['history']['en']['log.milestone.updated.context'] = "{actioner} updated milestone {subject} in {project}";
$config['dtcore']['pages']['all']['history']['en']['log.milestone.updated.short'] = "{actioner} updated milestone {subject}";
$config['dtcore']['pages']['all']['history']['en']['log.milestone.updated'] = "{actioner} updated milestone {subject} &rarr; '{field}' was modified";

$config['dtcore']['pages']['all']['history']['en']['log.milestone.created.context'] = "{actioner} added a milestone ({subject}) to {project}";
$config['dtcore']['pages']['all']['history']['en']['log.milestone.created.short'] = "{actioner} added milestone {subject}";
$config['dtcore']['pages']['all']['history']['en']['log.milestone.created'] = "{actioner} added a milestone ({subject}) to the project";

$config['dtcore']['pages']['all']['history']['en']['log.milestone.deleted.context'] = "{actioner} deleted milestone {subject} from {project}";
$config['dtcore']['pages']['all']['history']['en']['log.milestone.deleted.short'] = "{actioner} deleted milestone {subject}";
$config['dtcore']['pages']['all']['history']['en']['log.milestone.deleted'] = "{actioner} deleted milestone {subject} from the project";