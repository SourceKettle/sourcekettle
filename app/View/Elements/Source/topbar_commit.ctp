<?php
/**
 *
 * Element for displaying the source topbar for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Topbar
 * @since         DevTrack v 0.1
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
                'text' => $this->DT->t('bar.tree'),
                'url' => array(
                    'action' => 'tree',
                    'controller' => 'source',
                    'branch' => $commit['hash'],
                ),
            ),
            array(
                'text' => $this->DT->t('bar.commits'),
                'url' => array(
                    'action' => 'commits',
                    'controller' => 'source',
                    'branch' => $commit['hash'],
                ),
            ),
        ),
    ),
    'right' => array(
        array(
            array(
                'text' => $this->DT->t('bar.getthecode'),
                'url' => array(
                    'action' => 'gettingStarted',
                    'controller' => 'source',
                ),
            ),
        ),
        array(
            array(
                'text' => $this->Bootstrap->icon('random', 'white')." ".$this->DT->t('bar.branch'),
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
