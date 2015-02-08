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
$renderableBranches = array();
foreach ($branches as $branch) {
	$renderableBranches[] = array('text' => $branch, 'url' => array('project' => $project['Project']['name'], 'action' => 'tree', $branch));
}
foreach ($tags as $tag) {
	$renderableBranches[] = array('text' => "tags/$tag", 'url' => array('project' => $project['Project']['name'], 'action' => 'tree', "tags/$tag"));
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
			'text' => __("Branch: ")."<strong>".$branch."</strong>",
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
