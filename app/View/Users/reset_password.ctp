<div class="row-fluid">
    <div class="span6 offset3">
        
        <?php
        echo $this->Form->create('User', array('class' => 'well form-horizontal'));
        echo "<h1>Reset your password</h1>";
        echo $this->Bootstrap->input("password", array(
            "input" => $this->Form->password("password"),
            "label" => "Please enter a new password"
        ));
        
        echo $this->Bootstrap->input("password)_confirm", array(
            "input" => $this->Form->password("password_confirm"),
            "label" => "Please confirm your new password"
        ));

        echo $this->Bootstrap->button("Reset", array("style" => "primary", "size" => "large", 'class' => 'controls'));

        echo $this->Form->end();
        ?>
    </div>
</div>
