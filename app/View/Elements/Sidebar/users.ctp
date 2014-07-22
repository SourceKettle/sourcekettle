<?php

/**
 *
 * Element for displaying the user sidebar for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements
 * @since         SourceKettle v 0.1
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
            'action' => 'details',
        ),
    );

    // If we are logged in with a non-sourcekettle-managed account,
    // do not offer the 'delete account' link or 'change password' link.
    if ($current_user['__is_internal']) {
        unset($options['Your Account']['Delete Account']);
        unset($options['Your Account']['Change Password']);
    }

    
    echo $this->element('Sidebar/generic', array('options' => $options));
