<?php
/**
 *
 * View class for APP/teams/admin_add for the SourceKettle system
 * View allow admin to create a new team
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2014
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Teams
 * @since         SourceKettle v 1.5
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header('Administration <small>organise your hackers</small>'); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/admin') ?>
    </div>
    <div class="span10">
        <div class="row-fluid">
            <?php
            echo $this->Form->create('Team', array('class' => 'span12 well form-horizontal', 'action' => 'admin_edit'));

			echo $this->Form->input('id');
            echo '<h3>'.__('Update team details').'</h3>';

            echo $this->Bootstrap->input("name", array(
                "input" => $this->Form->text("name", array("class" => "span5")),
            ));

            echo $this->Bootstrap->input("description", array(
                "input" => $this->Form->text("description", array("class" => "span11")),
            ));

            echo $this->Bootstrap->input("users", array(
				"input" => $this->Form->input('User', array('label' => false, "size" => 20, "class" => "span11"))
			));

            echo $this->Bootstrap->button(__('Update'), array("style" => "primary", "size" => "large", 'class' => 'controls'));

            echo $this->Form->end();
            ?>
        </div>
    </div>
</div>
