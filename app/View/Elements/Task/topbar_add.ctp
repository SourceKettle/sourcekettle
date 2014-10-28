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
                'text' => __('Assigned to you'),
                'url' => array(
                    'action' => '.',
                    'controller' => 'tasks',
                ),
            ),
            array(
                'text' => __('Assigned to others'),
                'url' => array(
                    'action' => 'others',
                    'controller' => 'tasks',
                ),
            ),
            array(
                'text' => __('Assigned to nobody'),
                'url' => array(
                    'action' => 'nobody',
                    'controller' => 'tasks',
                ),
            ),
            array(
                'text' => __('All tasks'),
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
