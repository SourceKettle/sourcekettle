<?php
/**
 *
 * View class for APP/users/admin_add for the DevTrack system
 * View allow admin to create a new user
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Users
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header('Administration <small>add a new sheep to your flock</small>'); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/admin') ?>
    </div>
    <div class="span10">
        <div class="row-fluid">
            <?php
            echo $this->Form->create('User', array('class' => 'span7 well form-horizontal', 'action' => 'admin_add'));

            echo '<h3>New users details</h3>';

            echo $this->Bootstrap->input("name", array(
                "input" => $this->Form->text("name", array("class" => "span11")),
            ));

            echo $this->Bootstrap->input("email", array(
                "input" => $this->Form->text("email", array("class" => "span11")),
            ));

            echo $this->Bootstrap->input("System Admin", array(
                "input" => $this->Form->checkbox("is_admin"),
            ));

            echo $this->Bootstrap->input("Account Active", array(
                "input" => $this->Form->checkbox("is_active"),
            ));

            echo $this->Bootstrap->button("Create", array("style" => "primary", "size" => "large", 'class' => 'controls'));

            echo $this->Form->end();
            ?>
        </div>
    </div>
</div>
