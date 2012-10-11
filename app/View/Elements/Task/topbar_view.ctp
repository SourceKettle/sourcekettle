<?php
/**
 *
 * Element for displaying the task topbar for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements.Topbar
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
 $pl = $this->DT->t('bar.task').$id;

if ($task['Task']['assignee_id'] != null) {
    if ($task['Task']['task_status_id'] == 1) {
        $progress = array(
            'text' => 'Start progress',
            'url' => array(
                'action' => 'starttask',
                'controller' => 'tasks',
                $id
            ),
        );
    } else if ($task['Task']['task_status_id'] == 2) {
        $progress = array(
            'text' => 'Stop progress',
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
if ($task['Task']['task_status_id'] != 4) {
     $state = array(
         'text' => $this->DT->t('bar.close'),
         'url' => '#closeModal',
         'props' => array("data-toggle" => "modal", "class" => "btn-success")
     );
 } else {
     $state =  array(
         'text' => $this->DT->t('bar.open'),
         'url' => array(
            'action' => 'opentask',
            'controller' => 'tasks',
            $id
         ),

         'props' => array("class" => "btn-info")
     );
 }

 if ($task['Task']['task_status_id'] <= 2) {
    $resolve = array(
        'text' => $this->DT->t('bar.resolve'),
        'url' => '#resolveModal',
        'props' => array('data-toggle' => 'modal')
    );
 } else {
     $resolve = '';
 }

 $options = array(
    'back' => $previousPage,
    'left' => array(
        array(
            array(
                'text' => $this->DT->t('bar.edit'),
                'url' => array(
                    'action' => 'edit',
                    'controller' => 'tasks',
                    $id
                ),
            ),
            array(
                'text' => $this->DT->t('bar.assign'),
                'url' => '#assignModal',
                'props' => array('data-toggle' => 'modal'),
            ),
            $progress,
            $resolve,
            $state,
        ),
    ),
    'right' => array(
        array(
            array(
                'text' => $this->DT->t('topbar.create.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'add',
                    'controller' => 'tasks',
                ),
                'props' => array('class' => 'btn-primary'),
            ),
        ),
    ),
);

echo $this->element('Topbar/button', array('options' => $options, 'pl' => $pl));
