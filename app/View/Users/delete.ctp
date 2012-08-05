<?=$this->Bootstrap->page_header($this->request->data['User']['name'])?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/users') ?>
    </div>

    <div class="span6">
        <div class="well">
            <h3>Delete your account</h3>
            <p>Please note, this action is not reversible. This will also delete any projects for which you are the only contributor.</p>
            <?php
            echo $this->Bootstrap->button_form("Delete my account", array("action" => "delete"), array("style" => "danger", "size" => "large"), "Are you sure you want to delete your account?");
            ?>
        </div>
    </div>
    <div class="span4">
        <h3>Leaving so soon?</h3>
        <p>
            Here at DevTrack, we like making sure that it wasn't us that made you want to leave.
            We'll try harder, honest! Call more often, meet your friends...we will even laugh at more of your jokes!
        </p>
        <p>
            Seriously, if it was us, if DevTrack isn't good enough or doesnt do what you want, please let us know by leaving feedback on 
            <?= $this->Html->link('GitHub', 'https://github.com/chriswbulmer/devtrack/') ?>.
        </p>
    </div>
</div>
