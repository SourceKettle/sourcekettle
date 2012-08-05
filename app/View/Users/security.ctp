<?php
/**
 *
 * View class for APP/users/security for the DevTrack system
 * Displays a form to let the user update their password.
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
 
echo $this->Bootstrap->page_header($this->request->data['User']['name'])?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/users') ?>
    </div>
    
    <div class="span6 offset1">
        <?php
        echo $this->Form->create('User', array('class' => 'well form-horizontal', 'type' => 'post'));
        echo '<h3>Change your password</h3>';

        echo $this->Bootstrap->input("Current password", array(
            "input" => $this->Form->password("password_current"),
        ));

        echo $this->Bootstrap->input("new password", array(
            "input" => $this->Form->password("password"),
        ));

        echo $this->Bootstrap->input("Confirm password", array(
            "input" => $this->Form->password("password_confirm"),
        ));
        echo $this->Bootstrap->button("Save", array("style" => "primary", "size" => "large", 'class' => 'controls'));

        echo $this->Form->end();
        ?>
    </div>
    
    
</div>