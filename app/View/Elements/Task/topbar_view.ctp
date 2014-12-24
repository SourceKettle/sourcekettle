<?php
/**
 *
 * Element for displaying the task topbar for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Topbar
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
 $pl = __('Task')." #$id";

if ($task['Task']['assignee_id'] != null) {
    if ($task['TaskStatus']['name'] == 'open') {
        $progress = array(
            'text' => __('Start progress'),
            'url' => array(
                'action' => 'starttask',
                'controller' => 'tasks',
                $id
            ),
        );
    } else if ($task['TaskStatus']['name'] == 'in progress') {
        $progress = array(
            'text' => __('Stop progress'),
            'url' => array(
                'action' => 'stoptask',
                'controller' => 'tasks',
                $id
            ),
        );
    } else {
        $progress = false;
    }
} else {
    $progress = false;
}
if ($task['TaskStatus']['name'] != 'closed') {
     $state = array(
         'text' => __('Close task'),
         'url' => '#closeModal',
         'props' => array("data-toggle" => "modal")
     );
 } else {
     $state =  array(
         'text' => __('Re-open task'),
         'url' => array(
            'action' => 'opentask',
            'controller' => 'tasks',
            $id
         ),

         'props' => array("class" => "btn-info")
     );
 }

 if (in_array($task['TaskStatus']['name'], array('open', 'in progress'))) {
    $resolve = array(
        'text' => __('Resolve'),
        'url' => '#resolveModal',
        'props' => array('data-toggle' => 'modal')
    );
} else if ($task['TaskStatus']['name'] == 'resolved'){
    $resolve = array(
        'text' => __('Un-resolve'),
        'url' => '#unresolveModal',
        'props' => array('data-toggle' => 'modal')
    );
 } else {
     $resolve = '';
 }

 $options = array(
    'links' => array(
        array(
            array(
                'text' => __('Edit'),
                'url' => array(
                    'action' => 'edit',
                    'controller' => 'tasks',
                    $id
                ),
            ),
            array(
                'text' => __('Assign'),
                'url' => '#assignModal',
                'props' => array('data-toggle' => 'modal'),
            ),
            $progress,
            $resolve,
            $state,
        ),
        array(
            array(
                'text' => __('Create Task'),
                'url' => array(
                    'action' => 'add',
                    'controller' => 'tasks',
                ),
				'active' => true,
				'pull-right' => true,
            ),
        ),
        array(
            array(
                'text' => __('Create Subtask'),
                'url' => array(
                    'action' => 'add',
                    'controller' => 'tasks',
					'?' => array('parent' => $id),
                ),
				'pull-right' => true,
            ),
        ),
    ),
);

echo $this->element('Topbar/pills', array('options' => $options, 'pl' => $pl));
