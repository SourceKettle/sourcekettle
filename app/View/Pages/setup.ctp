<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (Configure::read('debug') == 0):
	throw new NotFoundException();
endif;
App::uses('Debugger', 'Utility');
?>

<h1>Application setup</h1>

<?php 
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
?>

<p>
<?php
	if (version_compare(PHP_VERSION, '5.2.8', '>=')):
		echo '<div class="alert alert-success">';
			echo __d('cake_dev', 'Your version of PHP is 5.2.8 or higher.');
		echo '</div>';
	else:
		echo '<div class="alert alert-error">';
			echo __d('cake_dev', 'Your version of PHP is too low. You need PHP 5.2.8 or higher to use CakePHP.');
		echo '</div>';
	endif;
?>
</p>
<p>
	<?php
		if (is_writable(TMP)):
			echo '<div class="alert alert-success">';
				echo __d('cake_dev', 'Your tmp directory is writable.');
			echo '</div>';
		else:
			echo '<div class="alert alert-error">';
				echo __d('cake_dev', 'Your tmp directory is NOT writable.');
			echo '</div>';
		endif;
	?>
</p>
<p>
	<?php
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
	?>
</p>
<p>
	<?php
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
	?>
</p>
<?php
if (isset($filePresent)):
	App::uses('ConnectionManager', 'Model');
	try {
		$connected = ConnectionManager::getDataSource('default');
	} catch (Exception $connectionError) {
		$connected = false;
	}
?>
<p>
	<?php
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
	?>
</p>
<?php endif;?>
<?php
	App::uses('Validation', 'Utility');
	if (!Validation::alphaNumeric('cakephp')) {
		echo '<p><div class="alert alert-error">';
			echo __d('cake_dev', 'PCRE has not been compiled with Unicode support.');
			echo '<br/>';
			echo __d('cake_dev', 'Recompile PCRE with Unicode support by adding <code>--enable-unicode-properties</code> when configuring');
		echo '</div></p>';
	}
?>