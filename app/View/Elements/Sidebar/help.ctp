<?php

/**
 *
 * Element for displaying the project sidebar in the help pages for the DevTrack system
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
        'Project' => array(
            'Overview' => array(
                'icon' => 'home',
                'url' => array(
                    'action' => array('project'),
                    'controller' => 'help',
                ),
            ),
            'Time' => array(
                'icon' => 'book',
                'url' => array(
                    'action' => array('time'),
                    'controller' => 'help',
                ),
            ),
            'Source' => array(
                'icon' => 'pencil',
                'url' => array(
                    'action' => array('source'),
                    'controller' => 'help',
                ),
            ),
            'Tasks' => array(
                'icon' => 'file',
                'url' => array(
                    'action' => array('tasks'),
                    'controller' => 'help',
                ),
            ),
            'Milestones' => array(
                'icon' => 'road',
                'url' => array(
                    'action' => array('milestones'),
                    'controller' => 'help',
                ),
            ),
            'Attachments' => array(
                'icon' => 'download',
                'url' => array(
                    'action' => array('attachments'),
                    'controller' => 'help',
                ),
            ),
        ),
        'Project Admin' => array(
            'Collaborators' => array(
            'icon' => 'user',
            'url' => array(
                'action' => 'collaborators',
                'controller' => 'help',
            ),
        ),
            'Settings' => array(
                'icon' => 'cog',
                'url' => array(
                    'action' => 'settings',
                    'controller' => 'help',
                ),
            ),
        ),
        'Your Account' => array(
            'Basic details' => array(
                'icon' => 'user',
                'url' => array(
                    'action' => 'details',
                    'controller' => 'help',
                ),
            ),
            'Change Password' => array(
                'icon' => 'lock',
                'url' => array(
                    'action' => 'security',
                    'controller' => 'help',
                ),
            ),
            'Change Theme' => array(
                'icon' => 'glass',
                'url' => array(
                    'action' => 'theme',
                    'controller' => 'help',
                ),
            ),
            'Delete Account' => array(
                'icon' => 'remove',
                'url' => array(
                    'action' => 'delete',
                    'controller' => 'help',
                ),
            ),
        ),
        'SSH Keys' => array(
            'Add Key' => array(
                'icon' => 'plus-sign',
                'url' => array(
                    'action' => 'addkey',
                    'controller' => 'help',
                ),
            ),
            'Edit Keys' => array(
                'icon' => 'minus-sign',
                'url' => array(
                    'action' => 'viewkeys',
                    'controller' => 'help',
                ),
            ),
        ),
    );

    echo $this->element('Sidebar/generic', array('options' => $options));
