<?php
/**
 *
 * Element for displaying the milestone topbar for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements.Topbar
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
 $options = array(
    $this->DT->t('topbar.option1.text', array('action' => 'topbar')) => array(
        'url' => array(
            'action' => 'open',
            'controller' => 'milestones',
        ),
    ),
    $this->DT->t('topbar.option2.text', array('action' => 'topbar')) => array(
        'url' => array(
            'action' => 'closed',
            'controller' => 'milestones',
        ),
    ),
);

echo $this->element('Topbar/generic', array('options' => $options));
