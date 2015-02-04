<?php
/**
 *
 * View class for APP/users/admin_view for the SourceKettle system
 * View will render a user
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Users
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<div class="row-fluid">
    <?php
    echo $this->Form->create('User', array('class' => 'span7 well form-horizontal', 'action' => 'admin_edit'));

    echo '<h3>Edit users details</h3>';

    echo $this->Bootstrap->input("name", array(
        "input" => $this->Form->text("name", array("class" => "span11")),
    ));

    echo $this->Bootstrap->input("email", array(
        "input" => $this->Form->text("email", array("class" => "span11")),
    ));

    echo $this->Bootstrap->input("System Admin", array(
        "input" => $this->Form->checkbox("is_admin"),
    ));

    echo $this->Bootstrap->input("Account Active", array(
        "input" => $this->Form->checkbox("is_active"),
    ));

    echo $this->Bootstrap->button("Update", array("style" => "primary", "size" => "large", 'class' => 'controls'));

    echo $this->Form->end();
    ?>
    <div class="span5 well">
        <h3>Additional Information</h3>
        <dl class="dl-horizontal">
            <dt>User registered</dt><dd><?= $this->Time->timeAgoInWords($this->request->data['User']['created']) ?></dd>

            <dt>Last modified</dt><dd><?= $this->Time->timeAgoInWords($this->request->data['User']['modified']) ?></dd>

            <dt>Ssh Keys</dt><dd><?= count($this->request->data['SshKey']) ?></dd>
            <dt>Internal Account</dt><dd><?= $this->request->data['User']['is_internal'] ? 'Yes' : 'No' ?></dd>

        </dl>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <h2 style="text-align:center;">Users Projects</h2>
    </div>
</div>

<div class="row-fluid">
	<?= $this->Element("Project/projectgrid") ?>
</div>
