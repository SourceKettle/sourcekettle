<?php
/**
 *
 * Shows a jQuery UI set of linked lists
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2014
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Task
 * @since         SourceKettle v 1.5
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css("linked_list", null, array ('inline' => false));
$this->Html->script("jquery-ui.min", array ('inline' => false));
$this->Html->script("jquery.ui.touch-punch.min", array ('inline' => false));
echo $this->Html->script("linked_list", array ('inline' => false));

if (!isset($classes)) {
	$classes = '';
}

if (!isset($listSpan)) {
	$listSpan = 12;
}

if (!isset($itemSpan)) {
	$itemSpan = 12;
}

// This is a unique-per-page class we'll use to link our lists together.
// If a page has multiple sets of linked lists, these need to be different
// so that the different list sets don't all get linked together.
if (!isset($listSetName)) {
	$listSetName = 'linkedList';
}

foreach ($lists as $header => $list) {
	
	if (isset($list['classes'])) {
		$lclasses = $list['classes'];
	} else {
		$lclasses = $classes;
	}

	if (isset($list['tooltip'])) {
		$tooltip = ' title="'.$list['tooltip'].'"';
		$lclasses .= " tooltipped";
	} else {
		$tooltip = "";
	}

	if (isset($list['listSpan'])) {
		$lspan = $list['listSpan'];
	} else {
		$lspan = $listSpan;
	}

	if (isset($list['itemSpan'])) {
		$ispan = $list['itemSpan'];
	} else {
		$ispan = $itemSpan;
	}

	if (isset($list['id'])) {
		$id = ' id="'.$list['id'].'"';
	} else {
		$id = '';
	}

	echo "<div class='span$lspan'>\n";
	echo "<h4$tooltip>$header</h4>\n";
	echo "<hr />\n";
	echo "<ul$id class='$listSetName $lclasses' data-taskspan='$ispan'>\n";
	foreach ($list['items'] as $id => $item) {
		echo "  <li id='$id' class='ui-state-default' data-item-id='$id'>$item</li>";
	}
	echo "</ul>\n";
	echo "</div>\n";
}

// Javascript to connect everything up
echo $this->Html->scriptBlock("linkedList($('.$listSetName'), '.$listSetName');", array ('inline' => false));
