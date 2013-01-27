<?php
/**
 *
 * Central configuration file for the DevTrack system
 * Provides the central config for the system
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

// Tool name
// value will be string that will be shown across the system in place of 'DevTrack'
$config['devtrack']['global']['alias'] = 'DevTrack';

// Username for SSH access to SCM repositories
$config['devtrack']['repo']['user'] = 'git';

// Repository folder
// set to folder where you would like SCM repositories placed
$config['devtrack']['repo']['base'] = '/home/git/repositories';

