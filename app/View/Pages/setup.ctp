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
 * @package       DevTrack.View.Pages
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
        echo "<?php echo __d('cake_dev', 'URL rewriting is not properly configured on your server.'); ?>";
        echo '1) <a target="_blank" href="http://book.cakephp.org/2.0/en/installation/advanced-installation.html#apache-and-mod-rewrite-and-htaccess">Help me configure it</a>';
        echo '2) <a target="_blank" href="http://book.cakephp.org/2.0/en/development/configuration.html#cakephp-core-configuration">I don\'t / can\'t use URL rewriting</a>';
        echo '</div>';
    }
}

/**
 * saltCipherCheck
 * Check that the salt and cipher are not defaults
 *
 */
function saltCipherCheck() {
    if (Configure::read('Security.salt') == 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi') {
        echo '<div class="alert alert-error">';
        echo __d('cake_dev', 'Please change the value of \'Security.salt\' in app/Config/core.php to a salt value specific to your application');
        echo '</div>';     
    }

    if (Configure::read('Security.cipherSeed') === '76859309657453542496749683645') {
        echo '<div class="alert alert-error">';    
        echo __d('cake_dev', 'Please change the value of \'Security.cipherSeed\' in app/Config/core.php to a numeric (digits only) seed value specific to your application');
        echo '</div>';
    }
}

/**
 * phpVersionCheck
 * Check that PHP is recent
 *
 */
function phpVersionCheck() {
    if (version_compare(PHP_VERSION, '5.2.8', '>=')):
        echo '<div class="alert alert-success">';
            echo __d('cake_dev', 'Your version of PHP is 5.2.8 or higher.');
        echo '</div>';
    else:
        echo '<div class="alert alert-error">';
            echo __d('cake_dev', 'Your version of PHP is too low. You need PHP 5.2.8 or higher to use DevTrack.');
        echo '</div>';
    endif;
}

/**
 * tmpCheck
 * Check that the tmp directory is writable
 *
 */
function tmpCheck() {
        if (is_writable(TMP)):
            echo '<div class="alert alert-success">';
                echo __d('cake_dev', 'Your tmp directory is writable.');
            echo '</div>';
        else:
            echo '<div class="alert alert-error">';
                echo __d('cake_dev', 'Your tmp directory is NOT writable.<br>The tmp directory can be found in app/tmp/');
            echo '</div>';
        endif;
}

/**
 * cacheCheck
 * Check that the cache is in working order
 *
 */
function cacheCheck() {
    $settings = Cache::settings();
    if (!empty($settings)):
        echo '<div class="alert alert-success">';
            echo __d('cake_dev', 'The %s is being used for core caching. To change the config edit APP/Config/core.php ', '<em>'. $settings['engine'] . 'Engine</em>');
        echo '</div>';
    else:
        echo '<div class="alert alert-error">';
            echo __d('cake_dev', 'Your cache is NOT working. Please check the settings in APP/Config/core.php');
        echo '</div>';
    endif;
}

/**
 * databaseCheck
 * Check that the database connection and tables are setup
 *
 */
function databaseCheck() {
    $filePresent = null;
    if (file_exists(APP . 'Config' . DS . 'database.php')):
        echo '<div class="alert alert-success">';
            echo __d('cake_dev', 'Your database configuration file is present.');
            $filePresent = true;
        echo '</div>';
    else:
        echo '<div class="alert alert-error">';
            echo __d('cake_dev', 'Your database configuration file is NOT present.');
            echo '<br/>';
            echo __d('cake_dev', 'Rename APP/Config/database.php.default to APP/Config/database.php');
        echo '</div>';
    endif;
    if (isset($filePresent)):
        App::uses('ConnectionManager', 'Model');
        try {
            $connected = ConnectionManager::getDataSource('default');
        } catch (Exception $connectionError) {
            $connected = false;
        }
        if ($connected && $connected->isConnected()):
            echo '<div class="alert alert-success">';
                 echo __d('cake_dev', 'DevTrack is able to connect to the database.');
            echo '</div>';
        else:
            echo '<div class="alert alert-error">';
                echo __d('cake_dev', 'DevTrack is NOT able to connect to the database.');
                echo '<br /><br />';
                echo $connectionError->getMessage();
            echo '</div>';
        endif;
    endif;
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
            echo __d('cake_dev', 'Recompile PCRE with Unicode support by adding <code>--enable-unicode-properties</code> when configuring');
        echo '</div></p>';
    }
}

echo '<h1>Application setup</h1>';

// Check to see if each check is passed
// TODO better code required
$winning = true;

// Check mod_rewrite
if ( !modRewriteCheck() ) $winning = false;

// Check ciphers have been set
if ( !saltCipherCheck() ) $winning = false;

// Check PHP version is up to date
if ( !phpVersionCheck() ) $winning = false;

// Check the tmp directory is writable
if ( !tmpCheck() ) $winning = false;

// Check the cache is working
if ( !cacheCheck() ) $winning = false;

// Check the database is A-OK
if ( !databaseCheck() ) $winning = false;

// Check Unicode
if ( !unicodeCheck() ) $winning = false;

