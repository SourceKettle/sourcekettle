<?php
/**
 *
 * View class for APP/settings/admin_index for the DevTrack system
 * View will render system wide settings
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Settings
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$options = array(
    '1' => array(
        'icon' => 'ok',
        'text' => 'On',
        '!icon' => 'remove',
        '!text' => 'Turn Off',
        'action' => array('register_enabled' => '0'),
        'colour' => 'success',
    ),
    '0' => array(
        'icon' => 'remove',
        'text' => 'Off',
        '!icon' => 'ok',
        '!text' => 'Turn On',
        'action' => array('register_enabled' => '1'),
        'colour' => 'danger',
    ),
);

echo $this->Bootstrap->page_header('Administration <small>system-wide configuration</small>'); ?>
	
<div class="row">
    <div class="span2">
        <?= $this->element('admin_sidebar', array('action' => 'settings')) ?>
    </div>
    <div class="well span9">
        <h3>System wide configuration options</h3>
        <table class="table table-striped">
            <?= $this->Form->create('Settings')//, array('class' => 'well form-search')) ?>
            <tbody>
                <tr>
                    <td>
                        <h4>Allow Registration <small>- allow for new users to create accounts</small></h4>
                    </td>
                    <td>
                    <?php
                        echo $this->Bootstrap->button_dropdown($this->Bootstrap->icon($options[$register]['icon'], 'white')." ".$options[$register]['text'], array(
                            "style" => $options[$register]['colour'],
                            "size" => "mini",
                            "links" => array(
                                $this->Html->link($this->Bootstrap->icon($options[$register]['!icon'])." ".$options[$register]['!text'],
                                        array('admin' => true, 'controller' => 'settings', 'action' => 'edit', json_encode($options[$register]['action'])),
                                        array('escape' => false)),
                                )
                        ));
                    ?>
                    </td>
                </tr>
            </tbody>
            <?= $this->Form->end() ?>
        </table>
    </div>
</div>
