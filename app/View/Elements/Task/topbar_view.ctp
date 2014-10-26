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
 $pl = $this->DT->t('bar.task').$id;

if ($task['Task']['assignee_id'] != null) {
    if ($task['TaskStatus']['name'] == 'open') {
        $progress = array(
            'text' => 'Start progress',
            'url' => array(
                'action' => 'starttask',
                'controller' => 'tasks',
                $id
            ),
        );
    } else if ($task['TaskStatus']['name'] == 'in progress') {
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
if ($task['TaskStatus']['name'] != 'closed') {
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

 if (in_array($task['TaskStatus']['name'], array('open', 'in progress'))) {
    $resolve = array(
        'text' => $this->DT->t('bar.resolve'),
        'url' => '#resolveModal',
        'props' => array('data-toggle' => 'modal')
    );
} else if ($task['TaskStatus']['name'] == 'resolved'){
    $resolve = array(
        'text' => $this->DT->t('bar.unresolve'),
        'url' => '#unresolveModal',
        'props' => array('data-toggle' => 'modal')
    );
 } else {
     $resolve = '';
 }

 $options = array(
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
