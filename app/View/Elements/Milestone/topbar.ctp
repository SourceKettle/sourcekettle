<?php
/**
 *
 * Element for displaying the milestone topbar for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Topbar
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

if(isset($name)){
	$pl = $this->Text->truncate(h($name), 20);
} else{
	$pl = '';
}
// Viewing/editing a Milestone: give options to change it
if($this->request['action'] == 'view' || $this->request['action'] == 'edit'){
	$left = array(
       array(
           'text' => __('Edit'),
           'url' => array(
               'action' => 'edit',
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

	if($this->request['action'] == 'edit') {
		$left[0] = array(
           'text' => __('View'),
           'url' => array(
               'action' => 'view',
               'controller' => 'milestones',
               $id
           ),
    	);
	}

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
    'back' => $previousPage,
    'left' => array($left),
    'right' => array($right),
);

echo $this->element('Topbar/button', array('options' => $options, 'pl' => $pl));
