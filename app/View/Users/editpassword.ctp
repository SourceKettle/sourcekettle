<?=$this->Bootstrap->page_header($this->request->data['User']['name'])?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/users') ?>
    </div>
    
    <div class="span6">
        <?php
        echo $this->Form->create('User', array('class' => 'well form-horizontal', 'action' => 'editpassword'));
        echo '<h3>Change your password</h3>';

        echo $this->Bootstrap->input("Current password", array(
            "input" => $this->Form->password("password_current"),
        ));

        echo $this->Bootstrap->input("new password", array(
            "input" => $this->Form->password("password"),
        ));

        echo $this->Bootstrap->input("Confirm password", array(
            "input" => $this->Form->password("password_confirm"),
        ));
        echo $this->Bootstrap->button("Save", array("style" => "primary", "size" => "large", 'class' => 'controls'));

        echo $this->Form->end();
        ?>
    </div>
    <div class="span4">
        <h3>What makes a super s3cr3t_pa55W0rd?</h3>
        <p>
            Here at DevTrack, we arn't your parents.<br>
            However we do know a thing or two (maybe more) about what makes a secure password.
        </p>
        <p>
            We also love the sound of our own voices, so we've put together some handy hints:
            <dl>
                <dt>Avoid:</dt>
                <dd>
                <ul>
                    <li>Dictionary words in any language.</li>
                    <li>Words spelled backwards, common misspellings, and abbreviations.</li>
                    <li>Sequences or repeated characters or adjacent letters on your keyboard.</li>
                    <li>Personal information. Your name, birthday, driver's license, passport number, or similar information.</li>
                </ul>
                </dd>
                <dt>Most importantly</dt>
                <dd>The longer a password is the harder it is to crack.</dd>
            </dl>
        </p>
        <p>
            In summary, 'password' is not a good password.
        </p>
    </div>
</div>
