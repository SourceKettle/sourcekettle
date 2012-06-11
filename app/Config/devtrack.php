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
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.Config
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

 // Tool name
 // value will be string that will be shown across the system in place of 'DevTrack'
 $config['devtrack']['global']['alias'] = 'PhillTrack';
 
 // Registration
 // set to true to allow new users to register with the system
 // set to false to prevent any new users
 $config['devtrack']['login']['registration'] = true;