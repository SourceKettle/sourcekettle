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
                'text' => __("All Attachments"),
                'url' => array(
                    'action' => '.',
                    'controller' => 'attachments',
                ),
            ),
        ),
        array(
            array(
                'text' => __("Images"),
                'url' => array(
                    'action' => 'image',
                    'controller' => 'attachments',
                ),
            ),
            array(
                'text' => __("Videos"),
                'url' => array(
                    'action' => 'video',
                    'controller' => 'attachments',
                ),
            ),
            array(
                'text' => __("Text"),
                'url' => array(
                    'action' => 'text',
                    'controller' => 'attachments',
                ),
            ),
            array(
                'text' => __("Others"),
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
                'text' => __("Upload file"),
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
