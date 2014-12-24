<?php
/**
 *
 * Element for displaying the milestone topbar for the SourceKettle system

 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link		  http://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Elements.Topbar
 * @since		 SourceKettle v 0.1
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$action = $this->request['action'];

// Viewing/editing a Milestone: give options to change it
if($action == 'view' || $action == 'plan' || $action == 'edit' || $action == 'burndown'){
	$links = array(
		array(
		   'text' => __('View'),
		   'url' => array(
			   'action' => 'view',
			   'controller' => 'milestones',
			   $id
		   ),
		),
	   	array(
		   'text' => __('Chart'),
		   'url' => array(
			   'action' => 'burndown',
			   'controller' => 'milestones',
			   $id
		   ),
	   	),
	   array(
		   'text' => __('Edit'),
		   'url' => array(
			   'action' => 'edit',
			   'controller' => 'milestones',
			   $id
		   ),
	   ),
	   array(
		   'text' => __('Plan'),
		   'url' => array(
			   'action' => 'plan',
			   'controller' => 'milestones',
			   $id
		   ),
	   ),
	   array(
		   'text' => __('Close'),
		   'url' => array(
			   'action' => 'close',
			   'controller' => 'milestones',
			   $id
		   ),
	   ),
	   array(
		   'text' => __('Delete'),
		   'url' => array(
			   'action' => 'delete',
			   'controller' => 'milestones',
			   $id
		   ),
		   'props' => array('class' => 'danger'), // TODO fix this make it red and all that
	   ),
	);

	if(!$milestone['Milestone']['is_open']) {
		array_slice($links, 3, 1);
		$links[3] = array(
		   'text' => __('Re-Open'),
		   'url' => array(
			   'action' => 'reopen',
			   'controller' => 'milestones',
			   $id
		   ),
		);
	}

	if($action != 'edit') {
		$links[] = array(
			'text' => __('Create task'),
			'url' => array(
				'action' => 'add',
				'controller' => 'tasks',
				'?' => array(
					'milestone' => $id,
				),
			),
			'pull-right' => true,
			'active' => true,
		);
	}

// No specific milestone (index): filter open/closed milestones
} else {
	$links = array(
		array(
			'text' => __('Open milestones'),
			'url' => array(
				'action' => 'open',
				'controller' => 'milestones',
			),
		),
		array(
			'text' => __('Closed milestones'),
			'url' => array(
				'action' => 'closed',
				'controller' => 'milestones',
			),
		),
		array(
			'text' => __('Create milestone'),
			'url' => array(
				'action' => 'add',
				'controller' => 'milestones',
			),
			'props' => array('class' => 'btn-primary'),
			'pull-right' => true,
		 ),
	);
}

echo $this->element('Topbar/pills', array('options' => array('links' => $links)));
