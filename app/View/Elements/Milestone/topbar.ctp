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

if(isset($name)){
	$pl = $this->Text->truncate(h($name), 20);
} else{
	$pl = '';
}

$action = $this->request['action'];
// Viewing/editing a Milestone: give options to change it
if($action == 'view' || $action == 'plan' || $action == 'edit' || $action == 'burndown'){
	$left = array(
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
		   'props' => array('class' => 'btn-danger'),
	   ),
	);

	if(!$milestone['Milestone']['is_open']) {
		$left[3] = array(
		   'text' => __('Re-Open'),
		   'url' => array(
			   'action' => 'reopen',
			   'controller' => 'milestones',
			   $id
		   ),
		);
	}

	if($action == 'edit') {
		$right = array();
	} else {
		$right = array(
			array(
				'text' => __('Create task'),
				'url' => array(
					'action' => 'add',
					'controller' => 'tasks',
					'?' => array(
						'milestone' => $id,
					),
				),
				'props' => array('class' => 'btn-primary'),
			 ),
		);
	}

// No specific milestone (index): filter open/closed milestones
} else {
	$left = array(
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
	);

	$right = array(
		array(
			'text' => __('Create milestone'),
			'url' => array(
				'action' => 'add',
				'controller' => 'milestones',
			),
			'props' => array('class' => 'btn-primary'),
		 ),
	);
}




$options = array(
	'left' => array($left),
	'right' => array($right),
);

echo $this->element('Topbar/button', array('options' => $options, 'pl' => $pl));
