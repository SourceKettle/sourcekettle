<?php
/**
 *
 * Setup page for the SourceKettle system
 * Guides users through appropriate system configuration steps
 *
 * Base system: CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Base system: Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Modifications: SourceKettle Development Team 2012
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Original: Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @copyright     Modifications: SourceKettle Development Team 2012
 * @link          https://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Setup
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (Configure::read('debug') == 0) {
    throw new NotFoundException();
} ?>

	<h1>Application setup</h1>
	<div class="alert alert-success"><?=__d('cake_dev', "Global config files are stored in [$configdir]")?></div>

<? if (!$modRewriteExists) { ?>
	<div class="alert alert-error">
		<?=__d('cake_dev', 'URL rewriting is not properly configured on your server.')?><br>
		1) <a target="_blank" href="http://book.cakephp.org/2.0/en/installation/advanced-installation.html#apache-and-mod-rewrite-and-htaccess">Help me configure it</a><br>
		2) <a target="_blank" href="http://book.cakephp.org/2.0/en/development/configuration.html#cakephp-core-configuration">I don't/can't use URL rewriting</a>
	</div>
<? } else { ?>
	<div class="alert alert-success">
		<?=__d('cake_dev', 'mod_rewrite is enabled on your server')?>
	</div>
<? }
if (!$securitySaltChanged) { ?>
	<div class="alert alert-error">
		<?=__d('cake_dev', "Please change the value of 'Security.salt' in [$globalConfigFile] to a salt value specific to your application.")?>
	</div>
<? }
if (!$securityCipherChanged) { ?>
	<div class="alert alert-error">
		<?=__d('cake_dev', "Please change the value of 'Security.cipherSeed' in [$globalConfigFile] to a numeric (digits only) seed value specific to your application.")?>
	</div>
<? }
if ($securitySaltChanged && $securityCipherChanged) { ?>
	<div class="alert alert-success">
		<?=__d('cake_dev', "The values of 'Security.cipherSeed' and 'Security.salt' have been changed from the default values.")?>
	</div>
<? }
if (!$gitUserSet) { ?>
	<div class="alert alert-error">
		<?=__d('cake_dev', 'Your git user is NOT set. The git user can be set in [' . $configdir . 'sourcekettle.php]')?>
	</div>
<? } else { ?>
	<div class="alert alert-success">
		<?=__d('cake_dev', "Your git user has been set to <strong>$gitUser</strong>")?>
	</div>
<? }
if (!$phpVersionCheck) { ?>
	<div class="alert alert-error">
		<?=__d('cake_dev', 'Your version of PHP is too low. You need PHP 5.2.8 or higher to use SourceKettle.')?>
	</div>
<? } else { ?>
	<div class="alert alert-success">
		<?=__d('cake_dev', 'Your version of PHP is 5.2.8 or higher.')?>
	</div>
<? }
if ($cacheConfigSet) { ?>
	<div class="alert alert-success">
		<?=__d('cake_dev', "The %s is being used for core caching. To change the config edit [$globalConfigFile]", "<em>$cacheConfigEngine Engine</em>")?>
	</div>
<? } else { ?>
	<div class="alert alert-error">
		<?=__d('cake_dev', "Your cache is NOT working. Please check the settings in [$globalConfigFile]")?>
	</div>
<? }
if ($tmpWritable) { ?>
	<div class="alert alert-success">
	<?=__d('cake_dev', 'Your tmp directory is writable.')?>
	</div>
<? } else { ?>
	<div class="alert alert-error">
		<?=__d('cake_dev', 'Your tmp directory is NOT writable. The tmp directory can be found in [' . APP . 'tmp/]')?>
	</div>
<? }
if (!$unicodeSupport) { ?>
	<p>
		<div class="alert alert-error">
			<?=__d('cake_dev', 'PCRE has not been compiled with Unicode support.')?>
			<br/>
			<?=__d('cake_dev', 'Recompile PCRE with Unicode support by adding <code>--enable-unicode-properties</code> when configuring.')?>
		</div>
	</p>
<? }
if ($databaseConfigPresent) { ?>
	<div class="alert alert-success">
	<?=__d('cake_dev', 'Your database configuration file is present.')?>
	</div>
	<? if ($databaseConnected) { ?>
		<div class="alert alert-success">
			<?=__d('cake_dev', 'SourceKettle is able to connect to the database.')?>
		</div>
		<? if (!$databaseTables) { ?>
			<div class="alert alert-error">
				<?=__d('cake_dev', 'The database tables required for SourceKettle\'s operation, are missing. ')?>
				<?=__d('cake_dev', 'Please use the \'db.sql\' script to initialise the database.')?>
			</div>
		<? }
	} else { ?>
		<div class="alert alert-error">
			<?=__d('cake_dev', 'SourceKettle is NOT able to connect to the database.')?><br/><br/>?>
			<?=$databaseConnectionIssue?>
		</div>
	<? }
} else { ?>
	<div class="alert alert-error">
		<?=__d('cake_dev', 'Your database configuration file is NOT present.')?><br/>
		<?=__d('cake_dev', 'Rename ' . APP . DS . 'Config' . DS . 'database.php.default to ' . $configdir . 'database.php')?>
	</div>
<? }
if ($gitRepoSet) { ?>
	<div class="alert alert-success">
		<?=__d('cake_dev', "Your git repositories directory has been set to [<strong>$gitRepoSetBase</strong>]")?>
	</div>
	<? if ($gitRepoWritable){ ?>
		<div class="alert alert-success">
			<?=__d('cake_dev', 'Your git repositories directory is writable.')?>
		</div>
	<? } else { ?>
		<div class="alert alert-error">
			<?=__d('cake_dev', "Your git repositories directory is NOT writable. The git repositories directory can be found in [$gitRepoSetBase]")?>
		</div>
	<? }
	if ($gitRepoReadable){ ?>
		<div class="alert alert-success">
			<?=__d('cake_dev', 'Your git repositories directory is readable.')?>
		</div>
	<? } else { ?>
		<div class="alert alert-error">
			<?=__d('cake_dev', "Your git repositories directory is NOT readable. The git repositories directory can be found in [$gitRepoSetBase]")?>
		</div>
	<? }
} else { ?>
	<div class="alert alert-error">
		<?=__d('cake_dev', 'Your git repositories directory is NOT set. The git repositories directory can be set in [' . $configdir . 'sourcekettle.php]')?>
	</div>
<? }
if ($complete) { ?>
	<div class="alert alert-info">
		<strong>SourceKettle is set up and ready to go!</strong>
		Just change the value of 'debug' in [<?=$globalConfigFile?>] to a '0' to place SourceKettle in production mode.
	</div>
<? } ?>
