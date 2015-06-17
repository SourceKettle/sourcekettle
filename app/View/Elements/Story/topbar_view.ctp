<?php
/**
 *
 * Element for displaying the task topbar in project view for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2015
 * @link		  http://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Elements.Project
 * @since		 SourceKettle v 1.7
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

 $options = array(
	'links' => array(
		array(
			'text' => __('List stories'),
			'url' => array(
				'action' => '.',
				'controller' => 'stories',
			),
		),
		/*array(
			'text' => __('Story map'),
			'url' => array(
				'action' => 'map',
				'controller' => 'stories',
			),
		),*/
		array(
			'text' => __('Edit story'),
			'url' => array(
				'action' => 'edit',
				'controller' => 'stories',
				$story['Story']['public_id'],
			),
		),
		array(
			'text' => __('New User Story'),
			'url' => array(
				'action' => 'add',
				'controller' => 'stories',
			),
			'active' => true,
			'pull-right' => true,
		),
	),
);

echo $this->element('Topbar/pills', array('options' => $options, 'span' => 12));
