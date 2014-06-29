<?php
/**
 *
 * Element for displaying the task topbar for the DevTrack system
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
 $pl = $this->DT->t('bar.task').$id;

if ($this->request->data['Task']['task_status_id'] != 4) {
     $state = array(
         'text' => __("Close task"),
         'url' => '#closeModal',
         'props' => array("data-toggle" => "modal", "class" => "btn-success")
     );
 } else {
     $state =  array(
         'text' => __("Re-open task"),
         'url' => array(
            'action' => 'opentask',
            'controller' => 'tasks',
            $id
         ),
         'props' => array("class" => "btn-info")
     );
 }

 $options = array(
    'back' => $previousPage,
    'left' => array(
        array(
            array(
                'text' => __("View"),
                'url' => array(
                    'action' => 'view',
                    'controller' => 'tasks',
                    $id
                ),
            ),
            $state,
        ),
    ),
	'right' => array()
);

echo $this->element('Topbar/button', array('options' => $options, 'pl' => $pl));
