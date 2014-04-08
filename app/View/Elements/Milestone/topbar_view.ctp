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
 $pl = $this->Text->truncate(h($name), 20);

 $options = array(
    'back' => $previousPage,
    'left' => array(
        array(
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
        ),
    ),
    'right' => array(
        array(
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
        ),
    ),
);

echo $this->element('Topbar/button', array('options' => $options, 'pl' => $pl));
