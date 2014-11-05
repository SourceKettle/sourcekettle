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
        __('Your Account') => array(
            __('Basic details') => array(
                'icon' => 'user',
                'url' => array(
                    'action' => 'details',
                    'controller' => 'users',
                ),
            ),
            __('Change Password') => array(
                'icon' => 'lock',
                'url' => array(
                    'action' => 'security',
                    'controller' => 'users',
                ),
            ),
            __('Change Theme') => array(
                'icon' => 'glass',
                'url' => array(
                    'action' => 'theme',
                    'controller' => 'users',
                ),
            ),
            __('Delete Account') => array(
                'icon' => 'remove',
                'url' => array(
                    'action' => 'delete',
                    'controller' => 'users',
                ),
            ),
        ),
        __('SSH Keys') => array(
            __('Add Key') => array(
                'icon' => 'plus-sign',
                'url' => array(
                    'action' => 'add',
                    'controller' => 'sshKeys',
                ),
            ),
            __('Edit Keys') => array(
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
    if ($current_user['is_internal']) {
        unset($options[__('Your Account')][__('Delete Account')]);
        unset($options[__('Your Account')][__('Change Password')]);
    }

    
    echo $this->element('Sidebar/generic', array('options' => $options));
