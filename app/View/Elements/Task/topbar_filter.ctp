<?php
/**
 *
 * Element for displaying the task topbar for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Topbar
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

?>

<ul class="span12 nav nav-pills">
	<li class="disabled">
	  <a href="#">Filters:</a>
	</li>

    <li class="dropdown">
    	<a class="dropdown-toggle" data-toggle="dropdown"
    	href="#">
    		<?=__("Milestone")?> <b class="caret"></b>
    	</a>
    	<ul class="dropdown-menu">
			<li><a href='?milestones='>Clear filter</a></li>
			<li><a href='?milestones=all'>All milestones</a></li>
		<? foreach ($milestones['open'] as $id => $milestone) {
			$class = in_array($id, array_keys($selected_milestones))? ' class="active"': '';
			?>
			<li<?=$class?>><a href='?milestones=<?=$id?>'><?=$milestone['Milestone']['subject']?></a></li>
		<? } ?>
		<? foreach ($milestones['closed'] as $id => $milestone) {
			$class = in_array($id, array_keys($selected_milestones))? ' class="active"': '';
			?>
			<li<?=$class?>><a href='?milestones=<?=$id?>'><?=$milestone['Milestone']['subject'].' '.__('(closed)')?></a></li>
		<? } ?>
    	</ul>
    </li>

    <li class="dropdown">
    	<a class="dropdown-toggle" data-toggle="dropdown"
    	href="#">
    		<?=__("Status")?> <b class="caret"></b>
    	</a>
    	<ul class="dropdown-menu">
			<li><a href='?statuses='>Clear filter</a></li>
			<li><a href='?statuses=all'>All statuses</a></li>
		<? foreach ($statuses as $id => $name) {
			$class = in_array($id, array_keys($selected_statuses))? ' class="active"': '';
			?>
			<li<?=$class?>><a href='?statuses=<?=urlencode($name)?>'><?=h($name)?></a></li>
		<? } ?>
    	</ul>
    </li>

    <li class="dropdown">
    	<a class="dropdown-toggle" data-toggle="dropdown"
    	href="#">
    		<?=__("Priority")?> <b class="caret"></b>
    	</a>
    	<ul class="dropdown-menu">
			<li><a href='?priorities='>Clear filter</a></li>
			<li><a href='?priorities=all'>All priorities</a></li>
		<? foreach ($priorities as $id => $name) {
			$class = in_array($id, array_keys($selected_priorities))? ' class="active"': '';
		?>
			<li<?=$class?>><a href='?priorities=<?=urlencode($name)?>'><?=h($name)?></a></li>
		<? } ?>
    	</ul>
    </li>

    <li class="dropdown">
    	<a class="dropdown-toggle" data-toggle="dropdown"
    	href="#">
    		<?=__("Type")?> <b class="caret"></b>
    	</a>
    	<ul class="dropdown-menu">
			<li><a href='?types='>Clear filter</a></li>
			<li><a href='?types=all'>All types</a></li>
		<? foreach ($types as $id => $name) {
			$class = in_array($id, array_keys($selected_types))? ' class="active"': '';
			?>
			<li<?=$class?>><a href='?types=<?=urlencode($name)?>'><?=h($name)?></a></li>
		<? } ?>
    	</ul>
    </li>

    <li class="dropdown">
    	<a class="dropdown-toggle" data-toggle="dropdown"
    	href="#">
    		<?=__("Assigned to")?> <b class="caret"></b>
    	</a>
    	<ul class="dropdown-menu">
			<li><a href='?assignees='>Clear filter</a></li>
			<li><a href='?assignees=all'>All assignees</a></li>
		<? foreach ($collaborators as $id => $name) {
			$class = in_array($id, array_keys($selected_assignees))? ' class="active"': '';
			?>
			<li<?=$class?>><a href='?assignees=<?=$id?>'><?=$name?></a></li>
		<? } ?>
    	</ul>
    </li>

    <li class="dropdown">
    	<a class="dropdown-toggle" data-toggle="dropdown"
    	href="#">
    		<?=__("Created by")?> <b class="caret"></b>
    	</a>
    	<ul class="dropdown-menu">
			<li><a href='?creators='>Clear filter</a></li>
			<li><a href='?creators=all'>All creators</a></li>
		<? foreach ($collaborators as $id => $name) {
			$class = in_array($id, array_keys($selected_creators))? ' class="active"': '';
		?>
			<li<?=$class?>><a href='?creators=<?=$id?>'><?=$name?></a></li>
		<? } ?>
    	</ul>
    </li>

	<li class="active pull-right">
	  <a href="add"><?=__("Create task")?></a>
	</li>
</ul>

