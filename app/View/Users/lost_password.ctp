<div class="row-fluid">
    <div class="span6 offset3">
        
        <?php
        echo $this->Form->create('User', array('class' => 'well form-horizontal'));
        echo "<h1>Reset your password</h1>";
        echo $this->Bootstrap->input("email", array(
            "input" => $this->Form->text("email"),
            "label" => "Please enter your email"
        ));

        echo $this->Bootstrap->button("Reset", array("style" => "primary", "size" => "large", 'class' => 'controls'));

        echo $this->Form->end();
        ?>
    </div>
</div>
