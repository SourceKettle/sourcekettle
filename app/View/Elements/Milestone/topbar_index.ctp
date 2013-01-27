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
 $options = array(
    'left' => array(
        array(
            array(
                'text' => $this->DT->t('topbar.option1.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'open',
                    'controller' => 'milestones',
                ),
            ),
            array(
                'text' => $this->DT->t('topbar.option2.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'closed',
                    'controller' => 'milestones',
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
                    'controller' => 'milestones',
                ),
                'props' => array('class' => 'btn-primary'),
            ),
        ),
    ),
);

echo $this->element('Topbar/button', array('options' => $options));
