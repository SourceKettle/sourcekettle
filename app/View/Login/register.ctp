<?php
/**
*
* Registration view for the DevTrack system
* Renders a form allowing users to sign up
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
<h1>Register with DevTrack</h1>
<div class="row">
    <div class="span8">
        <?php
        echo $this->Form->create('User', array('class' => 'well form-horizontal'));
        echo '<h3>Create your account</h3>';

        echo $this->Bootstrap->input("name", array(
            "input" => $this->Form->text("name"),
            "help_block" => "Text under the input"
        ));
        
        echo $this->Bootstrap->input("email", array(
            "input" => $this->Form->text("email"),
            "help_block" => "Your email address will not be shared."
        ));
        
        echo $this->Bootstrap->input("password", array(
            "input" => $this->Form->password("password"),
            "help_block" => "Must be at least 8 characters and contain at least one number and letter."
        ));
        
        echo $this->Bootstrap->input("Confirm Password", array(
            "input" => $this->Form->password("password_confirm"),
            "help_block" => "Just to make sure."
        ));
        
        echo $this->Bootstrap->input("ssh_key", array(
            "input" => $this->Form->textarea("ssh_key"),
            "label" => "SSH Key",
            "help_inline" => "(Optional)",
            "help_block" => "SSH keys make working with a repository easier. You can add one at any time."
        ));
        
        echo $this->Bootstrap->button("Signup", array("style" => "primary", "size" => "large", 'class' => 'controls'));
        
        echo $this->Form->end();
        ?>
    </div>
    <div class="span4">
        <h3>What is DevTrack?</h3>
        <br>
        <p>
            DevTrack is a project management and source code management tool designed to make developing applications simpler.
            It allows you to use an Subversion or Git repository to host your code and allows for easily managing tasks, the 
            time spent developing and documentation throughout your projects.
        </p>
    </div>
</div>