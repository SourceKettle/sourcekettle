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

echo $this->Session->flash('auth');

/*echo $this->Form->create('Login');
echo $this->Form->input('email');
echo $this->Form->input('password');
echo $this->Form->end('Login');*/

?>
<br>
<div class="row">
    <div class="span6 offset3">
        <?php
        echo $this->Form->create('login', array('class' => 'well form-horizontal'));
        echo '<h1>Login to DevTrack</h1>';

        echo $this->Bootstrap->input("email", array(
            "input" => $this->Form->text("Email"),
        ));
        
        echo $this->Bootstrap->input("password", array(
            "input" => $this->Form->password("Password"),
        ));

        echo $this->Bootstrap->button("Login", array("style" => "primary", "size" => "large", 'class' => 'controls'));
        
        //echo "<br><br>";
        //echo $this->Bootstrap->button_link("Register", "/register", array("style" => "default", "size" => "small", 'class' => 'controls'));
        echo $this->Bootstrap->button_link("I forgot my password", "/login/lost_password", array("style" => "default", "size" => "small", 'class' => 'controls'));

        echo $this->Form->end();
        ?>
    </div>
</div>

