<?php
/**
 *
 * Element for displaying the time topbar for the DevTrack system
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
 $options = array(
    'left' => array(
        array(
            array(
                'text' => $this->DT->t('topbar.history.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'history',
                    'controller' => 'times',
                ),
            ),
            array(
                'text' => $this->DT->t('topbar.users.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'users',
                    'controller' => 'times',
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
                    'controller' => 'times',
                ),
                'props' => array('class' => 'btn-primary'),
            ),
        ),
    ),
);

echo $this->element('Topbar/button', array('options' => $options));
