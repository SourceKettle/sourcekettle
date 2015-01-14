<?php
/**
 *
 * Element for displaying the task topbar in project view for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link		  http://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Elements.Project
 * @since		 SourceKettle v 0.1
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

 $options = array(
	'links' => array(
		array(
			'text' => __('My projects'),
			'url' => array(
				'action' => '.',
				'controller' => 'projects',
			),
		),
		array(
			'text' => __('Team projects'),
			'url' => array(
				'action' => 'team_projects',
				'controller' => 'projects',
			),
		),
		array(
			'text' => __('Public projects'),
			'url' => array(
				'action' => 'public_projects',
				'controller' => 'projects',
			),
		),
		array(
			'text' => __('New Project'),
			'url' => array(
				'action' => 'add',
				'controller' => 'projects',
			),
			'active' => true,
			'pull-right' => true,
		),
	),
);

echo $this->element('Topbar/pills', array('options' => $options, 'span' => 12));
