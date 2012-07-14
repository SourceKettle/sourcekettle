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

$reg_op = array(
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
        <?= $this->element('Sidebar/admin') ?>
    </div>
    <div class="well span9">
        <?= $this->Form->create('Settings', array('action'=>'edit')) ?>
        <h3>System wide configuration options</h3>
        <table class="table table-striped">
            <tbody>
                <tr>
                    <td>
                        <h4>Allow Registration <small>- allow for new users to create accounts</small></h4>
                    </td>
                    <td>
                        <div class="pull-right  form-search">
                        <?php
                            echo $this->Bootstrap->button_dropdown($this->Bootstrap->icon($reg_op[$register]['icon'], 'white')." ".$reg_op[$register]['text'], array(
                                "style" => $reg_op[$register]['colour'],
                                "size" => "mini",
                                "links" => array(
                                    $this->Html->link($this->Bootstrap->icon($reg_op[$register]['!icon'])." ".$reg_op[$register]['!text'],
                                            array('admin' => true, 'controller' => 'settings', 'action' => 'edit', json_encode($reg_op[$register]['action'])),
                                            array('escape' => false)),
                                    )
                            ));
                        ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h4>Admin email address <small>- where are emails come from</small></h4>
                    </td>
                    <td>
                        <div class="pull-right">
                        <?php
                            echo $this->Bootstrap->input("sysadmin_email", array(
                                "input" => $this->Form->text("sysadmin_email", array("class" => "input-xlarge search-query", "value" => $sysadmin_email)).' '.$this->Bootstrap->button("Update", array("style" => "primary", "size" => "mini")),
                                "label" => false,
                            ));
                            
                        ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <?= $this->Form->end() ?>
    </div>
</div>
