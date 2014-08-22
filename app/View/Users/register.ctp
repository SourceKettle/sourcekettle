<?php
/**
*
* Registration view for the SourceKettle system
* Renders a form allowing users to sign up
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
* 
* @copyright     SourceKettle Development Team 2012
* @link          https://github.com/SourceKettle/sourcekettle
* @package       SourceKettle.View.Login
* @since         SourceKettle v 0.1
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

echo $this->Bootstrap->page_header("Register with ".$sourcekettle_config['global']['alias']." <small>Hello! Bonjour! Willkommen!..</small>");
?>
<div class="row">
    <div class="span8">
        <?php
        echo $this->Form->create('User', array('class' => 'well form-horizontal'));
        
        echo $this->Bootstrap->input("name", array(
            "input" => $this->Form->text("name", array("class" => "input-xlarge", "placeholder" => "John Smith")),
        ));
        
        echo $this->Bootstrap->input("email", array(
            "input" => $this->Form->text("email", array("class" => "input-xlarge", "placeholder" => "john.smith@example.com")),
        ));
        
        echo $this->Bootstrap->input("password", array(
            "input" => $this->Form->password("password", array("class" => "input-xlarge")),
            "help_block" => "Must be at least 8 characters."
        ));
        
        echo $this->Bootstrap->input("Confirm Password", array(
            "input" => $this->Form->password("password_confirm", array("class" => "input-xlarge")),
            "help_block" => "Just to make sure."
        ));
        
        echo $this->Bootstrap->input("ssh_key", array(
            "input" => $this->Form->textarea("ssh_key", array("class" => "input-xlarge")),
            "label" => "SSH Key",
            "help_inline" => "(Optional)",
            "help_block" => "SSH keys make working with a repository easier.<br>You can add one at any time."
        ));
        
        echo $this->Bootstrap->button("Signup", array("style" => "primary", "size" => "large", 'class' => 'controls'));
        
        echo $this->Form->end();
        ?>
    </div>
    <div class="span4">
        <h3>What is SourceKettle?</h3>
        <p>
            SourceKettle is a <strong>project management</strong> and <strong>source code management</strong> tool designed to make developing applications simpler.
            It allows you to use an <strong>Subversion</strong> or <strong>Git</strong> repository to host your code and allows for easily managing tasks, the 
            time spent developing and documentation throughout your projects.
        </p>
        <br>
        <h3>What makes SourceKettle special?</h3>
        <p>
            SourceKettle is <strong>all</strong> of the following:
            <ul>
                <li>Open Source</li>
                <li>Simple to use</li>
                <li>Easy to install</li>
                <li>Developer and Admin friendly</li>
            </ul>
        </p>
        <br>
        <h3>What do our developers think?</h3>
        <blockquote>
            <p>I wish SourceKettle was around when we were developing SourceKettle!</p>
            <small>@pwhittlesea</small>
        </blockquote>
    </div>
</div>
