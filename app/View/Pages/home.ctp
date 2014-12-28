<?php
/**
 *
 * View class for APP/Pages/home for the SourceKettle system
 * Display the home page. Only visible to not logged in users
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Help
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

if (!isset($_SESSION['sk-cookies-accepted'])) {
	echo $this->element('cookieInfo');
}

// Load the sysadmin-configurable home page content out of the Config directory
$basedir = realpath(dirname(__FILE__).'/../../');

if(file_exists("$basedir/Config/homepage.php")){
    include("$basedir/Config/homepage.php");
} else{?>
   <h1><?=__("Welcome to %s!", $sourcekettle_config['UserInterface']['alias']['value'])?></h1>
   <?= __("%s to get started.", $this->Html->link(__('Click here'), array('controller' => 'login')))?>
<?}

