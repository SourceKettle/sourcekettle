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
 $options = array(
    $this->DT->t('topbar.option1.text') => array(
        'url' => array(
            'action' => '.',
            'controller' => 'tasks',
        ),
    ),
    $this->DT->t('topbar.option2.text') => array(
        'url' => array(
            'action' => 'classic',
            'controller' => 'tasks',
        ),
    ),
    $this->DT->t('topbar.option3.text') => array(
        'align' => 'right',
        'url' => '#addTaskModal',
        'data-toggle' => 'modal',
        'class' => 'btn-primary',
    ),
    $this->DT->t('topbar.option4.text') => array(
        'align' => 'right',
        'url' => array(
            'action' => 'admin_index',
            'controller' => 'admin',
        ),
        'dropdown' => array(
            /* Example milestone
            '1' => array(
                'action' => 'milestone',
                'controller' => 'tasks',
                'value' => 1
            ),
            */
        ),
    ),
);

echo $this->element('Topbar/generic', array('options' => $options));
echo $this->element('Task/modal_add');
