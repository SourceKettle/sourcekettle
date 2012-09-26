<?php
/**
 *
 * Element for displaying the time topbar for the DevTrack system
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
 $pl = $this->DT->t('bar.time').$id;

 $options = array(
    'back' => $previousPage,
    'left' => array(
        array(
            array(
                'text' => $this->DT->t('bar.view'),
                'url' => array(
                    'action' => 'view',
                    'controller' => 'times',
                    $id
                ),
            ),
            array(
                'text' => $this->DT->t('bar.delete'),
                'url' => array(
                    'action' => 'delete',
                    'controller' => 'times',
                    $id
                ),
                'props' => array('style' => 'danger'),
            ),
        ),
    ),
    'right' => array(
        array(
            array(
                'text' => $this->DT->t('topbar.create.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'add',
                    'controller' => 'times',
                ),
                'props' => array('class' => 'btn-primary'),
            ),
        ),
    ),
);

echo $this->element('Topbar/button', array('options' => $options, 'pl' => $pl));
