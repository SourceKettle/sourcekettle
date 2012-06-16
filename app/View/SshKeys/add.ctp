<?=$this->Bootstrap->page_header($this->request->data['User']['name'])?>

<div class="row">
    <div class="span2">
        <?= $this->element('users_sidebar', array('action' => 'addkey')) ?>
    </div>

    <div class="span6 offset1">
        <?php
        echo $this->Form->create('SshKey', array('class' => 'well form-horizontal', 'url' => array('controller' => 'sshKeys', 'action' => 'add')));
        echo '<h3>Add an SSH key</h3>';
        echo $this->Bootstrap->input("key", array(
            "input" => $this->Form->textarea("key", array("class" => "input-xlarge")),
            "label" => "SSH Key",
        ));

        echo $this->Bootstrap->input("comment", array(
            "input" => $this->Form->text("comment", array("class" => "input-xlarge")),
        ));

        echo $this->Bootstrap->button("Add", array("style" => "primary", "size" => "large", 'class' => 'controls'));

        echo $this->Form->end();
        ?>
    </div>
</div>