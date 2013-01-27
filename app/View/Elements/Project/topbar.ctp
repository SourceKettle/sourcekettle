<?php
/**
 *
 * Element for displaying the task topbar in project view for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Project
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
                    'controller' => 'projects',
                ),
            ),
            array(
                'text' => $this->DT->t('topbar.public.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'public_projects',
                    'controller' => 'projects',
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
                    'controller' => 'projects',
                ),
                'props' => array('class' => 'btn-primary'),
            ),
        ),
    ),
);

echo $this->element('Topbar/button', array('options' => $options, 'span' => 12));
