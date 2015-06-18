<?php
/**
 *
 * Generic TopBar Element for the SourceKettle system
 * Provides a bootstrap "pills" navigation bar for the top of pages
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2014
 * @link		  http://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Elements.Topbar
 * @since		 SourceKettle v 1.5
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<? if (isset($span)){
	echo "<div id='topbar' class='span" . $span . "'>";
} else {
	?>
	<div id="topbar" class="span12">
	<?
} ?>
	<ul class="nav nav-pills">
<?php
	$prefs = array(
		'collaborators' => array('icon' => 'user'),
		'times'		 => array('icon' => 'time'),
		'source'		=> array('icon' => 'pencil'),
		'tasks'		 => array('icon' => 'file'),
		'milestones'	=> array('icon' => 'road'),
	);

	foreach ($options['links'] as $option) {
		if ($option == null) continue;
		// Create correct URL
		if (isset($option['url']) && is_array($option['url'])) {
			$option['url']['project'] = $this->params['project'];
		}

		$properties = (isset($option['props'])) ? $option['props'] : array();
		$properties['escape'] = false;

		echo "<li class=\"";
		if (isset($option['active'])) echo ' active ';
		if (isset($option['dropdown'])) echo ' dropdown ';
		if (isset($option['pull-right'])) echo ' pull-right ';
		echo "\">";

		$icon = '';
		if (isset($option['icon-white'])) $icon = $this->Bootstrap->icon($option['icon-white'], 'white').' ';
		elseif (isset($option['icon'])) $icon = $this->Bootstrap->icon($option['icon']).' ';

		if (isset($option['dropdown'])) {
			$option['url'] = "#";
			$option['text'] .= ' <b class="caret"></b>';
			$properties["data-toggle"] = "dropdown";
			$properties["class"] = "dropdown-toggle";

			echo "<ul class='dropdown-menu'>";
			foreach ($option['dropdown'] as $title => $choice) {
				$choiceIcon = '';
				if (isset($choice['icon-white'])) $icon = $this->Bootstrap->icon($choice['icon-white'], 'white');
				elseif (isset($choice['icon'])) $icon = $this->Bootstrap->icon($choice['icon']);
				if (is_array($choice)) {
					echo "<strong>$title</strong>";
					echo "<ul>";
					foreach ($choice as $c) {
						echo "<li>".$this->Html->link($choiceIcon.$c['text'], $c['url'])."</li>";
					}
					echo "</ul>";
				} else {
					echo "<li>".$this->Html->link($choiceIcon.$choice['text'], $choice['url'])."</li>";
				}
			}

			echo "</ul>";
		}

		echo $this->Html->link($icon.$option['text'], $option['url'], $properties);
		echo "</li>";

	}
?>

	</ul>
</div>
