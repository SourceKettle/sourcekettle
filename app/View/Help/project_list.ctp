<?php
/**
 *
 * View class for APP/help/project_list for the SourceKettle system
 * Display the help page for the list projects part of the application
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          https://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Help
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header('Help! <small>What do all the project icons mean?</small>');
$this->Html->script('help', array('inline' => false));

// Example data
$private = array('Project' => array(
	'id' => 0,
	'name' => 'SuperSecr3t',
	'public' => false,
	'modified' => new DateTime('2 days ago', new DateTimeZone('UTC')),
));
$public = array('Project' => array(
	'id' => 0,
	'name' => 'Publ1cProj',
	'public' => true,
	'modified' => new DateTime('2 hours ago', new DateTimeZone('UTC')),
));
?>

<div class="row">
	<div class="span2">
		<?= $this->element('Sidebar/help') ?>
	</div>
	<div class="span10">
		<div class="well">
			<h3>Listing projects</h3>

			<p>Here at SourceKettle, we have tried to pack as much information into the project views as possible. This can however, be daunting to new users who do not know what all the little icons mean, so here is a breakdown:</p>

			<h4>1) Public vs. Private</h4>

			<p>See that little <i class="icon-lock"></i> at the top right of <strong>exhibit 1</strong>? That means that the project is private and you should keep its existence super secret! If you see a <i class="icon-globe"></i> (<strong>exhibit 2</strong>) then this means the project is public and can be viewed by any SourceKettle member.</p>

		</div>
		<div class="row-fluid">
			<div class="offset1 span5 example">
				<?=$this->Element('Project/block', array('nospan' => true, 'project' => $private));?>
				<div class="alert alert-info">
					<strong>Exhibit 1:</strong> A private project
				</div>
			</div>
			<div class="span5 example">
				<?=$this->Element('Project/block', array('nospan' => true, 'project' => $public));?>
				<div class="alert alert-info">
					<strong>Exhibit 2:</strong> A public project
				</div>
			</div>
		</div>
		<div class="well">
			<h4>2) Title, description and last modified</h4>

			<p>These parts of the view are mostly self explanatory but can, occasionally, be confusing. At the top we have the title of the project (<strong>SuperSect3t</strong> in <strong>exhibit 1</strong>). Following this we have the projects description and finally we have the last time that the project was modified.</p>
			<div class="alert alert-info">
				<strong>Hint:</strong> Take a look at our next guide on <strong><?= $this->Html->link('deciphering the projects front page', array('action' => 'overview')) ?></strong>
			</div>
		</div>
	</div>
</div>
