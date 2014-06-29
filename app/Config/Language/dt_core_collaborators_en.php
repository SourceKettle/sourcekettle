<?php
/**
 *
 * ADVANCED USERS ONLY
 * Do NOT configure this file unless you know what you are doing.
 * Editing this file incorrectly could reduce the stability of the system.
 *
 * tl;dr Here be dragons.
 *
 * Core Configuration file for the SourceKettle system
 * Provides the core config for the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.Config
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/*
 * Configuration for the collaborators section
 * APP/Collaborators
 */

// APP/Collaborators/index
$config['dtcore']['pages']['collaborators']['index']['en']['header.text'] = "Collaborators working on the project";

$config['dtcore']['pages']['collaborators']['index']['en']['users.header'] = "Users on this project";
$config['dtcore']['pages']['collaborators']['index']['en']['users.users'] = "User";
$config['dtcore']['pages']['collaborators']['index']['en']['users.role'] = "Role";
$config['dtcore']['pages']['collaborators']['index']['en']['users.actions'] = "Actions";
$config['dtcore']['pages']['collaborators']['index']['en']['users.actions.delete'] = "Are you sure you want to remove {user} from the project?";

$config['dtcore']['pages']['collaborators']['index']['en']['add.header'] = "Add a User";


// APP/Collaborators/all
$config['dtcore']['pages']['collaborators']['all']['en']['header.text'] = "Collaborators working on the project";
