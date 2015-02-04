<?php
/**
 *
 * View class for APP/help/source for the SourceKettle system
 * Display the help page for viewing source code
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

$this->Html->css('pages/help', null, array ('inline' => false));
?>

<div class="row-fluid">
	<div class="well">
		<h3>Viewing source code</h3>
		<p>To start viewing the source code that is attached to your project, select '<a href="#"><i class="icon-pencil"></i> Source</a>' from the projects sidebar. It's as simple as that! From here you can traverse the folder hierarchy of your repository.</p>
	</div>
</div>

<div class="row-fluid">
	<div class="well">
		<h3>Getting the code flowing</h3>
		<p>
		If you want to use source control, you will first need to set up at least one <a href='/help/addkey'>SSH key</a>.
		</p>
		<h4>Already a git wizard?</h4>
		<p>
		If you already know what you're doing and just want a git URL, it will look like:
		<pre><?= $sourcekettle_config['SourceRepository']['user']['value'] ?>@<?= $_SERVER['SERVER_NAME'] ?>:projects/my-project-name.git</pre>
		</p>
		<p>
		If you have created a brand, spanking new project but haven't yet checked anything in, click on the '<a href='#'><i class="icon-pencil"></i>Source</a>' link in your project's sidebar for instructions on how to get started (it'll tell you all the project-specific stuff so you can pretty much copy-and-paste lines into a terminal).
		</p>
	
	</div>
</div>

<div class="row-fluid">
	<div class="well">
		<h3>Checking out an existing repository</h3>
		<p>
		If you've got an existing project full of code-y goodness, at some point you will probably want to check out a copy somewhere else! You will need to run:
		<pre>git checkout <?= $sourcekettle_config['SourceRepository']['user']['value'] ?>@<?= $_SERVER['SERVER_NAME'] ?>:projects/my-project-name.git</pre>
	
		...with your actual project name, of course! The URI for your actual repository is displayed on the source page for easy copy-and-pasting.
		</p>
	</div>
</div>
