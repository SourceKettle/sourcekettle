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
 $pl = __('Time')." #$id";

 $options = array(
    'links' => array(
        array(
            'text' => __('Edit'),
            'url' => array(
                'action' => 'edit',
                'controller' => 'times',
                $id
            ),
        ),
        array(
            'text' => __('Delete'),
            'url' => array(
                'action' => 'delete',
                'controller' => 'times',
                $id
            ),
            'props' => array('style' => 'danger'),
        ),
        array(
            'text' => __('Log Time'),
            'url' => array(
                'action' => 'add',
                'controller' => 'times',
            ),
            'props' => array('class' => 'btn-primary'),
			'pull-right' => true,
        ),
    ),
);

echo $this->element('Topbar/pills', array('options' => $options, 'pl' => $pl));
