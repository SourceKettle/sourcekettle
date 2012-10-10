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
                'text' => $this->DT->t('topbar.index.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => '.',
                    'controller' => 'tasks',
                ),
            ),
            array(
                'text' => $this->DT->t('topbar.others.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'others',
                    'controller' => 'tasks',
                ),
            ),
            array(
                'text' => $this->DT->t('topbar.nobody.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'nobody',
                    'controller' => 'tasks',
                ),
            ),
            array(
                'text' => $this->DT->t('topbar.all.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'all',
                    'controller' => 'tasks',
                ),
            ),
        ),
    ),
    'right' => array(
    ),
);

echo $this->element('Topbar/button', array('options' => $options));
