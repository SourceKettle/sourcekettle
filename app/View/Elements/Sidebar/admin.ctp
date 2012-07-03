<?php

/**
 *
 * Element for displaying the admin sidebar for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
    $options = array(
        'Administration' => array(
            'Overview' => array(
                'icon' => 'fullscreen',
                'url' => array(
                    'action' => 'admin_index',
                    'controller' => 'admin',
                ),
            ),
            'Settings' => array(
                'icon' => 'warning-sign',
                'url' => array(
                    'action' => 'admin_index',
                    'controller' => 'settings',
                ),
            ),
        ),
        'Users' => array(
            'Search' => array(
                'icon' => 'search',
                'url' => array(
                    'action' => 'admin_index',
                    'controller' => 'users',
                ),
            ),
            'Add' => array(
                'icon' => 'user',
                'url' => array(
                    'action' => 'admin_add',
                    'controller' => 'users',
                ),
            ),
        ),
        'Projects' => array(
            'Search' => array(
                'icon' => 'search',
                'url' => array(
                    'action' => 'admin_index',
                    'controller' => 'projects',
                ),
            ),
            'Add' => array(
                'icon' => 'file',
                'url' => array(
                    'action' => 'admin_add',
                    'controller' => 'projects',
                ),
            ),
        ),
        'help' => array(
            'action' => '.',
        ),
    );

    echo $this->element('Sidebar/generic', array('options' => $options));
