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

 $options = array(
    'left' => array(
        array(
            array(
                'text' => $this->DT->t('topbar.all.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => '.',
                    'controller' => 'attachments',
                ),
            ),
        ),
        array(
            array(
                'text' => $this->DT->t('topbar.images.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'image',
                    'controller' => 'attachments',
                ),
            ),
            array(
                'text' => $this->DT->t('topbar.videos.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'video',
                    'controller' => 'attachments',
                ),
            ),
            array(
                'text' => $this->DT->t('topbar.text.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'text',
                    'controller' => 'attachments',
                ),
            ),
            array(
                'text' => $this->DT->t('topbar.other.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'other',
                    'controller' => 'attachments',
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
                    'controller' => 'attachments',
                ),
                'props' => array('class' => 'btn-primary'),
            ),
        ),
    ),
);

echo $this->element('Topbar/button', array('options' => $options));
