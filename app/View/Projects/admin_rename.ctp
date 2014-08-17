<?php
/**
 *
 * View class for APP/projects/admin_rename for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Projects
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$details = array(
    '2' => array(
        'icon' => 'wrench',
        'text' => 'Admin',
        'action' => 'admin_makeadmin',
    ),
    '1' => array(
        'icon' => 'user',
        'text' => 'User',
        'action' => 'admin_makeuser',
    ),
    '0' => array(
        'icon' => 'search',
        'text' => 'Guest',
        'action' => 'admin_makeguest',
    ),
);

echo $this->Bootstrap->page_header('Administration <small>they called it *what*??</small>'); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/admin') ?>
    </div>
    <div class="span10">
        <div class="row-fluid">
            <?=$this->Form->create('Project', array('class' => 'span7 well form-horizontal')); ?>

            <h3>Rename project</h3>

            <?=$this->Bootstrap->input("New name", array(
                "input" => $this->Form->text("name", array("class" => "span11")),
            ));?>

            <?=$this->Bootstrap->button("Rename project", array("style" => "primary", "size" => "large", 'class' => 'controls'));?>

            <?=$this->Form->end();?>
     	</div>
    </div>
</div>
<style type="text/css">.btn-group {float: right; margin-right: 10px;}</style>
