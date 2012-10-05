<?php
/**
 *
 * Setup page for the DevTrack system
 * Guides users through appropriate system configuration steps
 *
 * Base system: CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Base system: Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Modifications: DevTrack Development Team 2012
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Original: Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @copyright     Modifications: DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Setup
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (Configure::read('debug') == 0):
    throw new NotFoundException();
endif;
App::uses('Debugger', 'Utility');

/**
 * modRewriteCheck
 * Check apache to see if mod_rewrite is enabled
 *
 */
function modRewriteCheck() {
    $modules = apache_get_modules();
    $found = false;
    foreach ($modules as $module){
        if ($module == 'mod_rewrite'){
            echo '<div class="alert alert-success">';
                echo __d('cake_dev', 'mod_rewrite is enabled on your server');
            echo '</div>';
            $found = true;
            break;
        }
    }
    if (!$found){
        echo '<div class="alert alert-error">';
        echo __d('cake_dev', 'URL rewriting is not properly configured on your server.');
        echo '1) <a target="_blank" href="http://book.cakephp.org/2.0/en/installation/advanced-installation.html#apache-and-mod-rewrite-and-htaccess">Help me configure it</a>';
        echo '2) <a target="_blank" href="http://book.cakephp.org/2.0/en/development/configuration.html#cakephp-core-configuration">I don\'t / can\'t use URL rewriting</a>';
        echo '</div>';
        return 0;
    }
    return 1;
}

/**
 * saltCipherCheck
 * Check that the salt and cipher are not defaults
 *
 */
function saltCipherCheck() {
    $changed = true;
    if (Configure::read('Security.salt') == 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi') {
        echo '<div class="alert alert-error">';
        echo __d('cake_dev', 'Please change the value of \'Security.salt\' in app/Config/core.php to a salt value specific to your application.');
        echo '</div>';
        $changed = false;     
    }
    if (Configure::read('Security.cipherSeed') === '76859309657453542496749683645') {
        echo '<div class="alert alert-error">';    
        echo __d('cake_dev', 'Please change the value of \'Security.cipherSeed\' in app/Config/core.php to a numeric (digits only) seed value specific to your application.');
        echo '</div>';
        $changed = false;     
    } 
    if ( $changed ) {
        echo '<div class="alert alert-success">';    
        echo __d('cake_dev', 'The values of \'Security.cipherSeed\' and \'Security.salt\' have been changed from the default values.');
        echo '</div>';
        return 1;
    }
    return 0;
}

function gitRepoSet(){
    $config = Configure::read('devtrack');
    if (isset($config['repo']['base'])  && !empty($config['repo']['base'])){
        return true;
    } else {
        return false;
    }
}

function gitUserSet() {
    $config = Configure::read('devtrack');
    if (isset($config['repo']['user']) && !empty($config['repo']['user'])){
        echo '<div class="alert alert-success">';
        echo __d('cake_dev', 'Your git user has been set to <strong>' . $config['repo']['user'] . '</strong>');
        echo '</div>';
        return true;
    } else {
        echo '<div class="alert alert-error">';
        echo __d('cake_dev', 'Your git user is NOT set. The git user can be set in APP/Config/devtrack.php');
        echo '</div>';
        return false;
    }
}

function gitRepoLocationSet(){
    if (gitRepoSet()){
        $config = Configure::read('devtrack');
        echo '<div class="alert alert-success">';
        echo __d('cake_dev', 'Your git repositories directory has been set to <strong>' . $config['repo']['base'] . '</strong>');
        echo '</div>';
        return true;
    } else {
        echo '<div class="alert alert-error">';
        echo __d('cake_dev', 'Your git repositories directory is NOT set. The git repositories directory can be set in APP/Config/devtrack.php');
        echo '</div>';
        return false;
    }
}

function gitRepoWritable() {
    if (gitRepoSet()){

        $config = Configure::read('devtrack');
        if (is_writable($config['repo']['base'])){
            echo '<div class="alert alert-success">';
            echo __d('cake_dev', 'Your git repositories directory is writable.');
            echo '</div>';
            return 1;
        } else {
            echo '<div class="alert alert-error">';
            echo __d('cake_dev', 'Your git repositories directory is NOT writable. The git repositories directory can be found in ' . $config['repo']['base']);
            echo '</div>';
            return 0;
        }
    }
}

function gitRepoReadable() {
    if (gitRepoSet()){
        $config = Configure::read('devtrack');
        if (is_readable($config['repo']['base'])){
            echo '<div class="alert alert-success">';
            echo __d('cake_dev', 'Your git repositories directory is readable.');
            echo '</div>';
            return 1;
        } else {
            echo '<div class="alert alert-error">';
            echo __d('cake_dev', 'Your git repositories directory is NOT readable. The git repositories directory can be found in ' . $config['repo']['base']);
            echo '</div>';
            return 0;
        }
    }
}

/**
 * phpVersionCheck
 * Check that PHP is recent
 *
 */
function phpVersionCheck() {
    if (version_compare(PHP_VERSION, '5.2.8', '>=')) {
        echo '<div class="alert alert-success">';
            echo __d('cake_dev', 'Your version of PHP is 5.2.8 or higher.');
        echo '</div>';
        return 1;
    } else {
        echo '<div class="alert alert-error">';
            echo __d('cake_dev', 'Your version of PHP is too low. You need PHP 5.2.8 or higher to use DevTrack.');
        echo '</div>';
        return 0;
    }
}

/**
 * tmpCheck
 * Check that the tmp directory is writable
 *
 */
function tmpCheck() {
    if (is_writable(TMP)) {
        echo '<div class="alert alert-success">';
        echo __d('cake_dev', 'Your tmp directory is writable.');
        echo '</div>';
        return 1;
    } else {
        echo '<div class="alert alert-error">';
        echo __d('cake_dev', 'Your tmp directory is NOT writable. The tmp directory can be found in app/tmp/');
        echo '</div>';
        return 0;
    }
}

/**
 * cacheCheck
 * Check that the cache is in working order
 *
 */
function cacheCheck() {
    $settings = Cache::settings();
    if (!empty($settings)) {
        echo '<div class="alert alert-success">';
        echo __d('cake_dev', 'The %s is being used for core caching. To change the config edit APP/Config/core.php ', '<em>'. $settings['engine'] . 'Engine</em>');
        echo '</div>';
        return 1;
    } else {
        echo '<div class="alert alert-error">';
        echo __d('cake_dev', 'Your cache is NOT working. Please check the settings in APP/Config/core.php');
        echo '</div>';
        return 0;
    }
}

/**
 * databaseCheck
 * Check that the database connection and tables are setup
 *
 */
function databaseCheck() {
    if (file_exists(APP . 'Config' . DS . 'database.php')) {
        echo '<div class="alert alert-success">';
        echo __d('cake_dev', 'Your database configuration file is present.');
        echo '</div>';
    } else {
        echo '<div class="alert alert-error">';
        echo __d('cake_dev', 'Your database configuration file is NOT present.');
        echo '<br/>';
        echo __d('cake_dev', 'Rename APP/Config/database.php.default to APP/Config/database.php');
        echo '</div>';
        return 0;
    }
    App::uses('ConnectionManager', 'Model');
    try {
        $connected = ConnectionManager::getDataSource('default');
    } catch (Exception $connectionError) {
        $connected = false;
    }
    if ($connected && $connected->isConnected()) {
        echo '<div class="alert alert-success">';
        echo __d('cake_dev', 'DevTrack is able to connect to the database.');
        echo '</div>';
    } else {
        echo '<div class="alert alert-error">';
        echo __d('cake_dev', 'DevTrack is NOT able to connect to the database.');
        echo '<br /><br />';
        echo $connectionError->getMessage();
        echo '</div>';
        return 0;
    }
    $db = ConnectionManager::getDataSource('default');
    $tables = $db->listSources();
    if ( empty($tables) ) {
        echo '<div class="alert alert-error">';
        echo __d('cake_dev', 'The database tables required for DevTracks operation, are missing. ');
        echo __d('cake_dev', 'Please use the \'db.sql\' script to initialise the database.');
        echo '</div>';
        return 0;
    }
    return 1;
}

/**
 * unicodeCheck
 * Check that UNICODE is supported
 *
 */
function unicodeCheck() {
    App::uses('Validation', 'Utility');
    if (!Validation::alphaNumeric('cakephp')) {
        echo '<p><div class="alert alert-error">';
        echo __d('cake_dev', 'PCRE has not been compiled with Unicode support.');
        echo '<br/>';
        echo __d('cake_dev', 'Recompile PCRE with Unicode support by adding <code>--enable-unicode-properties</code> when configuring.');
        echo '</div></p>';
        return 0;
    }
    return 1;
}

echo '<h1>Application setup</h1>';

// Check to see if each check is passed
// TODO better code required
$complete = true;

// Check mod_rewrite
if ( !modRewriteCheck() ) $complete = false;

// Check ciphers have been set
if ( !saltCipherCheck() ) $complete = false;

// Check PHP version is up to date
if ( !phpVersionCheck() ) $complete = false;

// Check the tmp directory is writable
if ( !tmpCheck() ) $complete = false;

// Check the cache is working
if ( !cacheCheck() ) $complete = false;

// Check the database is A-OK
if ( !databaseCheck() ) $complete = false;

// Check Unicode
if ( !unicodeCheck() ) $complete = false;

// Check git user is set
if ( !gitUserSet() ) $complete = false;

// Check the git repositories are properly configured
if ( gitRepoLocationSet()){
    // Check repo directory readable
    if ( !gitRepoReadable() ) $complete = false;

    // Check repo directory writable
    if ( !gitRepoWritable() ) $complete = false;
} else {
    $complete = false;
}

if ( $complete ) {
    echo '<div class="alert alert-info">';
    echo '<strong>';
    echo 'DevTrack is set up and ready to go! ';
    echo '</strong>';
    echo 'Just change the value of \'debug\' in app/Config/core.php to a \'0\' to place DevTrack in production mode.';
    echo '</div>';
}
