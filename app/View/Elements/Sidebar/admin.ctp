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
            'Approvals' => array(
                'icon' => 'check',
                'url' => array(
                    'action' => 'admin_approve',
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
                    'action' => 'add',
                    'controller' => 'projects',
					'admin' => false,
                ),
            ),
        ),
        'help' => array(
            'action' => '.',
        ),
    );

	if ( Configure::read('debug') > 0 ) {
		$options['Administration']['Unit tests'] = array(
			'icon' => 'fire',
			'url' => '/test.php' // TODO can we route this properly?
		);
	}

    echo $this->element('Sidebar/generic', array('options' => $options));
