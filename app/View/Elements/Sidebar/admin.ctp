<?php

/**
 *
 * Element for displaying the admin sidebar for the SourceKettle system
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
        __('Administration') => array(
            __('Overview') => array(
                'icon' => 'fullscreen',
                'url' => array(
                    'action' => 'admin_index',
                    'controller' => 'admin',
                ),
            ),
            __('Settings') => array(
                'icon' => 'warning-sign',
                'url' => array(
                    'action' => 'admin_index',
                    'controller' => 'settings',
                ),
            ),
        ),
        __('Users') => array(
            __('Search') => array(
                'icon' => 'search',
                'url' => array(
                    'action' => 'admin_index',
                    'controller' => 'users',
                ),
            ),
            __('Add') => array(
                'icon' => 'user',
                'url' => array(
                    'action' => 'admin_add',
                    'controller' => 'users',
                ),
            ),
            __('Approvals') => array(
                'icon' => 'check',
                'url' => array(
                    'action' => 'admin_approve',
                    'controller' => 'users',
                ),
            ),
        ),
		__('Teams') => array(
			__('Search') => array(
                'icon' => 'search',
                'url' => array(
                    'action' => 'admin_index',
                    'controller' => 'teams',
                ),
			),
            __('Add') => array(
                'icon' => 'bullhorn',
                'url' => array(
                    'action' => 'admin_add',
                    'controller' => 'teams',
                ),
            ),
		),
        __('Projects') => array(
            __('Search') => array(
                'icon' => 'search',
                'url' => array(
                    'action' => 'admin_index',
                    'controller' => 'projects',
                ),
            ),
            __('Add') => array(
                'icon' => 'file',
                'url' => array(
                    'action' => 'add',
                    'controller' => 'projects',
					'admin' => false,
                ),
            ),
        ),
        __('Project Groups') => array(
            __('Search') => array(
                'icon' => 'search',
                'url' => array(
                    'action' => 'admin_index',
                    'controller' => 'project_groups',
                ),
            ),
            __('Add') => array(
                'icon' => 'th',
                'url' => array(
                    'action' => 'add',
                    'controller' => 'project_groups',
                ),
            ),
        ),
        'help' => array(
            'action' => '.',
        ),
    );

	if ( Configure::read('debug') > 0 ) {
		$options[__('Administration')][__('Unit tests')] = array(
			'icon' => 'fire',
			'url' => '/test.php' // TODO can we route this properly?
		);
	}

    echo $this->element('Sidebar/generic', array('options' => $options));
