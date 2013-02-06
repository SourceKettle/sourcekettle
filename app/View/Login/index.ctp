<?php
/**
*
* Login form for the SourceKettle system
* Renders the form which users can use to login
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

?>
<div class="row">
    <div class="span6 offset3">
        <?= $this->Form->create('User', array('class' => 'well form-horizontal')) ?>
        <div class="row-fluid">
            <h1>Login to SourceKettle</h1>
            <p>Don't have an account? <?= $this->Html->link('Register here', '/register') ?></p>

            <?php
            echo $this->Bootstrap->input("email", array(
                "input" => $this->Form->text("email", array('class'=>'span12', 'tabindex'=>'1')),
            ));

            echo $this->Bootstrap->input("password", array(
                "input" => $this->Form->password("password", array('class'=>'span12', 'escape'=>false, 'tabindex'=>'2')),
                "label" => "Password (".$this->Html->link('Forgot?','/users/lost_password').')',
            ));

            echo $this->Bootstrap->button("Login", array("style" => "primary", "size" => "large", 'class' => 'controls', 'tabindex'=>'3'));
            ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>

