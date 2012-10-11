<?php
/**
 *
 * View class for APP/Pages/home for the DevTrack system
 * Display the home page. Only visible to not logged in users
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Help
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->element('cookieInfo');

// Load the sysadmin-configurable home page content out of the Config directory
$basedir = realpath(dirname(__FILE__).'/../../');

if(file_exists("$basedir/Config/homepage.php")){
    include("$basedir/Config/homepage.php");
} else{?>
   <h1>Welcome to DevTrack!</h1>
   <a href='/login'>Click here</a> to get started.
<?}

