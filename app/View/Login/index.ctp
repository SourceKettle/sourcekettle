<?php
/**
*
* Login form for the DevTrack system
* Renders the form which users can use to login
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
* 
* @copyright     DevTrack Development Team 2012
* @link          http://github.com/chrisbulmer/devtrack
* @package       DevTrack.View.Login
* @since         DevTrack v 0.1
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

?>
<div class="row">
    <div class="span6 offset3">
        <?php
        echo $this->Form->create('User', array('class' => 'well form-horizontal'));
        echo "<h1>Login to DevTrack</h1><p>Don't have an account? ".$this->Html->link('Register here', '/register')."</p>";

        echo $this->Bootstrap->input("email", array(
            "input" => $this->Form->text("email"),
        ));
        
        echo $this->Bootstrap->input("password", array(
            "input" => $this->Form->password("password"),
        ));

        echo $this->Bootstrap->button("Login", array("style" => "primary", "size" => "large", 'class' => 'controls'));
        
        echo $this->Bootstrap->button_link("I forgot my password", "/users/lost_password", array("style" => "default", "size" => "normal", 'class' => 'pull-right'));
        
        echo $this->Form->end();
        ?>
    </div>
</div>

