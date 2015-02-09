<?php
/**
 *
 * Element for displaying the source topbar for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link		  http://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Elements.Source
 * @since		 SourceKettle v 0.1
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$renderableBranches = array(__('Branches') => array(), __('Tags') => array());
foreach ($branches as $br) {
	$renderableBranches[__('Branches')][] = array('text' => $br, 'url' => array('project' => $project['Project']['name'], $br));
}
foreach ($tags as $tag) {
	$renderableBranches[__('Tags')][] = array('text' => "$tag", 'url' => array('project' => $project['Project']['name'], "tags/$tag"));
}

// Set title of current branch/tag
if (preg_match("/^tags\/(.+)$/", $branch, $matches)) {
	$branchTitle = __("Tag: <strong>%s</strong>", $matches[1]);
} else {
	$branchTitle = __("Branch: <strong>%s</strong>", $branch);
}

 $options = array(
	'links' => array(
		array(
			'text' => __("Source"),
			'url' => array(
				'action' => 'tree',
				'controller' => 'source',
				$branch,
			),
		),
		array(
			'text' => __("Commits"),
			'url' => array(
				'action' => 'commits',
				'controller' => 'source',
				$branch,
			),
		),
		array(
			'text' => $branchTitle,
			'dropdown' => $renderableBranches,
			'pull-right' => true,
		),
		array(
			'text' => __("Get The Code"),
			'url' => array(
				'action' => 'gettingStarted',
				'controller' => 'source',
			),
			'active' => true,
			'pull-right' => true,
		),
	),
);

echo $this->element('Topbar/pills', array('options' => $options));
