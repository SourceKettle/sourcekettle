<?php

/**
 *
 * Element for displaying the user sidebar for the DevTrack system
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
        'Your Account' => array(
            'Basic details' => array(
                'icon' => 'user',
                'url' => array(
                    'action' => 'details',
                    'controller' => 'users',
                ),
            ),
            'Change Password' => array(
                'icon' => 'lock',
                'url' => array(
                    'action' => 'security',
                    'controller' => 'users',
                ),
            ),
            'Change Theme' => array(
                'icon' => 'glass',
                'url' => array(
                    'action' => 'theme',
                    'controller' => 'users',
                ),
            ),
            'Delete Account' => array(
                'icon' => 'remove',
                'url' => array(
                    'action' => 'delete',
                    'controller' => 'users',
                ),
            ),
        ),
        'SSH Keys' => array(
            'Add Key' => array(
                'icon' => 'plus-sign',
                'url' => array(
                    'action' => 'add',
                    'controller' => 'sshKeys',
                ),
            ),
            'Edit Keys' => array(
                'icon' => 'minus-sign',
                'url' => array(
                    'action' => 'view',
                    'controller' => 'sshKeys',
                ),
            ),
        ),
        'help' => array(
            'action' => 'user',
        ),
    );

    echo $this->element('Sidebar/generic', array('options' => $options));
