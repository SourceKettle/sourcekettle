<?=$this->Bootstrap->page_header($this->request->data['User']['name'])?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/users') ?>
    </div>
    <? if($external_account):?>
    <div class="span6">
        <div class="well">
            <h3>Your account cannot be deleted</h3>
            <p>We're sorry you want to leave, but it's not quite that simple!</p>
            <p>This SourceKettle system is set up to use external authentication - most likely, you have logged in with a user account provided by your organisation.  There's not a lot we can do about that :-(</p>
        <h3>Problem?</h3>
        <p>
          If you think something's gone horribly wrong and you really <strong>should</strong> be able to delete your account, it's probably worth having a chat with <?= $this->Html->link('the system administrator', 'mailto:'.$devtrack_config['sysadmin_email']) ?>!
        </p>
        </div>
    </div>
    <? else: ?>
    <div class="span6">
        <div class="well">
            <h3>Delete your account</h3>
            <p>Please note, this action is not reversible. This will also delete any projects for which you are the only contributor.</p>
            <?php
            echo $this->Bootstrap->button_form("Delete my account", array("action" => "delete"), array("style" => "danger", "size" => "large"), "Are you sure you want to delete your account?");
            ?>
        </div>
    </div>
    <? endif ?>

    <div class="span4">
        <h3>Leaving so soon?</h3>
        <p>
            Here at SourceKettle, we like making sure that it wasn't us that made you want to leave.
            We'll try harder, honest! Call more often, meet your friends...we will even laugh at more of your jokes!
        </p>
        <p>
            Seriously, if it was us, if SourceKettle isn't good enough or doesnt do what you want, please let us know by leaving feedback on 
            <?= $this->Html->link('GitHub', 'https://github.com/SourceKettle/sourcekettle') ?>.
        </p>
    </div>
</div>
