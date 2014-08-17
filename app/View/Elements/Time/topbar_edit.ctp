<?php
/**
 *
 * Element for displaying the time topbar for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Topbar
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
 $pl = $this->DT->t('bar.time').$id;

 $options = array(
    'left' => array(
        array(
            array(
                'text' => $this->DT->t('bar.view'),
                'url' => array(
                    'action' => 'view',
                    'controller' => 'times',
                    $id
                ),
            ),
            array(
                'text' => $this->DT->t('bar.delete'),
                'url' => array(
                    'action' => 'delete',
                    'controller' => 'times',
                    $id
                ),
                'props' => array('style' => 'danger'),
            ),
        ),
    ),
    'right' => array(),
);

echo $this->element('Topbar/button', array('options' => $options, 'pl' => $pl));
