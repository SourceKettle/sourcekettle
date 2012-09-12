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
    'left' => array(
        array(
            array(
                'text' => $this->DT->t('topbar.option1.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => '.',
                    'controller' => 'tasks',
                ),
            ),
            array(
                'text' => $this->DT->t('topbar.option2.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'others',
                    'controller' => 'tasks',
                ),
            ),
            array(
                'text' => $this->DT->t('topbar.option3.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'watching',
                    'controller' => 'tasks',
                ),
            ),
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

echo $this->element('Topbar/button', array('options' => $options));
