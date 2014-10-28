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
	<li><a href="?">(clear)</a></li>

    <li class="dropdown">
    	<a class="dropdown-toggle" data-toggle="dropdown"
    	href="#">
    		<?=__("Milestone")?> <b class="caret"></b>
    	</a>
    	<ul class="dropdown-menu">
			<?// NB filters are pre-urlencoded... ?>
			<li><a href='?<?=$filter_urls['milestone']['clear']?>'>Clear filter</a></li>
			<li><a href='?<?=$filter_urls['milestone']['all']?>'>All milestones</a></li>
			<li><a href='?<?=$filter_urls['milestone']['none']?>'>Tasks with no milestone</a></li>
		<? foreach ($milestones as $id => $milestone) {
			$class = in_array($id, array_keys($selected_milestones))? ' class="active"': '';
			?>
			<li<?=$class?>><a href='?<?=$filter_urls['milestone'][$id]?>'><?=h($milestone)?></a></li>
		<? } ?>
    	</ul>
    </li>

    <li class="dropdown">
    	<a class="dropdown-toggle" data-toggle="dropdown"
    	href="#">
    		<?=__("Status")?> <b class="caret"></b>
    	</a>
    	<ul class="dropdown-menu">
			<li><a href='?<?=$filter_urls['status']['clear']?>'>Clear filter</a></li>
			<li><a href='?<?=$filter_urls['status']['all']?>'>All statuses</a></li>
		<? foreach ($statuses as $id => $name) {
			$class = in_array($id, array_keys($selected_statuses))? ' class="active"': '';
			?>
			<li<?=$class?>><a href='?<?=$filter_urls['status'][$id]?>'><?=h($name)?></a></li>
		<? } ?>
    	</ul>
    </li>

    <li class="dropdown">
    	<a class="dropdown-toggle" data-toggle="dropdown"
    	href="#">
    		<?=__("Priority")?> <b class="caret"></b>
    	</a>
    	<ul class="dropdown-menu">
			<li><a href='?<?=$filter_urls['priority']['clear']?>'>Clear filter</a></li>
			<li><a href='?<?=$filter_urls['priority']['all']?>'>All priorities</a></li>
		<? foreach ($priorities as $id => $name) {
			$class = in_array($id, array_keys($selected_priorities))? ' class="active"': '';
		?>
			<li<?=$class?>><a href='?<?=$filter_urls['priority'][$id]?>'><?=h($name)?></a></li>
		<? } ?>
    	</ul>
    </li>

    <li class="dropdown">
    	<a class="dropdown-toggle" data-toggle="dropdown"
    	href="#">
    		<?=__("Type")?> <b class="caret"></b>
    	</a>
    	<ul class="dropdown-menu">
			<li><a href='?<?=$filter_urls['type']['clear']?>'>Clear filter</a></li>
			<li><a href='?<?=$filter_urls['type']['all']?>'>All types</a></li>
		<? foreach ($types as $id => $name) {
			$class = in_array($id, array_keys($selected_types))? ' class="active"': '';
			?>
			<li<?=$class?>><a href='?<?=$filter_urls['type'][$id]?>'><?=h($name)?></a></li>
		<? } ?>
    	</ul>
    </li>

    <li class="dropdown">
    	<a class="dropdown-toggle" data-toggle="dropdown"
    	href="#">
    		<?=__("Assigned to")?> <b class="caret"></b>
    	</a>
    	<ul class="dropdown-menu">
			<li><a href='?<?=$filter_urls['assignee']['clear']?>'>Clear filter</a></li>
			<li><a href='?<?=$filter_urls['assignee']['all']?>'>All assignees</a></li>
			<li><a href='?<?=$filter_urls['assignee']['none']?>'>Tasks with no assignee</a></li>
		<? foreach ($collaborators as $id => $name) {
			$class = in_array($id, array_keys($selected_assignees))? ' class="active"': '';
			?>
			<li<?=$class?>><a href='?<?=$filter_urls['assignee'][$id]?>'><?=$name?></a></li>
		<? } ?>
    	</ul>
    </li>

    <li class="dropdown">
    	<a class="dropdown-toggle" data-toggle="dropdown"
    	href="#">
    		<?=__("Created by")?> <b class="caret"></b>
    	</a>
    	<ul class="dropdown-menu">
			<li><a href='?<?=$filter_urls['creator']['clear']?>'>Clear filter</a></li>
			<li><a href='?<?=$filter_urls['creator']['all']?>'>All creators</a></li>
		<? foreach ($collaborators as $id => $name) {
			$class = in_array($id, array_keys($selected_creators))? ' class="active"': '';
		?>
			<li<?=$class?>><a href='?<?=$filter_urls['creator'][$id]?>'><?=$name?></a></li>
		<? } ?>
    	</ul>
    </li>

	<li class="active pull-right">
	  <a href="add"><?=__("Create task")?></a>
	</li>
</ul>

