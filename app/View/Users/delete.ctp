
<div class="row-fluid">
    <div class="span2">
        <?= $this->element('Sidebar/users') ?>
    </div>
    <? if($external_account):?>
    <div class="span6">
        <div class="well">
            <h3><?=__("Your account cannot be deleted")?></h3>
            <p><?=__("We're sorry you want to leave, but it's not quite that simple!")?></p>
            <p><?=__("This system is set up to use external authentication - most likely, you have logged in with a user account provided by your organisation.  There's not a lot we can do about that :-(")?></p>
        <h3><?=__("Problem?")?></h3>
        <p>
          <?=__("If you think something's gone horribly wrong and you really <strong>should</strong> be able to delete your account, it's probably worth having a chat with %s!", $this->Html->link(__('the system administrator'), 'mailto:'.$sourcekettle_config['Users']['sysadmin_email']['value']))?>
        </p>
        </div>
    </div>
    <? else: ?>
    <div class="span6">
        <div class="well">
            <h3><?=__("Delete your account")?></h3>
            <p><?=__("Please note, this action is not reversible. This will also delete any projects for which you are the only contributor.")?></p>
            <?php
            echo $this->Bootstrap->button_form(__("Delete my account"), array("action" => "delete"), array("style" => "danger", "size" => "large"), __("Are you sure you want to delete your account?"));
            ?>
        </div>
    </div>
    <? endif ?>

    <div class="span4">
        <h3><?=__("Leaving so soon?")?></h3>
        <p>
            <?=__("Here at %s, we like making sure that it wasn't us that made you want to leave.", $sourcekettle_config['UserInterface']['alias']['value'])?>
            <?=__("We'll try harder, honest! Call more often, meet your friends...we will even laugh at more of your jokes!")?>
        </p>
        <p>
            <?=__("Seriously, if it was us, if %s isn't good enough or doesnt do what you want, please let us know by leaving feedback on %s.", 
			$sourcekettle_config['UserInterface']['alias']['value'],
            $this->Html->link('GitHub', 'https://github.com/SourceKettle/sourcekettle'))?>
        </p>
    </div>
</div>
