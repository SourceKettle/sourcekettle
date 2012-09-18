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
 * Configuration for the attachments section
 * APP/Attachments
 */

// APP/Attachment/topbar
$config['dtcore']['pages']['attachments']['topbar']['en']['topbar.create.text'] = "Upload file";
$config['dtcore']['pages']['attachments']['topbar']['en']['topbar.all.text'] = "All Attachments";
$config['dtcore']['pages']['attachments']['topbar']['en']['topbar.images.text'] = "Images";
$config['dtcore']['pages']['attachments']['topbar']['en']['topbar.videos.text'] = "Videos";
$config['dtcore']['pages']['attachments']['topbar']['en']['topbar.text.text'] = "Text";
$config['dtcore']['pages']['attachments']['topbar']['en']['topbar.other.text'] = "Others";

// APP/Attachments/index
$config['dtcore']['pages']['attachments']['index']['en']['header.text'] = "Attachments for the project";

// APP/Attachments/add
$config['dtcore']['pages']['attachments']['add']['en']['header.text'] = "Add an Attachment";
$config['dtcore']['pages']['attachments']['add']['en']['instruction'] = "Select a file to upload";
$config['dtcore']['pages']['attachments']['add']['en']['form.select'] = "Select file";
$config['dtcore']['pages']['attachments']['add']['en']['form.upload'] = "Upload";
$config['dtcore']['pages']['attachments']['add']['en']['form.change'] = "Change";

// APP/Attachments/image
$config['dtcore']['pages']['attachments']['image']['en']['header.text'] = "Images for the project";

// APP/Attachments/video
$config['dtcore']['pages']['attachments']['video']['en']['header.text'] = "Videos for the project";

// APP/Attachments/text
$config['dtcore']['pages']['attachments']['text']['en']['header.text'] = "Text files for the project";

// APP/Attachments/text
$config['dtcore']['pages']['attachments']['other']['en']['header.text'] = "Other files for the project";

// APP/Attachments/elements/empty
$config['dtcore']['pages']['attachments']['element.empty']['en']['empty.text'] = "No files of this type";

// APP/Attachments/elements/full
$config['dtcore']['pages']['attachments']['element.full']['en']['table.header.filename'] = "Filename";
$config['dtcore']['pages']['attachments']['element.full']['en']['table.header.size'] = "Size";
$config['dtcore']['pages']['attachments']['element.full']['en']['table.header.created'] = "Created";
$config['dtcore']['pages']['attachments']['element.full']['en']['table.header.options'] = "Options";