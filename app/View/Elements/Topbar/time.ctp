<?php
/**
 *
 * Element for displaying the time topbar for the DevTrack system
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
 $options = array(
    'History' => array(
        'url' => array(
            'action' => 'history',
            'controller' => 'times',
        ),
    ),
    'User Stats' => array(
        'url' => array(
            'action' => 'users',
            'controller' => 'times',
        ),
    ),
    'Log Time' => array(
        'align' => 'right',
        'url' => '#addTimeModal',
        'data-toggle' => 'modal',
        'class' => 'btn-primary',
    ),
);

echo $this->element('Topbar/generic', array('options' => $options));
echo $this->element('Time/modal_add');