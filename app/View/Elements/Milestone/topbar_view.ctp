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
 $pl = $this->Text->truncate($name, 20);

 $options = array(
    'back' => $previousPage,
    'left' => array(
        array(
            array(
                'text' => $this->DT->t('topbar.edit.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'edit',
                    'controller' => 'milestones',
                    $id
                ),
            ),
            array(
                'text' => $this->DT->t('topbar.delete.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'delete',
                    'controller' => 'milestones',
                    $id
                ),
                'props' => array('class' => 'btn-danger'),
            ),
        ),
    ),
    'right' => array(
        array(
            array(
                'text' => $this->DT->t('topbar.create.text', array('action' => 'topbar')),
                'url' => array(
                    'action' => 'add',
                    'controller' => 'milestones',
                ),
                'props' => array('class' => 'btn-primary'),
            ),
        ),
    ),
);

echo $this->element('Topbar/button', array('options' => $options, 'pl' => $pl));
