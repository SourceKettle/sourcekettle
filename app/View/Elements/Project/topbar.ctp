<?php
/**
 *
 * Element for displaying the task topbar in project view for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Project
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

 $options = array(
    'left' => array(
        array(
            array(
                'text' => __('My projects'),
                'url' => array(
                    'action' => '.',
                    'controller' => 'projects',
                ),
            ),
            array(
                'text' => __('Team projects'),
                'url' => array(
                    'action' => 'team_projects',
                    'controller' => 'projects',
                ),
            ),
            array(
                'text' => __('Public projects'),
                'url' => array(
                    'action' => 'public_projects',
                    'controller' => 'projects',
                ),
            ),
        ),
    ),
    'right' => array(
        array(
            array(
                'text' => __('New Project'),
                'url' => array(
                    'action' => 'add',
                    'controller' => 'projects',
                ),
                'props' => array('class' => 'btn-primary'),
            ),
        ),
    ),
);

echo $this->element('Topbar/button', array('options' => $options, 'span' => 12));
