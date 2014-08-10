<?php
/**
 *
 * Element for displaying the source topbar for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Source
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$renderableBranches = array();
foreach ($branches as $b) {
    $renderableBranches[] = $this->Html->link($b, array('project' => $project['Project']['name'], 'action' => 'tree', $b));
}

 $options = array(
    'left' => array(
        array(
            array(
                'text' => __("Source"),
                'url' => array(
                    'action' => 'tree',
                    'controller' => 'source',
                    'branch' => $branch,
                ),
            ),
            array(
                'text' => __("Commits"),
                'url' => array(
                    'action' => 'commits',
                    'controller' => 'source',
                    'branch' => $branch,
                ),
            ),
        ),
    ),
    'right' => array(
        array(
            array(
                'text' => __("Get The Code"),
                'url' => array(
                    'action' => 'gettingStarted',
                    'controller' => 'source',
                ),
            ),
        ),
        array(
            array(
                'text' => $this->Bootstrap->icon('random', 'white')." ".__("Branch:").$branch,
                'url' => '#',
                'type' => 'dropdown',
                'props' => array(
                    'style' => 'inverse',
                    'links' => $renderableBranches
                ),
            ),
        ),
    ),
);

echo $this->element('Topbar/button', array('options' => $options));
