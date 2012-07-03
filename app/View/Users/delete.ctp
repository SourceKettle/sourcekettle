<?=$this->Bootstrap->page_header($this->request->data['User']['name'])?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/users') ?>
    </div>

    <div class="span6 offset1">
        <div class="well">
            <h3>Delete your account</h3>
            <p>Please note, this action is not reversible. This will also delete any projects for which you are the only contributor.</p>
            <?php
            echo $this->Bootstrap->button_form("Delete my account", array("action" => "delete"), array("style" => "danger", "size" => "large"), "Are you sure you want to delete your account?");
            ?>
        </div>
    </div>
</div>