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
                    'project' => $project,
                ),
            ),
            'Time' => array(
                'icon' => 'book',
                'url' => array(
                    'action' => 'time',
                    'controller' => 'projects',
                    'project' => $project,
                ),
            ),
            'Source' => array(
                'icon' => 'pencil',
                'url' => array(
                    'action' => '.',
                    'controller' => 'source',
                    'project' => $project,
                ),
            ),
            '&nbsp;&nbsp;Code' => array(
                'icon' => null,
                'url' => array(
                    'action' => 'tree',
                    'controller' => 'source',
                    'project' => $project,
                ),
            ),
            '&nbsp;&nbsp;Commits' => array(
                'icon' => null,
                'url' => array(
                    'action' => 'commits',
                    'controller' => 'source',
                    'project' => $project,
                ),
            ),
            'Tasks' => array(
                'icon' => 'file',
                'url' => array(
                    'action' => 'tasks',
                    'controller' => 'projects',
                    'project' => $project,
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
                    'project' => $project,
                ),
            ),
            'Settings' => array(
                'icon' => 'cog',
                'url' => array(
                    'action' => 'edit',
                    'controller' => 'projects',
                    'project' => $project,
                ),
            ),
        );
    }

    echo $this->element('generic_sidebar', array('options' => $options));
