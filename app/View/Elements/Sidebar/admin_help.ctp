<?php

/**
 *
 * Element for displaying the admin sidebar for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
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
                    'controller' => 'help',
                ),
            ),
            'Settings' => array(
                'icon' => 'warning-sign',
                'url' => array(
                    'action' => 'admin_settings',
                    'controller' => 'help',
                ),
            ),
        ),
        'Users' => array(
            'Search' => array(
                'icon' => 'search',
                'url' => array(
                    'action' => 'admin_usersearch',
                    'controller' => 'help',
                ),
            ),
            'Add' => array(
                'icon' => 'user',
                'url' => array(
                    'action' => 'admin_useradd',
                    'controller' => 'help',
                ),
            ),
        ),
        'Projects' => array(
            'Search' => array(
                'icon' => 'search',
                'url' => array(
                    'action' => 'admin_projectsearch',
                    'controller' => 'help',
                ),
            ),
            'Add' => array(
                'icon' => 'file',
                'url' => array(
                    'action' => 'admin_projectadd',
                    'controller' => 'help',
                ),
            ),
        ),
        'help' => array(
            'action' => '.',
        ),
    );

    echo $this->element('Sidebar/generic', array('options' => $options));
