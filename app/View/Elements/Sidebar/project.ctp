<?php

/**
 *
 * Element for displaying the project sidebar for the DevTrack system
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
        'Features' => array(
            'Overview' => array(
                'icon' => 'home',
                'url' => array(
                    'action' => 'view',
                    'controller' => 'projects',
                    'project' => $this->params['project'],
                ),
            ),
            'Time' => array(
                'icon' => 'book',
                'url' => array(
                    'action' => 'time',
                    'controller' => 'projects',
                    'project' => $this->params['project'],
                ),
            ),
            'Source' => array(
                'icon' => 'pencil',
                'url' => array(
                    'action' => array('.', 'tree', 'gettingStarted', 'commits', 'commit'),
                    'controller' => 'source',
                    'project' => $this->params['project'],
                ),
            ),
            'Tasks' => array(
                'icon' => 'file',
                'url' => array(
                    'action' => 'tasks',
                    'controller' => 'projects',
                    'project' => $this->params['project'],
                ),
            ),
        ),
        'help' => array(
            'action' => 'project',
        ),
    );

    if(isset($isAdmin) && $isAdmin) {
        $options['Administration'] = array(
            'Collaborators' => array(
                'icon' => 'user',
                'url' => array(
                    'action' => '.',
                    'controller' => 'collaborators',
                    'project' => $this->params['project'],
                ),
            ),
            'Settings' => array(
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
