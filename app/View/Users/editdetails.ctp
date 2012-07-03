<?= $this->Bootstrap->page_header($this->request->data['User']['name']) ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/users') ?>
    </div>
    <div class="span6 offset1">
        <?php
        echo $this->Form->create('User', array('class' => 'well form-horizontal', 'action' => 'editdetails'));
        echo '<h3>Edit your details</h3>';
        echo $this->Bootstrap->input("name", array(
            "input" => $this->Form->text("name"),
        ));

        echo $this->Bootstrap->input("email", array(
            "input" => $this->Form->text("email"),
        ));
        echo $this->Bootstrap->button("Update", array("style" => "primary", "size" => "large", 'class' => 'controls'));

        echo $this->Form->end();
        ?>
    </div>
</div>