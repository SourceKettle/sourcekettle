<?php

/**
 *
 * Element for displaying the project sidebar for the SourceKettle system
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
        __('Features') => array(
            __('Overview') => array(
                'icon' => 'home',
                'url' => array(
                    'action' => array('view','history'),
                    'controller' => 'projects',
                    'project' => $this->params['project'],
                ),
            ),
            __('Time') => array(
                'icon' => 'time',
                'url' => array(
                    'action' => array('*'),
                    'controller' => 'times',
                    'project' => $this->params['project'],
                ),
            ),
            __('Chart') => array(
                'icon' => 'signal',
                'url' => array(
                    'action' => array('burndown'),
                    'controller' => 'projects',
                    'project' => $this->params['project'],
                ),
            ),
            __('Source') => array(
                'icon' => 'pencil',
                'url' => array(
                    'action' => array('.', 'tree', 'gettingStarted', 'commits', 'commit', 'history'),
                    'controller' => 'source',
                    'project' => $this->params['project'],
                ),
            ),
            __('Tasks') => array(
                'icon' => 'file',
                'url' => array(
                    'action' => array('*'),
                    'controller' => 'tasks',
                    'project' => $this->params['project'],
                ),
			),
		    '-> '.__('My tasks') => array(
                'icon' => 'file',
		        'url' => array(
		            'action' => array('*'),
					'?' => 'assignees='.urlencode($current_user['id']).'&statuses=open,in+progress',
		            'controller' => 'tasks',
		            'project' => $this->params['project'],
		        ),
		    ),
            __('Milestones') => array(
                'icon' => 'road',
                'url' => array(
                    'action' => array('*'),
                    'controller' => 'milestones',
                    'project' => $this->params['project'],
                ),
            ),
            __('Attachments') => array(
                'icon' => 'download',
                'url' => array(
                    'action' => array('*'),
                    'controller' => 'attachments',
                    'project' => $this->params['project'],
                ),
            ),
        ),
        'help' => array(
            'action' => 'overview',
        ),
    );

    if(isset($isAdmin) && $isAdmin) {
        $options[__('Administration')] = array(
            __('Collaborators') => array(
                'icon' => 'user',
                'url' => array(
                    'action' => '.',
                    'controller' => 'collaborators',
                    'project' => $this->params['project'],
                ),
            ),
            __('Settings') => array(
                'icon' => 'cog',
                'url' => array(
                    'action' => 'edit',
                    'controller' => 'projects',
                    'project' => $this->params['project'],
                ),
            ),
        );
    }

    echo $this->element('Sidebar/generic', array('options' => $options));
